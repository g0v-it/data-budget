# g0v-data smart data management platform

Thi directory contains a lightweight implementation of a [Smart Data Management Platform](https://it.linkeddata.center/b/smart-data-platform/)(SDMP) conforming to [Knowledge Exchange Engine Schema](http://LinkedData.Center/kees)(KEES) specification

The SDMP is in charge to build and maintain adomain knowledge base running a workflow based on a sequence of four temporal phases called "windows“:
:
1. a startup  phase (**boot window**)  to initialize the knowledge base just with KEES description
2. a time slot for the population of the Knowledge Base and to link data (**learning window**)
3. a time slot for the data inference (**reasoning window**)
4. a time slot to access the Knowledge Base and answering to questions  (**teaching window**)



More or less the **learning window** is an ETL process that:
  
- **extracts** raw data from source datasets.
- **trasnsforms** data from a data model to another using customized gateway
- **loads** data in a persistent RDF store together with the meaning of data and provenance info.

The **reasoning window** computes axioms and creates inferences analyzing the learned knowledge base.

The **teasching window** allow the knowledge base to be queried.


sdaas uses a simple shell like script (build.sdaas) to define the whole KEES workflow.

## Development

The gov-it directory contains all data and components required by the sdaas platform

To create a knowledge base containing data about italian governmet budget you require docker:

```
docker build . -t kb
docker run -d -v g0v-it:/kees -p 9999:8080 --name kb kb
docker exec kb -f /kees/build.sdaas --reboot
```

Play with the blazegraph control panel pointing a browser to http://localhost:9999/bigdata.

Go to the Explore tab and type:

```
<http://data.budget.g0v.it/resource/knowledge_base>
```

## Credits and license

- the rdf datastore implementation is based on [Docker Blazegraph](https://github.com/lyrasis/docker-blazegraph)
- the sdaas platform is derived from [LinkedData.Center SDaas Product](https://it.linkeddata.center/p/sdaas) and licensed with CC-by-nd-nc by LinkedData.Center to g0v community
