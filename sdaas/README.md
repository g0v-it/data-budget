# data.budget.gov.it knowledge Base (smart data as a service)

This project contains all needed for setting up and update a knowledge base ready to be used by the budget.g0v.it APIs.

The knowledge base is built around the [g0v-ap-api ontology](http://data.budget.g0v.it/g0v-ap-api/v1)
that is a local specialization of the [g0v-ap ontology](http://data.budget.g0v.it/g0v-ap/v1) designed
to provide a data model that is easy to query for the data-budget apis component in budget.gov.it project

The data ingestion process is managed by the SDaS platform (community edition).


## updating the knowledge base

knowledge base build process require:

- to edit local data, editing the files in the *data* directory
- to develop the *gateways* for transforming web resources in linked data. See [gateways doc.](gateways/README.md)
- to write *axioms* and rules to generate new data. See [axioms doc.](axioms/README.md)
- to edit a *build script* for sdaas platform that drives the data ingestion process.
- to run sdaas

### debugging the build script

the test of the build script requires the sdaas-ce container.

```
docker run -d -p 9999:8080 -v $PWD/.:/workspace --name kb linkeddatacenter/sdaas-ce
docker exec -t kb sdaas --debug -f build.sdaas --reboot
```

logs info and debug traces will be created in .cache directory

Access the admin workbench pointing browser to http://localhost:9999/sdaas

 
### publishing  the knowledge base

You can pack data and services with :

```
docker build . -t sdaas
docker run -d -p 8889:8080 --name datastore sdaas
```

The resulting container will provide a readonly distribution of the whole knowlede base in a stand-alone graph database with sparql interface.


## Directory structure

- the **build.sdaas** file is a script for sdaas platform to populate the knowledge base from scratch. It requires sdaas platform community edition 2.0+
- the **axioms** directory contains rules to be processed during reasoning windows.
- the **data** directory contains local data files
- the **gateways** directory contains the code to transform raw data into linked data
- the **scripts** directory contains the code for local extensions to sdaas
- the **.cache** temporary directory that contains logs and debugging info. Not saved in repo.


## Credits and license


- the dockerfile reuse [Docker Blazegraph](https://github.com/lyrasis/docker-blazegraph)
- the sdaas platform is derived from [LinkedData.Center SDaas Product](https://it.linkeddata.center/p/sdaas) and licensed with CC-by-nd-nc by LinkedData.Center to g0v community
