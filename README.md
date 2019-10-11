![copernicani](doc/copernicani-logo.png)

# g0v data-budget

data-budget is a *Smart Data Management Platform* developed to feed the http://budget.g0v.it/ web application.

The platform builds a knowledge base about the Italian Government budget considering :

- "disegno di legge di bilancio" (budget proposal)
- "legge di bilancio" (the actual budget)
- "consuntivo di bilancio" (consolidated balance)

The platform extracts main financial facts from the official [open data portal](https://bdap-opendata.mef.gov.it) provided 
by the "Ministero dell'Economia e Finance" and produces a knowledge graph according with the 
[fr-ap-mef profile](fr-ap-mef)

Any application access such knowledge through a SPARQL interface as required by the Semantic Web standards.

The data-budget is built using the [Smart Data as a Service platform (SDaaS)](https://github.com/linkeddatacenter/sdaas-ce) and provides:

- a persistent RDF store for the knowledge graph; 
- a [SPARQL service](https://www.w3.org/TR/sparql11-overview) endpoint; 
- a data ingestion engine; 
- a set of gateways to transform the raw data provided by from the [MEF Open Data Portal](https://openbdap.mef.gov.it) into fr-ap-mef linked data;
- a build script to drive the ingestion process.

data-budget is designed to be the data provider component of a general system architecture composed by:

- optional *API interfaces* that query the SPARQL endpoint and expose knowledge graph views as expected by software agents (e.g. LODMAP2D-api). 
- one or more software agents to explore the knowledge base (e.g LODMAP2D, YASGUI, LODVIEW, etc., etc.)

![architecture](doc/architecture.png)

The platform is shipped with a [Docker](https://docker.com) setup that makes it easy 
to get a containerized development environment up and running. 
If you do not already have Docker on your computer, 
[it's the right time to install it](https://docs.docker.com/install/).

To test the platform, an example stack of services is provided. Just type: 

```
docker-compose build
docker-compose up -d
```

This will start locally the following services:


| Name        | Description                                                   | Port 
| ----------- | ------------------------------------------------------------- | ------- 
| sdaas       | a server that manages the datastore and the ingestion engine  | 29321    
| api         | a server that manages the BGO linked data resources           | 29322 
| webapp      | a server that provides an instance of LODMAP2D bgo reasoner   ! 20323

- try http://localhost:29321/sdaas to access sdaas workbench
- try http://localhost:29322/app.ttl to test LODMAP2D-api endpoint
- try http://localhost:29323/partition/overview to see LODMAP2D explored in actions.
- access sdaas cli interface  typing `docker exec -ti "here the id of the docker instance running sdaas platform" sdaas`

The first time you start the containers, Docker downloads and builds images for you. It will take some time, but don't worry
this is done only once. Starting servers will then be lightning fast.


To shudown the platform type: 

```
docker-compose down
```


## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://copernicani.slack.com/) general room. Make sure to mention **@enrico** so he is notified


## Credits

- the Smart Data Management Platform and LODMAP2D application was developed by [LinkedData.Center](http://LinkedData.Center/)
- data extracted from [Open BDAP portal](https://bdap-opendata.mef.gov.it/) with [CC-BY](http://creativecommons.org/licenses/by/3.0) license
- the RDF datastore and the SPARQL endpoint is based on the [Blazegraph community edition](https://www.blazegraph.com/)

Thanks to all project contributors, to the [Copernicani community](https://copernicani.it/) and to the [g0v asia community](http://g0v.asia) for ideas and support.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
