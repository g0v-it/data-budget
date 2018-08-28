# g0v-data sdaas

This directory contains the engine to build a knowledge base about the Italian government budget. It is realized as a lightweight implementation of a  [Smart Data Management Platform](https://it.linkeddata.center/b/smart-data-platform/) derived from the [LinkeData.Center SDaaS product](https://it.linkeddata.center//p/sdaas).

The knowledge base is consistent with the [Knowledge Exchange Engine Schema](http://LinkedData.Center/kees)(KEES) specifications. 


The data life cycle is based on a workflow that is based on a sequence of four temporal phases called *windows*:

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

Normally the knowledge base lives in the **teaching window** ready to answer questions.

## Test platform

```
docker build -t sdaas .
docker run -d -p 9999:8080 -v $PWD/sdaas-bin:/usr/local/bin/sdaas -v $PWD/webapps/root:/var/lib/jetty/webapps/root --name sdaas sdaas
docker exec -ti sdaas sdaas -f build.sdaas --reboot
```

Navigate the knowledge base pointing a browser to http://localhost:9999/:


### Development and debugging

Manually downloads copy all required raw data that are not directly accessible for web in the g0v-kb/datalake/download.

Cache other linked open data editing and running the script `refresh.sh` in the *g0v-it/datalake/lod* directory.

Connect to the container to debug build script commands


## Credits and license

- the dockerfile was inspired from [Docker Blazegraph](https://github.com/lyrasis/docker-blazegraph)
- the sdaas platform is derived from [LinkedData.Center SDaas Product](https://it.linkeddata.center/p/sdaas) and licensed with CC-by-nd-nc by LinkedData.Center to g0v community
