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


/**
 * Extract  $bdapDatasetYear and bdapDatasetType from $bdapDatasetId
 */
preg_match("/^spd_(lbf|rnd|dlb)_spe_elb_cap_01_(20[0-9]{2})$/", $bdapDatasetId, $matches) || die("Unreconized dataset id $bdapDatasetId");
$bdapDatasetYear= intval($matches[2]);
$bdapDatasetType= $matches[1];


/**
 * Detect $mefReportClass
 */
switch ($bdapDatasetType) {
    case "dlb":
        $mefReportClass="DisegnoLeggeDiBilancio";
        $reportTitle= "Disegno di Legge di Bilancio $bdapDatasetYear" ;
        break;
    case "lbf":
        $mefReportClass="LeggeDiBilancio";
        $reportTitle= "Legge di Bilancio $bdapDatasetYear" ;
        break;
    case "rnd":
        $mefReportClass="RendicontoFinanziario";
        $reportTitle= "Rendiconto spese anno $bdapDatasetYear" ;
        break;
}

/**
 * labels
 */ 
$a = "Amministrazione";
$m = "Missione";
$p = "Programma";
$az = "Azione";
$c = "Capitolo di spesa";
$b = "fact";

/**
 * Detect the csv scheme form year and dataset type
 */
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
echo "# 
# Generated by dbap.php gateway from https://g0v-it.github.io/data-budget/
# according with fr-ap-mef  profile (see https://g0v-it.github.io/data-budget/fr-ap-mef)
@prefix fr: <http://linkeddata.center/botk-fr/v1#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> . 
@prefix skos: <http://www.w3.org/2004/02/skos/core#> . 
@prefix dcat: <http://www.w3.org/ns/dcat#> . 
@prefix dct: <http://purl.org/dc/terms/> . 
@prefix foaf: <http://xmlns.com/foaf/0.1/> . 
@prefix interval: <http://reference.data.gov.uk/def/intervals/> . 
@prefix qb: <http://purl.org/linked-data/cube#> . 
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> . 
@prefix sdmx-attribute:	<http://purl.org/linked-data/sdmx/2009/attribute#> .
@prefix mef: <http://w3id.org/g0v/it/mef#> .
@prefix bgo: <http://linkeddata.center/lodmap-bgo/v1#> .
@prefix resource: <http://mef.linkeddata.cloud/resource/> .
@prefix report: <http://mef.linkeddata.cloud/resource/{$bdapDatasetId}_> .


<> foaf:primaryTopic resource:$bdapDatasetId .

resource:$bdapDatasetId a mef:$mefReportClass ;
    fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/{$bdapDatasetYear}-01-01T00:00:00/P1Y> ;  
    sdmx-attribute:unitMeasure <http://publications.europa.eu/resource/authority/currency/EUR> ;
.
";

//skips header
fgets(STDIN);$recordNum=0;
$last_a=$last_m=$last_p=$last_az='';

while ($rawdata = fgetcsv(STDIN, 2048, ';')) {
    $recordNum++;
    
    // descriptions
    $amministrazione = Helper::FILTER_SANITIZE_TURTLE_STRING($rawdata[$match[$a]]);
    $missione = Helper::FILTER_SANITIZE_TURTLE_STRING($rawdata[$match[$m]]);
    $programma = Helper::FILTER_SANITIZE_TURTLE_STRING($rawdata[$match[$p]]);
    $azione = Helper::FILTER_SANITIZE_TURTLE_STRING($rawdata[$match[$az]]); 
    $capitolo = Helper::FILTER_SANITIZE_TURTLE_STRING($rawdata[$match[$c]]);
    
    $amount = Helper::NORMALIZE_AMOUNT($rawdata[$match[$b]]);

    // numercc id for notation
    $a_id  = crc32($rawdata[$match[$a]]);
    $m_id  = $rawdata[$match[$m]-1];
    $p_id  = $rawdata[$match[$p]-1];
    $az_id = $rawdata[$match[$az]-1] ;
    $c_id  = $rawdata[$match[$c]-1] ;
    
    // skos notations
    $a_code  = crc32($a_id);
    $m_code  = $a_code . '-' . $m_id;
    $p_code  = $m_code . '-' . $p_id;
    $az_code = $p_code . '-' . $az_id ;
    $c_code  = $az_code . '-' . $c_id ;
       
    //Uris
    $a_uri  = "report:$a_code";
    $m_uri  = "report:$m_code";
    $p_uri  = "report:$p_code";
    $az_uri = "report:$az_code"; 
    $c_uri  = "report:fact$recordNum";

 
    echo "
#### record number $recordNum
$c_uri a mef:Capitolo;
    qb:dataSet resource:$bdapDatasetId ;
    fr:isPartOf $az_uri ;
    fr:amount $amount ;
    skos:notation \"$c_code\" ;
    skos:definition \"$capitolo\"@it .
" ;

if ($last_az != $az_uri) { $last_az = $az_uri; echo "
$az_uri
    fr:isPartOf $p_uri ;
    skos:notation \"$az_code\" ;
    skos:definition \"$azione\"@it .
";}

if ($last_p != $p_uri) { $last_p = $p_uri; echo "
$p_uri 
    fr:isPartOf $m_uri ;
    skos:notation \"$p_code\" ;
    skos:definition \"$programma\"@it .
";}

if ($last_m != $m_uri) { $last_m = $m_uri; echo "
$m_uri 
    fr:isPartOf $a_uri ;
    skos:notation \"$m_code\" ;
    skos:definition \"$missione\"@it .
";}

if ($last_a != $a_uri) { $last_a = $a_uri; echo "
$a_uri 
    skos:notation \"$a_code\" ;
    skos:definition \"$amministrazione\"@it .
";}
    
}
