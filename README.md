![copernicani](doc/copernicani-logo.png)

# g0v data-budget

A simple *Smart Data Management Platform* to feed the http://budget.g0v.it/ web application.


**Reference implementation:**

- **API endpoint**: https://data.budget.g0v.it/ldp
- **SPARQL endpoint**: https://data.budget.g0v.it/sparql
- **Linked Data browser**: http://data.budget.g0v.it/welcome 

## Development

The project contains the two "core" logical components:

- **sdaas** (smart data as a service):  the data management platform core providing a RDF store, a [SPARQL endpoint](https://www.w3.org/TR/sparql11-overview), a data ingestion engine, a set of gateways to transform raw data in linked data and a build script that populates the RDF store. See files and docs in [sdaas directory](sdaas)
- a set of **apis** that query the SPARQL endpoint and produce json data with a schema suitable to be used with the BubbleGraph Component. See files and docs in [apis directory](apis)

Beside these, two optional components may be needed to complete a production system:

- **LODMAP** server: a linked data browser to navigate the RDF store;
- a **router** that provides a single acces point to all other services with caching and ssl features.

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
this is done only once. Starting servers will then be lightning fast

To shudown the platform type: 

```
docker-compose down
```

### Production deployment hints

For production deployment a SSL reverse proxy server, with caching capability is strongly suggested. Here is a snippet of apache virtual host configuration for http://data.example.org/ that provides two public points:

- **/ldp/ *** that acts as a frontend to the api container 
- **/sparql** that acts as a frontend to the sparql service

```
<VirtualHost *:80>
    ServerName data.example.org
    ServerAdmin webmaster@example.org
    ProxyRequests Off
    ProxyVia Off
    ProxyPreserveHost On
    <Proxy *>
      AddDefaultCharset off
      Order deny,allow
      Allow from all
    </Proxy>
    ProxyPass /sparql http://docker.example.org:29321/sdaas/sparql
    ProxyPass /ldp/ http://docker.example.org:29322/
```

You could also run a service to manage uri dereferencing and linked data content negotiation. For instance the [LODVIEW](https://github.com/dvcama/LodView) application

```
docker run -d --name lodview  \
	-p 80:8080 \
      -e LODVIEW_URISPACE=http://data.example.org/resource/ \
      -e LODVIEW_URISPACEPREFIX=resource  \
      -e LODVIEW_SPARQLENDPOINT=https://data.example.org/sparql \
      -e LODVIEW_HOMEURL=https://example.org/ \
      -e LODVIEW_DATALICENSE=Contiene dati provenienti da Example.org. \
      -e LODMAP_HOME_TITLE=Welcome to example knowledge base \
      -e LODVIEW_PUBLICURLPREFIX=https://lodview.example.org/ \
      -e LODEVIEW_HEADERLOGO=https://data.example.org/logo.gif \
      -e LODVIEW_DATALICENSE=Dati estratti da Open Data" \
	linkeddatacenter/lodview 
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
