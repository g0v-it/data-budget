#!/usr/bin/env php
<?php
/**
 * Gateway per leggere i metadata di un report su bdap usando le api CKAN v1
 * vedi https://bdap-opendata.mef.gov.it/content/api
 **/
require_once __DIR__.'/vendor/autoload.php';

$data = json_decode(stream_get_contents(STDIN));
$bdapDatasetId = 'bdap_' . $data->name;
$title = BOTK\Filters::FILTER_SANITIZE_TURTLE_STRING($data->title);
$description = BOTK\Filters::FILTER_SANITIZE_TURTLE_STRING($data->notes);
$accessUrl = MEF\BDAP::titleToAccessUrl($data->title);

echo "@prefix fr: <http://linkeddata.center/botk-fr/v1#> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> . 
@prefix dcat:      <http://www.w3.org/ns/dcat#> . 
@prefix dct:      <http://purl.org/dc/terms/> . 
@prefix foaf:     <http://xmlns.com/foaf/0.1/> . 
@prefix resource: <http://mef.linkeddata.cloud/resource/> .

resource:openBDAP dcat:dataset resource:{$bdapDatasetId} .

resource:{$bdapDatasetId} a dcat:Dataset;
    dcat:landingPage <$accessUrl> ;
    dct:identifier \"{$bdapDatasetId}\" ;
    dct:publisher resource:MEF ;
    dct:created \"{$data->metadata_created}\"^^xsd:date ;
    dct:modified \"{$data->metadata_modified}\"^^xsd:date ;
    dct:title \"{$title}\"@it ;
    dct:description \"{$description}\"@it 
.


";

foreach ($data->resources as $distribution) {
    $distribution_id = "{$bdapDatasetId}_{$distribution->id}";
    $downloadUrl =  str_replace(' ','%20',$distribution->url);
    $format =  $distribution->mimetype;
    $dTitle = ($format=='text/csv')?'Dati relativi a ':'Descrizione dati ';
    $dTitle .= $title ;
    echo "
resource:{$bdapDatasetId} dcat:distribution resource:$distribution_id .
resource:$distribution_id a dcat:Distribution ;
    dct:title \"$dTitle\"@it ;
    dct:identifier \"$distribution_id\" ;
    dcat:license <http://creativecommons.org/licenses/by/3.0>;
    dcat:downloadUrl <$downloadUrl> ;
    dct:format \"$format\"
.

";
}

// parse $bdapDatasetId and create links with mef:Budget Object

if (\MEF\BDAP::parseBdapId( $bdapDatasetId, $matches)) {
    echo "resource:{$matches['budgetId']} dct:source resource:{$bdapDatasetId} .";
} else {
    throw new \Exception("unexpected report name $bdapDatasetId");
}



