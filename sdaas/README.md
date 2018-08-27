# g0v-data smart data management platform

This directory contains the configuration for the knowledge base about the Italian government budget data an a lightweight implementation of a  [Smart Data Management Platform](https://it.linkeddata.center/b/smart-data-platform/)(SDMP) derived from the  [LinkeData.Center SDaaS product](https://it.linkeddata.center//p/sdaas).

The knowledge base configuration is consistent with the [Knowledge Exchange Engine Schema](http://LinkedData.Center/kees)(KEES) specifications. 

The data management platform runs a workflow that is based on a sequence of four temporal phases called *windows*:

1. a startup  phase (**boot window**)  to initialize the knowledge base
2. a time slot for the population of the knowledge base and to link data (**learning window**)
3. a time slot for the compute inferences (**reasoning window**)
4. a time slot query the Knowledge Base (**teaching window**)


More or less the **learning window** is an ETL process that:
  
- **extracts** raw data from source datasets (locally or from web).
- **transforms** data from the variois source a data models to the RDF domain language profile customized using custom gateways
- **loads** data in a persistent RDF store together with dataprovenance info.

The **reasoning window** computes axioms and creates inferences analyzing the learned knowledge base.

A simple file (build.sdaas) defines the whole KEES workflow.

## Quickstart

To create a knowledge base containing data about italian governmet budget you require docker:

```
cd ..
docker-compose up -d sdaas
docker exec databudget_sdaas_1 sdaas -f build.sdaas --reboot
```

Navigate the knowledge base pointing a browser to http://localhost:9999/sdaas/#explore and typing the URI `urn:sddas:config:kb` :


See some examples in g0v-it/queries directory.

### Development and debugging

Manually downloads copy all required raw data that are not directly accessible for web in the g0v-it/datalake/download.

Cache other linked open data editing and running the script `refresh.sh` in the *g0v-it/datalake/lod* directory.

Connect to the container to debug build script commands

```
docker exec -ti exec databudget_sdaas_1 sdaas sdaas
SD_HELP
```
 

## Credits and license

- the dockerfile was inspired from [Docker Blazegraph](https://github.com/lyrasis/docker-blazegraph)
- the sdaas platform is derived from [LinkedData.Center SDaas Product](https://it.linkeddata.center/p/sdaas) and licensed with CC-by-nd-nc by LinkedData.Center to g0v community
