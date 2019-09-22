# gateways

This directory contains the gateways that transform the raw data provided by the Italian government (BDAP catalog) and othr data providers into linked data compliant with the [g0v financial report application profile](https://github.com/g0v-it/ontologies/tree/master/fr-ap):

Gateways are simple stand-alone php7 scripts that read a csv stream row by row from STDIN and 
write RDF turtle statements to STDOUT. Following gateways are available:

- the **bdap** gateway requires as a mandatory parameters an id of a dataset define in https://bdap-opendata.mef.gov.it/SpodCkanApi/api/1/rest/dataset/
- The **bdap_meta** gateway requires in input a json structure containing the metadata about a dataset according CKAN v1 api.
- **programmi** gateway reads verbose program descriptions from data/descrizione_programmi.csv


## stand alone gateways development and testing


Gateways can be tested stand alone just with any host providing php7; e.g.:

```
docker run --rm -ti -v $PWD/.:/app php bash
cd /app
bdap.php spd_lbf_spe_elb_cap_01_2018 < tests/data/legge-di-bilancio.csv
bdap.php spd_dlb_spe_elb_cap_01_2017 < tests/data/disegno-legge-di-bilancio.csv 
bdap.php spd_rnd_spe_elb_cap_01_2017 < tests/data/rendiconto.csv 
bdap.php spd_rnd_spe_elb_cap_01_2016 < tests/data/rendiconto-old.csv 
programmi.php spd_lbf_spe_elb_cap_01_2018 < tests/data/programmi.csv 
bdap_meta.php < tests/data/metadata.json 
```

The gateways generate RDF statements serialized in turtle. Check the gateway results using an online service like http://rdf-translator.appspot.com/
 
