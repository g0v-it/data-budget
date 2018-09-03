![copernicani](https://copernicani.it/wp-content/uploads/cropped-logo_orizzontale_trasparente-1-e1525161268864.png)

# g0v-data

A simple smart data management platform to feed http://budget.g0v.it/ web application and similar apps. 

This project aims to create a general smart data management platform to feed a budget visualization application based on W3C semantic web standards.

## Reference implementation entry points

- **api**: http://data.budget.g0v.it/api/v1
- **linked open data**: http://data.budget.g0v.it/resource/welcome
- **g0v-ap** ontology: http://data.budget.g0v.it/g0v-ap/v1

Just http://data.budget.g0v.it/ redirects to this document.



## Architecture

The project contains the following logical components:

- the **g0v-ap**, an application ontology to describe a budget report with the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube). Find files and documentation in [g0v-ap directory](g0v-ap/README.md)
- **sdaas**: (smart data as a service) a data management platform core providing a RDF store, a [SPARQL endpoint](https://www.w3.org/TR/sparql11-overview) and a data ingestion engine. See files and docs in [sdaas directory](sdaas/README.md)
-**gov-kb**: a datalake, a set of gateways to transform raw government budget data into linked data according with g0v-ap ontology and a build script that drives the sdaas ingestion engine to populate the RDF store.
- a set of **apis** that query the SPARQL endpoint and produce json data with a schema suitable to be used with the [vue-budget component project](). See files and docs in [apis directory](apis/README.md)
- an installation of a web application derived from **lodview** to navigate the knowledge base
- a web **frontend** that provides the platform home page and the redirect/proxy to the containers'services
 
[This picture](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-architecture.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Q2VSl5IL_K1qByiSzGDffSXiVbSRA1zl%26export%3Ddownload) shows the components interactions.

The deploy of g0v-data requires a [stack of four docker containers + a volume for data persistency ](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-stack.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1FEItM1NOMCzj03GxkXc_EE5SLnJ-oF_R%26export%3Ddownload), one for running the data management platform and one for running the APIs server.


## local development quickstart

Install [docker](https://docs.docker.com/) version 18+ with docker-compose.

To run the rdf store and loading it with data for Italian government budget, type:

```bash
docker-compose up -d
# Start the data ingestion process
docker exec "$(basename $PWD)_sdaas_1" sdaas -f build.sdaas --reboot
```

### fronted entry points :

The frontent webapp acts a redirector and as a transparent proxy for all da management platform services. It provides following entry point:

- **/** redirects to this readme file
- **/api/** redirects to api documentation
- **/api/<api command>*** redirects to api  ( try http://localhost/api/accounts)
- **/resource/<resource id>** calls the lod browser  ( try http://localhost/resource/welcome)
- **/g0v-ap/v1** pretty print of the g0v-ap vocabulary  ( try http://localhost/g0v-ap/v1)

### published ports:

These default apply. You can override them in  docker-compose.yml file:

- **port 80**: the frontend 
- **8080** The api endpoint
- **8082** lodview endpoint
- **8083**  lode endpoint ( try http://localhost:8083/lode/extract?url=http://localhost) 
- **port 9999**: the data management platform entry point. This port should be disabled in production. ( try http://localhost:9999/sdaas)

## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://linkeddatacenter.slack.com/) general room. Make sure to mention **@enrico** so he is notified.

## Credits

- the RDF datastore and the SPARQL endpoint is based on the [Blazegraph community edition](https://www.blazegraph.com/)
- the gov-ap ontology and the smart data management platform was developed by [Enrico Fagnoni] (https://github.com/ecow) using the [SDaaS platform by LinkedData.Center](http://LinkedData.Center/)
- API server and gateways was developed by [Yassine Ouahidi](https://github.com/YassineOuahidi)
- [LOD-VIEW](http://lodview.it/) was developed by [Diego Valerio Camarda](https://www.linkedin.com/in/dvcama) and [Alessandro Antonuccio](http://hstudio.it/).

Thanks to all project contributors, to the [Copernicani community](https://copernicani.it/) and to the [g0v asia community](http://g0v.asia) for ideas and support.


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
