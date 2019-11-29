# bdap gateways

This directory contains the gateways that transform the raw data provided by the Italian government (BDAP catalog) into linked data compliant with the [g0v financial report application profile for MEF data](../mef-ap) (mef-ap)


Gateways are simple stand-alone php7 scripts that read a csv stream row by row from STDIN and 
write RDF turtle statements to STDOUT. Following gateways are available:


- the **ckan-meta.php** gateway processess input a json structure containing the metadata about a dataset retrived from  CKAN v1 api.
- the **bdap.php** gateway requires as a mandatory parameters an id of a dataset defined in https://bdap-opendata.mef.gov.it/SpodCkanApi/api/1/rest/dataset/
- **programmi.php** gateway reads verbose program descriptions from data/descrizione_programmi.csv


Retrieve gateways dependencies using [Composer](http://getcomposer.org/):


```shell
docker run --rm -ti -v $PWD/.:/app composer install
docker run --rm -ti -v $PWD/.:/app composer update
```


Gateways can be tested stand alone just with any host providing php7+; e.g.:

```
docker run --rm -ti -v $PWD/.:/app -w /app php bash
function mef_test { cat tests/$2 | ./$1 $2 | curl -f --data-urlencode content@- http://rdf-translator.appspot.com/convert/n3/n3/content ; }

mef_test ckan-meta.php ckan-meta.json
mef_test spd_dlb_spe_elb_pig.php spd_dlb_spe_elb_pig_01_2020.csv 
mef_test spd_lbf_spe_elb_pig.php spd_lbf_spe_elb_pig_01_2019.csv
mef_test spd_pas_spe_elb_pig.php spd_pas_spe_elb_pig_01_2019.csv 
mef_test spd_rnd_spe_elb_pig.php spd_rnd_spe_elb_pig_01_2018.csv
mef_test spd_dlb_not_azi.php spd_dlb_not_azi_azioni_01_2020.csv

exit
```

The gateways generate RDF statements serialized in turtle. Check the gateway results using an online service like http://rdf-translator.appspot.com/


The `SD_LEARN` SDaaS command automates the data trasformation process. 
 