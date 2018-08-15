# g0v-data
An RDF repository  and APIs  to feed Government Budget Visualization applications. 

** THIS PROJET IS IN A VERY EARLY DEVELOPMENT STAGE **

g0v is a decentralized civic tech community to advocate transparency of information and build tech solutions 
for citizens to participate in public affairs from the bottom up. The community was born in Taiwan, see [g0v.asia web site](http://g0v.asia/) for more info.

This project aims to create a commom reusable platform to feed data to budget visualization applications like http://budget.taipei/


## Components

The project contains:

- the g0v-ap ontology designed as an application profile of the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube),  [Data Catalog Vocabulary](https://www.w3.org/TR/vocab-dcat/) and [SKOS](https://www.w3.org/TR/skos-primer)
- an RDF repository with a SPARQL endpoint
- a set of gateways to transform raw Government budget data into linked data according with g0v-ap ontology and a build script that drives an automated data ingestion platform to populate an RDF repository. In first release  gateways and build scripts for [BDAP](http://www.bdap.tesoro.it/sites/openbdap) are provided.
- a set of APIs that query the RDF repository and produce json data with a schema suitable to be used to a Government Budget Visualization application. Following applcations are supported: budget.taipei and budget.g0v.it
 
[This picture](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-data-architecture.html#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Q2VSl5IL_K1qByiSzGDffSXiVbSRA1zl%26export%3Ddownload) shows main components interactions.

External components used:

- the RDF datastore and SPARQL endpoint  is based on the community edition of [Blazegraph](https://www.blazegraph.com/)
- the ingestion platform is based on [SDaaS Platform](https://it.linkeddata.center/p/sdaas/) by LinkedData.Center . The required SDaaS license is kindly provided for free by LinkedData.Center to the g0v community non commercial projects
- the data catalog contains nmetadata about used datasets and must be provided as an rdf file according with [Data Catalog Vocabulary](https://www.w3.org/TR/vocab-dcat/) as descrived in g0v-ap ontology.
- the government budget raw data must be provided as a web link as [3,4 or 5 stars linked data]() . All required ETL processes are implemented by the ingestion platform using custom data gateways drived by the build script.


## Support

For answers you may not find in here or in the Wiki, avoid posting issues. Feel free to ask for support on the [Slack](https://linkeddatacenter.slack.com/) general room. Make sure to mention **@enrico** so he is notified.

## Credits

- [All Contributors](../../contributors)
- [Blazegraph project](https://www.blazegraph.com/)
- [LinkedData.Center](http://linkeddata.center/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

