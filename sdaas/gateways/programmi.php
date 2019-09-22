#!/usr/bin/env php
<?php
/**
 * Gateway per trasformare il file data/missioni-programmi.csv in rdf g0v-ap
 * Assunzioni sul formato del file:
 *  in formato uft8 
 *  comma delimited
 *  prima linea è l'header da ignorare
 *  se missione o amministrazione sono vuoti si ereditano dalla riga precedente
 *  i primi 4 caratteri di missione e programma vanno eliminati
 *  
 **/
isset($argv[1]) || die("Gateway usage: bdap-programmi <id schema anno di riferimento (es. spd_lbf_spe_elb_cap_01_2018_schema>");
$datasetId = $argv[1];


include "Helper.php";

//PREFIXES
echo "@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix dct:      <http://purl.org/dc/terms/> . 
@prefix resource: <http://mef.linkeddata.cloud/resource/> .

resource:$datasetId
    dct:source resource:Missioni-programmi.csv ;
    rdfs:comment \"Integrato con ultima descrizione programmi disponibile.\"@it .
";


$missione = $programma = '';
fgets(STDIN);
while ($rawdata = fgetcsv(STDIN, 2048)) {
    $missione = $rawdata[0]?substr($rawdata[0], 4):$missione;
    $programma = $rawdata[1]?substr($rawdata[1], 4):$programma;
    $amministrazione = $rawdata[2];
    $descrizione = Helper::FILTER_SANITIZE_TURTLE_STRING($rawdata[3]); 

    //Codes then used in notation and uri construction
    $p_code =Helper::getUri($amministrazione . $missione . $programma);
    echo "resource:{$datasetId}_programma_${p_code} rdfs:comment \"$descrizione\"@it .\n";
}
