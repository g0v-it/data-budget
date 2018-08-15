# g0v-data

A simplesmart data management platform to feed Government Budget Visualization applications. 

** THIS PROJECT IS IN A VERY EARLY DEVELOPMENT STAGE **

g0v is a decentralized civic tech community to advocate transparency of information and build tech solutions 
for citizens to participate in public affairs from the bottom up. The community was born in Taiwan, see [g0v.asia web site](http://g0v.asia/) for more info.

This project aims to create a smart data management platform to feed budget visualization applications like http://budget.taipei/ based on W3C semantic web best practices.

## Components

The project contains four logical components:

- an ontology (g0v-ap) designed as an application profile of the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube), the [Data Catalog Vocabulary](https://www.w3.org/TR/vocab-dcat/) and [SKOS](https://www.w3.org/TR/skos-primer). Find files and documentation in [gov-ap directory](gov-ap)
- a process providing a RDF store and a [SPARQL endpoint](https://www.w3.org/TR/sparql11-overview), see docs in [rdfstore directory](rdfstore)
- a set of gateways to transform raw Government budget data into linked data according with g0v-ap ontology and a build script that drives an automated data ingestion engine that populate the RDF store. Gateways and build scripts for italian budget data [BDAP](http://www.bdap.tesoro.it/sites/openbdap) are provided. See files and docs in [sdaas directory](sdaas)
- a set of APIs that query the SPARQL endpoint provided by rdfstore and produce json data with a schema suitable to be used to a Government Budget Visualization application. Following applications are supported: budget.taipei and budget.g0v.it . See files and docs in [apis directory](apis)
 
[This picture](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-architecture.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Q2VSl5IL_K1qByiSzGDffSXiVbSRA1zl%26export%3Ddownload) shows the components interactions.

The deploy of g0v-data is based on a [stack of docker containers](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-stack.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1FEItM1NOMCzj03GxkXc_EE5SLnJ-oF_R%26export%3Ddownload)

The government budget raw data source must be available as [3,4 or 5 stars linked data](https://5stardata.info/en/) . All required ETL processes are implemented by the DMP using data gateways drived by the build script.


## Quickstart

As prerequisite you need need [docker](https://docs.docker.com/) version 18+ with docker-compose.

To run the rdf store and loading it with data for italian government budget, type:

```bash
docker-compose up -d
# run the ingestion processor
docker run \
	--network g0v-data_default \
	-v sdaas/it:/dmp \
	--env SD_BG_ENDPOINT=rdfstore:8080 \
	--env SD_BG_NAMESPACE=kb \
	linkeddatacenter/sdaas \
	/dmp/build.sdaas -k blazegraph
```

The rdf store exposes a SPARQL endpoint on 9999 port (try: http://localhost:9999/bigdata to access interactive SPARQL interface )

The api server exposes an http enpoint at 8080 port. Try to get the italian budget data with http://localhost:8080/v1/budget/it/metrics

The v1 APIs produce an json data compatible with the [budget.taipei application](https://github.com/tony1223/tw-budget-platform)
 


## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://linkeddatacenter.slack.com/) general room. Make sure to mention **@enrico** so he is notified.

## Credits

- [All Contributors](../../contributors)
- the RDF datastore and SPARQL endpoint  is based on the community edition of [Blazegraph](https://www.blazegraph.com/)
- the ingestion engine is based on the [SDaaS Platform](https://bitbucket.org/linkeddatacenter/sdaas/wiki/Home) by LinkedData.Center . The required SDaaS license is kindly provided for free by LinkedData.Center to the g0v community for non commercial projects.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

