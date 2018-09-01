<?php
/**
 * Gateway per trasformare  le leggi di bilancio emesse dal 2018 su BDAP
 * da  formato csv a rdf g0v-ap
 **/
//dataset-afcec986-9a57-4eae-abfa-543a4f043c5e
if(!isset($argv[1])){
    echo "You need to provide the dataset id";
    die();
}

require_once('Helper.php');

$bdapDatasetId = $argv[1];
$bdapDatasetUri = "http://data.budget.g0v.it/resource/$bdapDatasetId";
$taxonomyUri = "<$bdapDatasetUri#taxonomy>";
//ARRAY FOR VALUES
$a = "amministrazione"; $m = "missione"; $p = "programma"; $az = "azione"; $c = "capitolo"; $b = "budget";
$match = array(
    $a => 2,
    $m => 13,
    $p => 15,
    $az => 19,
    $c => 6,
    $b => 20
);
//PREFIXES
printf('@prefix g0v: <http://data.budget.g0v.it/g0v-budget/v1#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> . 
@prefix skos:     <http://www.w3.org/2004/02/skos/core#> . 
@prefix dcat:      <http://www.w3.org/ns/dcat#> . 
@prefix dct:      <http://purl.org/dc/terms/> . 
@prefix foaf:     <http://xmlns.com/foaf/0.1/> . 
@prefix interval: <http://reference.data.gov.uk/def/intervals/> . 
@prefix qb:       <http://purl.org/linked-data/cube#> . 
@prefix rdfs:   <http://www.w3.org/2000/01/rdf-schema#> .
');

//TAXONOMY
echo"
$taxonomyUri a g0v:BudgetTaxonomy, skos:ConceptScheme;
    dct:src <$bdapDatasetUri> ; 
    dct:title \"Tassonomia capitoli di spesa per amministrazioni\"@it;
    dct:description \"Tassonomia amministrazioni/missioni/programmi/azioni/capitoli ricavata dalla legge di bilancio\"@it
    ."
;
echo "\n";
//skips header
fgets(STDIN);
while ($rawdata = fgetcsv(STDIN, 2048, ';')) {
    $amministrazione = $rawdata[$match[$a]];
    $missione = $rawdata[$match[$m]];
    $programma = $rawdata[$match[$p]];
    $azione = $rawdata[$match[$az]]; 
    $capitolo = $rawdata[$match[$c]];
    $budget = $rawdata[$match[$b]];

    //Codes then used in notation and uri construction
    $a_code =Helper::getUri($amministrazione);
    $m_code =Helper::getUri($amministrazione . $missione);
    $p_code =Helper::getUri($amministrazione . $missione . $programma);
    $az_code =Helper::getUri($amministrazione . $missione . $programma . $azione);
    $c_code = Helper::getUri($amministrazione . $missione . $programma . $azione . $capitolo);
    
    //Uris
    $a_uri = "<$bdapDatasetUri#{$a}_$a_code>";
    $m_uri = "<$bdapDatasetUri#{$m}_$m_code>";
    $p_uri = "<$bdapDatasetUri#{$p}_$p_code>";
    $az_uri = "<$bdapDatasetUri#{$az}_$az_code>";
    $c_uri = "<$bdapDatasetUri#{$c}_$c_code>";
    $b_uri = "<$bdapDatasetUri#{$b}_" . uniqid() .">";

    //AMMINISTRAZIONE
    printSingleLevel($a_uri, $a_code, Helper::FILTER_SANITIZE_TURTLE_STRING($amministrazione), Helper::FILTER_SANITIZE_TURTLE_STRING(Helper::getAltLabel($amministrazione)), "");
    //MISSIONE
    printSingleLevel($m_uri, $m_code, Helper::FILTER_SANITIZE_TURTLE_STRING($missione) , Helper::FILTER_SANITIZE_TURTLE_STRING($missione), $a_uri);
    //PROGRAMMA
    printSingleLevel($p_uri, $p_code, Helper::FILTER_SANITIZE_TURTLE_STRING($programma) , Helper::FILTER_SANITIZE_TURTLE_STRING($programma), $m_uri);
    //AZIONE
    printSingleLevel($az_uri, $az_code, Helper::FILTER_SANITIZE_TURTLE_STRING($azione), Helper::FILTER_SANITIZE_TURTLE_STRING($azione), $p_uri); 
    //CAPITOLO
    printSingleLevel($c_uri, $c_code, Helper::FILTER_SANITIZE_TURTLE_STRING($capitolo), Helper::FILTER_SANITIZE_TURTLE_STRING($capitolo), $az_uri);
    //BUDGET
    printBudget($b_uri, $c_uri, Helper::FILTER_SANITIZE_TURTLE_STRING($capitolo), Helper::FILTER_SANITIZE_TURTLE_STRING($budget));
    //TOP CONCEPT
    printf("\n%s skos:hasTopConcept %s .\n", $taxonomyUri, $a_uri);
}

function printBudget($uri, $subjectUri, $subjectLabel, $value){
    $bdapDatasetUri = $GLOBALS['bdapDatasetUri'];
    printf("%s a g0v:Budget, qb:Observation ;
        g0v:subject %s ;
        qb:dataSet  <%s>;
        rdfs:comment \"Valore riferito al capitolo di spesa %s\"@it ;
        g0v:obsValue %s ."
        ,$uri, $subjectUri, $bdapDatasetUri, $subjectLabel,$value);
}

function printSingleLevel($uri, $notation, $prefLabel, $altLabel, $broaderUri){
    $taxonomyUri = $GLOBALS['taxonomyUri'];
    printf('%s a skos:Concept ;
        skos:notation "%s" ;
        skos:prefLabel "%s"@it ;
        skos:altLabel "%s"@it ;
        skos:inScheme %s',
        $uri, $notation, $prefLabel, $altLabel, $taxonomyUri);
    
    if(!empty($broaderUri)){
        printf(";\n\tskos:broader %s", $broaderUri);
    }
    printf(".\n");
}


?>