![copernicani](doc/copernicani-logo.png)

# g0v data-budget

A *Smart Data Management Platform* to feed the http://budget.g0v.it/ web applications.

The plaform is built around a knowledge graph that contains informations about
some  Italian Government budget and financial reports:

- "legge di bilancio"
- "disegno di legge di bilancio"
- "consuntivo di bilancio"

The platform extracts and links main financial facts from the official open data provided by the "Ministero dell'Economia e Finance" for 2014-2019 years . 

The knowledge graph is compliant with RDF and Semantic Web Specification.

Applications can access the knowledge graph through a SPARQL interface.
An example of  RESTfull APIs is also provided as part of the platform. The
provided APIS integrates with http://budget.g0v.it/ application (source available at https://github.com/g0v-it/web-budget) 

**Reference implementations:**

- **SPARQL endpoint**: https://data.budget.g0v.it/sparql
- **Linked Data browser**: http://data.budget.g0v.it/welcome 
- **API endpoint**: https://data.budget.g0v.it/ldp
- **Example of a data consumer**: https://budget.g0v.it/

## Development

The project contains the two "core" logical components:

- **sdaas** (smart data as a service):  the data management platform core providing a RDF store, a [SPARQL endpoint](https://www.w3.org/TR/sparql11-overview), a data ingestion engine, a set of gateways to transform raw data in linked data and a build script that populates the RDF store. See files and docs in [sdaas directory](sdaas)
- a set of **apis** that query the SPARQL endpoint and produce json data with a schema suitable to be used with the BubbleGraph Component. See files and docs in [apis directory](apis)

Beside these, two additional optional components may be needed to complete a real production system:

- **LODMAP** server: a linked data browser to deferencing URIS and navigate the RDF store;
- a **router** that provides a single acces point to all services with firewall, caching and ssl features.

This picture shows the components interactions:

![architecture](doc/architecture.png)


To deploy the platform deploy a stack of some services is required:

![stack](doc/stack.png)

The platform is shipped with a [Docker](https://docker.com) setup that makes it easy 
to get a containerized development environment up and running. 
If you do not already have Docker on your computer, 
[it's the right time to install it](https://docs.docker.com/install/).

To start all services using [docker Compose](https://docs.docker.com/compose/) type: 

```
docker-compose build
docker-compose up -d
```

This starts locally the following services:


| Name        | Description                                                   | Port 
| ----------- | ------------------------------------------------------------- | ------- 
| sdaas       | a server that manages the datastore and the ingestion engine  | 29321    
| api         | a server that manages the web-budget api                      | 29322 

Try http://localhost:29321/sdaas to access blazegraph workbench
Try http://localhost:29322/ to test api endpoint

The first time you start the containers, Docker downloads and builds images for you. It will take some time, but don't worry
this is done only once. Starting servers will then be lightning fast.



To shudown the platform type: 

```
docker-compose down
```


## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://copernicani.slack.com/) general room. Make sure to mention **@enrico** so he is notified


## Credits

- the Smart Data Management Platform was developed by [LinkedData.Center](http://LinkedData.Center/)
- the [g0v fr-ap application profile ](https://github.com/g0v-it/ontologies/tree/master/fr-ap) and the  [LODMAP Bubble Graph Ontology](https://github.com/linkeddatacenter/LODMAP-ontologies/tree/master/BGO) was developed by Enrico Fagnoni @ LinkedData.Center
- API server was developed by [Yassine Ouahidi](https://github.com/YassineOuahidi)  @ LinkedData.Center and DataChef.Cloud
- data extracted from [Open BDAP portal](https://bdap-opendata.mef.gov.it/) with [CC-BY](http://creativecommons.org/licenses/by/3.0) license
- the RDF datastore and the SPARQL endpoint is based on the [Blazegraph community edition](https://www.blazegraph.com/)

Thanks to all project contributors, to the [Copernicani community](https://copernicani.it/) and to the [g0v asia community](http://g0v.asia) for ideas and support.

The URI dereferencing platform is derived from the [LODView project](https://github.com/dvcama/LodView)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
