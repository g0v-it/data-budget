<?php
/**
 * Gateway per leggere i nomi preferenziali delle azioni da csv pubblicato in 
 * https://docs.google.com/spreadsheets/d/e/2PACX-1vTs9jWocG_xYjgp4JmKcH6o_7piJ9b4t--c-kx_xf6Erkp_-ad-4Fj0MRY2Eyd0AA9-LZ94pTwDv4na/pub?output=csv
 * src. https://docs.google.com/spreadsheets/d/1JpmdYjNkc-Yr4JlQdFhn0jgcJ0tuMZZ5Y9IWmc57BTA/edit?usp=sharing
 **/

include "Helper.php";

echo "@prefix : <http://data.budget.g0v.it/g0v-ap-api/v1#> .\n";

//skips header
fgets(STDIN);
while ($rawdata = fgetcsv(STDIN, 2048, ',')) {
    $url = $rawdata[1];
    $shortName = Helper::FILTER_SANITIZE_TURTLE_STRING($rawdata[4]);
    if($url && $shortName ){
        echo "<$url> :name \"$shortName\"@it .\n";
    }
}