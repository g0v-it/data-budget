#!/usr/bin/env php
<?php

/**
 * Gateway per trasformare i disegni di legge di bilancio, le leggi di bilancio 
 * e rendiconti spesa per capitolo su BDAP da  formato csv a rdf g0v-ap
 *  
 **/
isset($argv[1]) || die("Gateway usage: bdap <dataset_id>");

include "Helper.php";

/**
 * Detect input schema from the dataset id (csv expected)
 */

$bdapDatasetId = $argv[1];

// extract  $bdapDatasetYear and bdapDatasetType from $bdapDatasetId
preg_match("/^spd_(lbf|rnd|dlb)_spe_elb_cap_01_(20[0-9]{2})$/", $bdapDatasetId, $matches) || die("Unreconized dataset id $bdapDatasetId");
$bdapDatasetYear= intval($matches[2]);
$bdapDatasetType= $matches[1];


// detect $mefReportClass
switch ($bdapDatasetType) {
    case "dlb":
        $mefReportClass="DisegnoLeggeDiBilancio";
        break;
    case "lbf":
        $mefReportClass="LeggeDiBilancio";
        break;
    case "rnd":
        $mefReportClass="RendicontoFinanziario";
        break;
}


// detect the csv scheme form year and dataset type
$a = "amministrazione";
$m = "missione";
$p = "programma";
$az = "azione";
$c = "capitolo";
$b = "fact";
if ($bdapDatasetYear >= 2017) {
    $match = array(
        $a => 2,
        $m => 13,
        $p => 15,
        $az => 19,
        $c => 6
    );
    switch ($bdapDatasetType) {
        case "lbf":
            $match[$b]=20;
            break;
            
        case "rnd":
            $match[$b]=35;
            break;
            
        case "dlb":
            $match[$b]=52;
            ;
            break;
    }
} else {
    $match = array(
        $a => 2,
        $m => 14,
        $p => 16,
        $az => 18,
        $c => 6
    );
    switch ($bdapDatasetType) {
        case "lbf":
            $match[$b]=21;
            break;
            
        case "rnd":
            $match[$b]=36;
            break;
            
        default:
            die( "$bdapDatasetType not recognized for year $bdapDatasetYear");
            break;
    }
}



/**
 * parse std input and generate rdf triples in turtle format
 */


$taxonomyUri = "resource:{$bdapDatasetId}_schema";

//PREFIXES
echo "@prefix fr: <http://linkeddata.center/botk-fr/v1#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> . 
@prefix skos:     <http://www.w3.org/2004/02/skos/core#> . 
@prefix dcat:      <http://www.w3.org/ns/dcat#> . 
@prefix dct:      <http://purl.org/dc/terms/> . 
@prefix foaf:     <http://xmlns.com/foaf/0.1/> . 
@prefix interval: <http://reference.data.gov.uk/def/intervals/> . 
@prefix qb:       <http://purl.org/linked-data/cube#> . 
@prefix rdfs:   <http://www.w3.org/2000/01/rdf-schema#> . 
@prefix sdmx-attribute:	<http://purl.org/linked-data/sdmx/2009/attribute#> .
@prefix resource: <http://mef.linkeddata.cloud/resource/> .

resource:$bdapDatasetId a fr:FinancialReport;
    dcat:theme resource:{$bdapDatasetType}_theme ;
    fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/{$bdapDatasetYear}-01-01T00:00:00/P1Y> ;  
    sdmx-attribute:unitMeasure <http://publications.europa.eu/resource/authority/currency/EUR> 
.
$taxonomyUri a skos:ConceptScheme;
    dct:source resource:$bdapDatasetId ;
    dct:title \"Tassonomia capitoli di spesa per amministrazioni\"@it;
    dct:description \"Tassonomia per amministrazioni>missioni>programmi>azioni>capitoli relative all'esercizio {$bdapDatasetYear}\"@it
.
";


//skips header
fgets(STDIN);
$recordNum=0;
while ($rawdata = fgetcsv(STDIN, 2048, ';')) {
    $recordNum++;
    $esercizio = $rawdata[0];
    $amministrazione = $rawdata[$match[$a]];
    $missione = $rawdata[$match[$m]];
    $programma = $rawdata[$match[$p]];
    $azione = $rawdata[$match[$az]]; 
    $capitolo = $rawdata[$match[$c]-1] . " - " . $rawdata[$match[$c]];
    $budget = $rawdata[$match[$b]];

    //Codes then used in notation and uri construction
    $a_code =Helper::getUri($amministrazione);
    $m_code =Helper::getUri($amministrazione . $missione);
    $p_code =Helper::getUri($amministrazione . $missione . $programma);
    $az_code =Helper::getUri($amministrazione . $missione . $programma . $azione);
    $c_code = Helper::getUri($amministrazione . $missione . $programma . $azione . $capitolo);
    
    //Uris
    $a_uri = "resource:{$bdapDatasetId}_{$a}_${a_code}";
    $m_uri = "resource:{$bdapDatasetId}_{$m}_${m_code}";
    $p_uri = "resource:{$bdapDatasetId}_{$p}_${p_code}";
    $az_uri = "resource:{$bdapDatasetId}_{$az}_${az_code}";
    $c_uri = "resource:{$bdapDatasetId}_{$c}_${c_code}";
    $b_uri = "resource:{$bdapDatasetId}_{$b}_" . $recordNum;

    //AMMINISTRAZIONE
    printSingleLevel($a_uri, $a_code, $amministrazione);
    //MISSIONE
    printSingleLevel($m_uri, $m_code, $missione, $a_uri);
    //PROGRAMMA
    printSingleLevel($p_uri, $p_code, $programma, $m_uri);
    //AZIONE
    printSingleLevel($az_uri, $az_code, $azione, $p_uri); 
    //CAPITOLO
    printSingleLevel($c_uri, $c_code, $capitolo, $az_uri);
    //BUDGET
    printBudget($b_uri, $c_uri, $budget);
    //TOP CONCEPT
    printf("\n%s skos:hasTopConcept %s .\n", $taxonomyUri, $a_uri);
}

function printBudget($uri, $subjectUri, $value){
    $bdapDatasetId = $GLOBALS['bdapDatasetId'];
    printf("%s a fr:Fact;
        fr:concept %s ;
        qb:dataSet resource:%s;
        fr:amount %.2F ."
        ,$uri, $subjectUri, $bdapDatasetId, $value);
}

function printSingleLevel($uri, $notation, $label, $broaderUri = null){
    $taxonomyUri = $GLOBALS['taxonomyUri'];
    $prefLabel = Helper::FILTER_SANITIZE_TURTLE_STRING(Helper::getAltLabel($label));
    printf('%s a skos:Concept ;
        skos:notation "%s" ;
        skos:prefLabel "%s"@it ;
        skos:inScheme %s',
        $uri, $notation, $prefLabel, $taxonomyUri);
    
    if(!empty($broaderUri)){
        printf(";\n\tskos:broader %s", $broaderUri);
    }
    printf(".\n");
}


?>