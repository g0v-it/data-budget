#!/usr/bin/env php
<?php
/**
 * Gateway per leggere i metadata di un report su bdap usando le api CKAN v1
 * vedi https://bdap-opendata.mef.gov.it/content/api
 **/
include "Helper.php";

$data = json_decode(stream_get_contents(STDIN));
$bdapDatasetId = $data->name;
$title=Helper::FILTER_SANITIZE_TURTLE_STRING($data->title);
$description=Helper::FILTER_SANITIZE_TURTLE_STRING($data->notes);
$accessUrl = str_replace('rgspod','bdap-opendata.mef.gov.it', $data->ckan_url);
$downloadUrl = "https://bdap-opendata.mef.gov.it/SpodCkanApi/api/1/rest/dataset/{$bdapDatasetId}.csv";

// do some cosmetics to title and description..
$title = preg_replace('/Bilancio.*/i','Bilancio: capitoli di spesa',$title);
$description =preg_replace('/ - Atto dovuto in formato elaborabile.*/','.',$description);

echo "@prefix fr: <http://linkeddata.center/botk-fr/v1#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> . 
@prefix dcat:      <http://www.w3.org/ns/dcat#> . 
@prefix dct:      <http://purl.org/dc/terms/> . 
@prefix foaf:     <http://xmlns.com/foaf/0.1/> . 
@prefix resource: <http://mef.linkeddata.cloud/resource/> .

resource:{$bdapDatasetId} a dcat:Dataset;
    dcat:landingPage <$accessUrl> ;
    dct:identifier \"{$bdapDatasetId}\" ;
    dct:publisher resource:MEF ;
    dct:created \"{$data->metadata_created}\"^^xsd:date ;
    dct:modified \"{$data->metadata_modified}\"^^xsd:date ;
    dct:title \"{$title}\"@it ;
    dct:description \"{$description}\"@it ;
    dcat:distribution resource:{$bdapDatasetId}_csv
.

resource:{$bdapDatasetId}_csv a dcat:Distribution ;
    dcat:license <http://creativecommons.org/licenses/by/3.0>;
    dcat:accessUrl <$accessUrl> ;
    dcat:downloadUrl <$downloadUrl> ;
    dct:format \"text/csv\"
.
";
