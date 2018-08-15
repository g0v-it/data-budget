# g0v-data
An RDF repository  and APIs  to feed Government Budget Visualization applications. 

** THIS PROJET IS IN A VERY EARLY DEVELOPMENT STAGE **

g0v is a decentralized civic tech community to advocate transparency of information and build tech solutions 
for citizens to participate in public affairs from the bottom up. The community was born in Taiwan, see [g0v.asia web site](http://g0v.asia/) for more info.

This project aims to create a common data platform to feed to budget visualization applications like http://budget.taipei/


## Components

The project contains four components:

- the g0v-ap ontology designed as an application profile of the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube),  [Data Catalog Vocabulary](https://www.w3.org/TR/vocab-dcat/) and [SKOS](https://www.w3.org/TR/skos-primer). Find files and documentation in [gov-ap directory](gov-ap)
- an RDF repository with a SPARQL endpoint. See docs in [rdfstore directory](rdfstore)
- a set of gateways to transform raw Government budget data into linked data according with g0v-ap ontology and a build script that drives an automated data ingestion platform to populate an RDF repository. In first release  gateways and build scripts for [BDAP](http://www.bdap.tesoro.it/sites/openbdap) are provided. See files and docs in [sdaas directory](sdaas)
- a set of APIs that query the RDF repository and produce json data with a schema suitable to be used to a Government Budget Visualization application. Following applcations are supported: budget.taipei and budget.g0v.it . See files and docs in [apis directory](apis)
 
[This picture](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-architecture.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Q2VSl5IL_K1qByiSzGDffSXiVbSRA1zl%26export%3Ddownload) shows main components interactions.

The deploy of g0v-data is based on a [stack of docker containers](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-stack.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1FEItM1NOMCzj03GxkXc_EE5SLnJ-oF_R%26export%3Ddownload):


The ingestion platform should be executed just to update the knoledge base contained into rdf repository.

The government budget raw data must be provided as a web link as [3,4 or 5 stars linked data]() . All required ETL processes are implemented by the ingestion platform using custom data gateways drived by the build script.


## Quickstart

To run api ad rdf store type

```bash
docker-compose up
```

The rdf graph database exposes SPARQL enpoint on 9999 port (try: http://localhost:9999/bigdata )

The api exposes http enpoints at 8080 port (try http://localhost:8080/v1)

To load raw data for italian government budget, type:

```bash
docker run --name sdaas -v sdaas/it:/sdaas linkeddatacenter/sdaas:v2.0
```

then get data in a json format with http://localhost:8080/v1/metrics

v1 api format is compatible with [budget.taipei application](https://github.com/tony1223/tw-budget-platform)
 


## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://linkeddatacenter.slack.com/) general room. Make sure to mention **@enrico** so he is notified.

## Credits

- [All Contributors](../../contributors)
- the RDF datastore and SPARQL endpoint  is based on the community edition of [Blazegraph](https://www.blazegraph.com/)
- the ingestion platform is based on [SDaaS Platform](https://bitbucket.org/linkeddatacenter/sdaas/wiki/Home) by LinkedData.Center . The required SDaaS license is kindly provided for free by LinkedData.Center to the g0v community for non commercial projects.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

