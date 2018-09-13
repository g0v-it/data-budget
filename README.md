![copernicani](https://copernicani.it/wp-content/uploads/cropped-logo_orizzontale_trasparente-1-e1525161268864.png)

# g0v-data

A simple smart data management platform to feed http://budget.g0v.it/ web application and similar apps. 

This project aims to create a general smart data management platform to feed a budget visualization application based on W3C semantic web standards.

**Try it now:**

- **api**: http://data.budget.g0v.it/api/v1
- **linked open data** viewer: http://data.budget.g0v.it/resource/welcome
- **SPARQL endpoint**: http://data.budget.g0v.it:8889/sdaas/#query


## Development

The project contains the following logical components:

- the **g0v-ap**, a general application ontology to describe a budget report with the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube). Find files and documentation in [g0v-ap directory](g0v-ap/README.md)
- **sdaas** (smart data as a service):  the data management platform core providing a RDF store, a [SPARQL endpoint](https://www.w3.org/TR/sparql11-overview), a data ingestion engine, a datalake, a set of gateways to transform raw data in linked data according with g0v-ap-api ontology (an application level extension of g0v-ap) and a build script that populates the RDF store. See files and docs in [sdaas directory](sdaas/README.md)
- a set of **apis** that query the SPARQL endpoint and produce json data with a schema suitable to be used with the [vue-budget component project](). See files and docs in [apis directory](apis/README.md)
- an installation of a web application derived from **lodview** to navigate the knowledge base as linked open data
- a **router** that provides redirects and proxies to the platform services.
 
This pictureshows the components interactions:

![architecture](doc/gov-data-architecture.png)


## Deployment

The full deploy of g0v-data requires a stack of four services (e.g. docker containers):

![stack](doc/gov-data-stack.png)



### local development quickstart

Install [docker](https://docs.docker.com/) version 18+ with docker-compose.

Deploy the whole stack:

```bash
docker-compose up -d
```

### router entry points :

The frontent webapp acts a redirector and as a transparent proxy for all da management platform services. It provides following entry point:

- **/** redirects to this readme file
- **/api/** redirects to api documentation
- **/api/v1/<api command>*** redirects to api  ( try http://localhost/api/accounts)
- **/resource/<resource id>** calls the data browser  ( try http://localhost/resource/welcome)
- **/g0v-ap/v1** pretty print of the g0v-ap vocabulary  ( try http://localhost/g0v-ap/v1)
- **/g0v-ap-api/v1** pretty print of the g0v-ap-api vocabulary  ( try http://localhost/g0v-ap-api/v1)
- **/sdaas/sparql** redirects to sparql endpoint  ( try http://localhost/sdaas/sparql)

### published ports:

- **port 80**: the router endpoint
- **8080** The api endpoint
- **8082** lodview endpoint
- **8083**  lode endpoint ( try http://localhost:8083/lode/extract?url=http://localhost) 
- **port 9999**: the data management platform entry point (read only)

## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://linkeddatacenter.slack.com/) general room. Make sure to mention **@enrico** so he is notified.

## Credits


- data extracted from by [OpenBDAP](https://bdap-opendata.mef.gov.it/) with CC-BY open licens
- the RDF datastore and the SPARQL endpoint is based on the [Blazegraph community edition](https://www.blazegraph.com/)
- the gov-ap ontology and the smart data management platform was developed by [Enrico Fagnoni] (https://github.com/ecow) using the [SDaaS platform by LinkedData.Center](http://LinkedData.Center/)
- API server and gateways was developed by [Yassine Ouahidi](https://github.com/YassineOuahidi)
- [LOD-VIEW](http://lodview.it/) was developed by [Diego Valerio Camarda](https://www.linkedin.com/in/dvcama) and [Alessandro Antonuccio](http://hstudio.it/).

Thanks to all project contributors, to the [Copernicani community](https://copernicani.it/) and to the [g0v asia community](http://g0v.asia) for ideas and support.


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
