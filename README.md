![copernicani](https://copernicani.it/wp-content/uploads/cropped-logo_orizzontale_trasparente-1-e1525161268864.png)

# g0v-data

A simple smart data management platform to feed http://budget.g0v.it/ web application and similar apps. 

g0v is a decentralized civic tech community to advocate transparency of information and build tech solutions 
for citizens to participate in public affairs from the bottom up. The community was born in Taiwan, see [g0v.asia web site](http://g0v.asia/) for more info.

This project aims to create a general smart data management platform to feed a budget visualization application based on W3C semantic web standards.

**Ask not why nobody is doing this. You are the "nobody"!**

## Components

The project contains four logical components:

- the **g0v-ap** ontology designed to describe a budget data with an application profile of the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube). Find files and documentation in [g0v-ap directory](g0v-ap/README.md)
- **sdaas**: (smart data as a service) a data management platform providing a RDF store, a [SPARQL endpoint](https://www.w3.org/TR/sparql11-overview) and a data ingestion engine. See files and docs in [sdaas directory](sdaas/README.md)
-**gov-kb**: a datalake, a set of gateways to transform raw government budget data into linked data according with g0v-ap ontology and a build script that drives the sdaas ingestion engine to populate the RDF store.
- a set of **APIs** that query the SPARQL endpoint and produce json data with a schema suitable to be used with the [vue-budget component project](). See files and docs in [apis directory](apis/README.md)
 
[This picture](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-architecture.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Q2VSl5IL_K1qByiSzGDffSXiVbSRA1zl%26export%3Ddownload) shows the components interactions.

The deploy of g0v-data requires a [stack of two docker containers](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-stack.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1FEItM1NOMCzj03GxkXc_EE5SLnJ-oF_R%26export%3Ddownload), one for running the data management platform and one for running the APIs server.


## Quickstart

You need need to install [docker](https://docs.docker.com/) version 18+ with docker-compose.

To run the rdf store and loading it with data for Italian government budget, type:

```bash
docker-compose up -d
# Start the data ingestion process
docker exec "$(basename $PWD)_sdaas_1" sdaas -f build.sdaas --reboot
```

The data management platform entry point is located at http://localhost:9999/ try http://localhost:9999/sdaas/#explore:kb:urn:sdaas:config:kb

**WARNING**: the 9999 port must be disabled in production environment to prevent security issues.

The api endpoint is located at http://localhost:8080/ try http://localhost:8080/accounts


## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://linkeddatacenter.slack.com/) general room. Make sure to mention **@enrico** so he is notified.

## Credits

- the RDF datastore and the SPARQL endpoint is based on the [Blazegraph community edition](https://www.blazegraph.com/)
- the gov-ap ontology and the smart data management platform was developed by Enrico Fagnoni (enrico at LinkedData.Center) using the SDaaS platform by [LinkedData.Center](http://LinkedData.Center/)
- API server and gateways was developed by Yassine Ouahidi (yass.ouahidi at gmail.com ) from [DataChef](http://DataChef.Cloud)

Thanks to all project contributors, to the [Copernicani community](https://copernicani.it/) and to the [g0v asia community](http://g0v.asia) for ideas and support.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
