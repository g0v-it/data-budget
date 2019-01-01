# gateways

This directory contains the gateways that transform the raw data provided by the Italian government (BDAP catalog) into linked data compliant with g0v-ap ontology:

Gateways are simple stand-alone php7 scripts that read a csv stream row by row from STDIN and write RDF turtle  compliant with  [g0v financial report application profile](https://github.com/g0v-it/ontologies/tree/master/fr-ap):

Gateways are simple stand-alone php7 scripts that read a csv stream row by row from STDIN and 
write RDF turtle statements on STDOUT. statements on STDOUT. 

The bdap gateway requires as a mandatory parameters an id of a dataset define in https://bdap-opendata.mef.gov.it/SpodCkanApi/api/1/rest/dataset/ and requires a csv input

The bdap-meta gateway requires expect in input a json structure containing the metadata about a dataset according CKAN v1 api.


## stand alone gateways development and testing


Gateways can be tested stand alone just with any host providing php7; e.g.:

```
docker run --rm -ti -v $PWD/.:/app composer bash
php bdap.php spd_lbf_spe_elb_cap_01_2018 < tests/data/legge-di-bilancio.csv
php bdap.php spd_dlb_spe_elb_cap_01_2017 < tests/data/disegno-legge-di-bilancio.csv 
php bdap.php spd_rnd_spe_elb_cap_01_2017 < tests/data/rendiconto.csv 
php bdap.php spd_rnd_spe_elb_cap_01_2016 < tests/data/rendiconto-old.csv 
php programmi.php spd_lbf_spe_elb_cap_01_2018 < tests/data/programmi.csv 
php bdap_meta.php < tests/data/metadata.json 
php account_names.php < tests/data/account_names.csv 
```

The gateways must generate valid RDF statements in turtle (n3) or any other RDF serialization format. Check the gateway results using an online service like http://rdf-translator.appspot.com/
 
