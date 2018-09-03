# budget.gov.it knowledge Base

This project contains the sddas configuration, data and gateways for setting up and update a knowledge base ready to teach the budget.g0v.it APIs.

The knowledge base is based around the [g0v-ap-api-v1 ontology](data/g0v-ap-api-v1.owl) that is a local specialization of [g0v-ap ontology](http://data.budget.g0v.it/g0v-ap/v1) designed to provide a data model that is easy to query for the data-budget apis component in budget.gov.it project.

It requires sdaas platforms.

**This directory must be mounted as /kees  on the sdaas engine host.** 

## Updating data

Edit files in the data directory

Manually downloads copy all required raw data that are not directly accessible for web in the *datalake/download* directory.

Cache other linked open data editing and running the script `refresh.sh` in the *datalake/lod* directory.

Install the sdaas platform to debug build.script

## stand alone gateways development and testing

Gateways can be tested stand alone just with any host providing php7; e.g.:

```
docker run -ti -v $PWD/gateways:/gateways thatsamguy/trusty-php71 bash
php gateways/bdap-legge-di-bilancio.php dstest1 < gateways/tests/data/legge-di-bilancio.csv 
php gateways/bdap-rendiconto.php dstest2 < gateways/tests/data/rendiconto.csv 
php gateways/bdap-rendiconto-special.php dstest3 < gateways/tests/data/rendiconto-special.csv 
```

Check the gateway results using an online service like http://rdf-translator.appspot.com/
 
## debugging build script

```
docker build -t sdaas ../sdaas
docker run -d -p 9999:8080 -v $PWD/.:/kees --name g0v-kb sdaas
docker exec -t g0v-kb sdaas --debug -f build.sdaas --reboot
```
logs info and debug traces will be created in .cache directory

## Directory structure

- the **build.sdaas** file is a script for sdaas platform to populate the knowledge base from scratch. It requires sdaas platform community edition 2.0+
- the **axioms** directory contains static rules to be processed during reasoning windows. 
- the **data** directory contains local data files
- the **datalake** this directory contains a mirror of data that are present outside the
- the **gateways** directory contains the code to transform raw data into linked data
- the **doc** directory directory contains various documentation files
- the **distrib** temporary created directory that contains the answers to questions. It is created by the teaching window during build process. Not saved in repo.
- the **.cache** temporary created directory that contains rules to be interpreted by reasoner and data distributions. Not saved in repo.
