# budget.gov.it knowledge Base

This project contains the configuration, quality records and data gateways for setting up and update the budget.gov.it knowledge Base.

## Updating data

Edit files in the data directory

Manually downloads copy all required raw data that are not directly accessible for web in the *datalake/download* directory.

Cache other linked open data editing and running the script `refresh.sh` in the *datalake/lod* directory.

Install the sdaas platform to debug build.script

## testing gateways

```
docker run -ti -v $PWD/gateways:/gateways -v $PWD/tests:/tests thatsamguy/trusty-php71 bash
php gateways/bdap-legge-di-bilancio.php test1 < tests/data/test1.csv 
```

To test rdf result use an online service like http://rdf-translator.appspot.com/
 
## debugging build script

```
docker build -t sdaas ../sdaas
docker run -d -p 9999:8080 -v $PWD/.:/kees --name g0v-kb sdaas
docker exec -ti g0v-kb bash
sdaas -f build.sdaas --reboot
```


## Directory structure

- the **build.sdaas** sdaas script to populate the knowledge base from scratch. It requires sdaas platform community edition 2.0+
- the **axioms** directory contains static rules to be processed during reasoning windows. 
- the **.cache** temporary created directory that contains rules to be interpreted by reasoner and data distributions. 
- the **data** directory contains local data files
- the **distrib** temporary created directory that contains the answers to questions. It is created by the teaching window during build process.
- the **doc** directory directory contains various documentation files
- the **gateways** directory contains the code to transform raw data into linked data
- the **tests** directory contains data samples and test sparql queries
- the **data_lake** this directory contains a mirror of data that are present outside the project. 
