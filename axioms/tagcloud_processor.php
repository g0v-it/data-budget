#!/usr/bin/env php
#
# Copyright (c) 2019 LinkedData.Center. All rights reserved.
#
<?php

/**
 * escape double quotes, backslash and new line in RDF turtle string
 * empty allowed
 */
function FILTER_SANITIZE_TURTLE_STRING($value)
{
    $value = preg_replace('/\r?\n|\r/', ' ', $value);
    $value = preg_replace('/\\\\/', '\\\\\\\\', $value);    // escape backslash
    $value = preg_replace('/"/', '\\"', $value);        // escape double quote
    
    return $value?:null;
}


function extractTags ($string, &$tags )
{
    // non considerare articoli e preposizioni
    static $taboo = [
        'gli', 'una', 'con', 'per', 'tra', 'fra',
        'davanti', 'dietro','dopo','fuori','lontano','lungo','mediante','prima','sopra','sotto',
        'del','dello','della','dell','dei','degli','delle','allo','alla','agli','alle','all','dal','dallo','dalla','dall','dai',
        'dagli','dalle','nel','nello','nella','nell','nei','negli','nelle','sul','sullo','sulla','sull','sui','sugli','sulle',
        'attività', 'attivita', 'programma', 'missione'
    ];
      
    $delimeter=" \n\t'.-_:!?\"=()/\|&*[]{}";
    $string = strtolower($string);
    $tok = strtok($string, $delimeter);
    
    while ($tok !== false) {
        if(isset($tags[$tok])){
            $tags[$tok]++;
        } else {
            if(strlen($tok)>2 && !in_array($tok,$taboo)) {
                $tags[$tok]=1;
            }
        }
        $tok = strtok($delimeter);
    }
}


$tags=[];
while ($rawdata = fgetcsv(STDIN)) {
    extractTags($rawdata[0] , $tags );
}

echo "
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#> 
PREFIX : <http://mef.linkeddata.cloud/resource/bgo_> 
INSERT DATA {
:tag_cloud bgo:hasTag
";

arsort($tags);
$maxValue=max(max($tags),1);
foreach( array_slice($tags,0,50) as $label=>$number ) {
    $label=FILTER_SANITIZE_TURTLE_STRING($label);
    $tagWeight = round( $number/$maxValue, 2);
    echo "\t[ a bgo:Tag; bgo:label \"$label\"; bgo:tagWeight $tagWeight ] , \n";
}
echo "\t[] }\n";