# g0v-data

A simple smart data management platform to feed Italian Government Budget Visualization application. 


g0v is a decentralized civic tech community to advocate transparency of information and build tech solutions 
for citizens to participate in public affairs from the bottom up. The community was born in Taiwan, see [g0v.asia web site](http://g0v.asia/) for more info.

This project aims to create a smart data management platform to feed a budget visualization application based on W3C semantic web best practices.

## Components

The project contains three logical components:

- an ontology (g0v-ap) designed to describe the Italian budget data with an application profile of the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube) . Find files and documentation in *g0v-ap directory*
- a data management platform providing a RDF store, a [SPARQL endpoint](https://www.w3.org/TR/sparql11-overview), a set of gateways to transform raw Government budget data into linked data according with g0v-ap ontology and a build script that drives an automated data ingestion engine that populate the RDF store.  See files and docs in *sdaas directory*
- a set of APIs that query the SPARQL endpoint provided by the platform and produce json data with a schema suitable to be used with the [vue-budget component project](). See files and docs in *apis directory*
 
[This picture](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-architecture.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Q2VSl5IL_K1qByiSzGDffSXiVbSRA1zl%26export%3Ddownload) shows the components interactions.

The deploy of g0v-data is based on a [stack of docker containers](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-stack.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1FEItM1NOMCzj03GxkXc_EE5SLnJ-oF_R%26export%3Ddownload)

The government budget raw data source must be available as [3,4 or 5 stars linked data](https://5stardata.info/en/) . All required ETL processes are implemented by the DMP using data gateways drived by the build script.


## Quickstart

As prerequisite you need need [docker](https://docs.docker.com/) version 18+ with docker-compose.

To run the rdf store and loading it with data for italian government budget, type:

```bash
docker-compose up -d
# run the ingestion processor
docker exec databudget_sdaas_1 ingestion.sh 
```

The rdf store exposes a SPARQL endpoint on 9999 port. Try to acesss the admin contro panel ar http://localhost:9999/bigdata/. **WARNING**: this interface must be disabled in producion environment to prevent security issue

The api server exposes an http endpoint at 8080 port. Try to get the italian budget data with http://localhost:8080/accounts



## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://linkeddatacenter.slack.com/) general room. Make sure to mention **@enrico** so he is notified.

## Credits

- the RDF datastore and SPARQL endpoint is based on the [Blazegraph community edition](https://www.blazegraph.com/)
- the ontology and the smart data management platform was developed by Enrico Fagnoni (enrico at LinkedData.Center) with SDaaS Platform by [LinkedData.Center](http://LinkedData.Center/)
- API server was developed by Yassine Ouahidi (yass.ouahidi at gmail.com ) from [DataChef](http://DataChef.Cloud)

Thanks to all project contributors and to the [Copernicani community](https://copernicani.it/) ideas and for support

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

