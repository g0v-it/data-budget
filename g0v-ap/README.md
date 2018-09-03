g0v-data application profile 
------------------------------

g0v-ap is an general Semantic Web Application defined with [OWL](https://www.w3.org/TR/owl2-primer/) suitable to annotate a government budget data with the purpose of supporting budget visualization applications (e.g. http://budget.g0v.it/).  

It captures different perspectives of a government budget data like historical trends, cross-department and component breakdown of tax by government. 

g0v-ap is an application profile that builds upon the following RDF vocabularies:: 

- the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube), to describe the outgoings/incomes accounts observations
- the [Data Catalog Vocabulary](https://www.w3.org/TR/vocab-dcat/) to describe the dataset metadata
- the [SKOS](https://www.w3.org/TR/skos-primer) to describe balance taxonomy.
- The [DCMI Metadata Terms](http://dublincore.org/documents/dcmi-terms/)
- some facilities from [sdmx ontologies](https://sdmx.org/)
- the [g0v-ap Vocabulary](http://data.budget.g0v.it/g0v-ap/v1) that defines few application classes, attributes and bindings.

g0v-ap also reuses some individual references to linked open data provided by [UK e-gov](https://github.com/alphagov/datagovuk_reference) and by [dbpedia](http://dbpedia.org/).

g0v-ap is inspired by the [Financial Report Semantics and Dynamics Theory](doc/Theory-2017-06-26.pdf). The main concept in g0v-ap is the **Fact** that is an observation of a money amount described with some attributes: a reference period (e.g. year 2018), a concept defined in a specific taxonomy, etc, etc. Facts  are grouped in **Component**s also described with a concept in a taxonomy, the set of all components' facts define a **FinancialReport** 

Every fact is strictly related to a taxonomy (1/1 relation). This could theoretically, prevents an historical analysis. In a practical use, the taxonomies in different financial report of for the same legal entity, do not change very much. For this reason it is possible to map different fact concepts, defined in different taxonomies, using the skos:closeMatch attribute. A reasoner MAY uses this information to compute a "euristic" historical trend for a specific Fact or Component.

Some fact properties, if not explicitly defined, can be inherited form the financial report attributes. Also the groups hierarchy can be deducted from the concepts taxonomy structure.

See the [g0v-ap UML diagram](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-uml-diagram#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Qa_zoF1Nl8ULUg9uChN-OH3ep2Lta4PY%26export%3Ddownload) for more info about restrictions.


In this snippet (in RDF turtle format) describes a provisional financial report as linked data with g0v-ap:

```
@prefix g0v: <http://data.budget.g0v.it/g0v-ap/v1#>.
@prefix kees: <http://linkeddata.center/kees/v1#> .
@prefix skos: <http://www.w3.org/2004/02/skos/core#> .
@prefix dct:  <http://purl.org/dc/terms/> .
@prefix daq: <http://purl.org/eis/vocab/daq#>.
@prefix interval: <http://reference.data.gov.uk/def/intervals/> .
@prefix qb:       <http://purl.org/linked-data/cube#> .
@prefix sdmx-dimension:  <http://purl.org/linked-data/sdmx/2009/dimension#> .
@prefix sdmx-attribute:  <http://purl.org/linked-data/sdmx/2009/attribute#> .
@prefix : <#>.

:2018_budget_report a g0v:FinancialReport ;
	sdmx-dimension:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	sdmx-attribute:unitMeasure <http://dbpedia.org/resource/Euro> .
	
[] a g0v:Fact;
	g0v:concept :level_5_account_02001200010001 ;
	qb:dataSet :2018_budget_report ;
	g0v:obsValue 288149000000.00	.

[] a g0v:Fact;
	g0v:concept :level_5_account_02001200020001 ;
	qb:dataSet :2018_budget_report ;
	g0v:obsValue 881493000.00	.
	
:level_5_account_A102001200010001 a skos:Concept ;skos:broader :level_4_account_A10200120001 .
:level_4_account_A10200120001 a skos:Concept ; skos:broader :level_3_account_A1020012 .
:level_3_account_A1020012 a skos:Concept ; skos:broader :level_2_account_A102 .
::level_2_account_A102 a skos:Concept ; skos:broader :top_level_account_A1 .
:top_level_account_A1 a skos:Concept .
:budget_taxonomy a g0v:BudgetTaxonomy; skos:hasTopConcept :top_level_account_A1 .

:2018_quality  a qb:Observation ;
    daq:computedOn :2018_budget_report ; 
    daq:metric kees:trustGraphMetric;
    daq:value 0.9 ;
    daq:isEstimated true 
.
```

To be valid, previous snippet requires a reasoner able to understand skos and basic g0v-ap axioms and to generate the missing mandatory properties and relations.

The directory [examples](examples/README.ttl) provides some data and axioms ready to use in a RDF store with a SPARQL update and query capabilities.

## How to use g0v-ap application profile

If you want to write an application that analyze/visualize budget data, first you have to transform the government budget data in RDF linked data using gov-ap classes and properties. The produced linked data can be stored in a RDF store and queried using the [SPARQL language](http://www.w3.org/TR/sparql11-query/).
With SPARQL update you can also easily write rules to generate (i.e. infers) other data to simplify the development of your application.

See [in this picture the typical dataflow](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-budget-datafow#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1iXdW0V08-gUK_SL1EkYmnofGvs1L1UD4%26export%3Ddownload)

## Edit g0v-ap ontology

g0v-ap is expressed as [owl file](g0v-ap.owl) serialized as RDF xml. You can edit the file by hand or using [Protégé](https://protege.stanford.edu/)

## Practice with the g0v-ap

To practice with ontolg0v-apogy you need a RDF data store and some data. [Blazegraph](https://www.blazegraph.com/) is a good starting point. For a quick start, use this docker:

```	
docker run --name blazegraph -d -p 9999:8080 lyrasis/blazegraph:2.1.4
```

1. Connect to the blazegraph workbench SPARQL update tab pointing the browser to http://localhost:9999/sdaas/#update
2. Load examples/example_data.ttl in a RDF triple store that support SPARQL Query and SPARQL Update.
2. Execute examples/skos_axioms.sparql_update create maps between taxonomies and other base skos rules
3. Execute examples/g0v_axioms.sparql_update to load external data and create low level components
4. Execute examples/app_axioms.sparql_update to generate application level data (e.g. budget amount aggregations)
5. Try SPARQL queries in examples directory


## Generating g0v-ap ontology documentation on-the-fly

Here find a container to run a [lode server](https://github.com/essepuntato/LODE)
 
```	
docker build . -t lode
docker run -d -v gov-ap.owl:/lode/LODE-master/src/main/webapp/g0v-ap.owl --name lode -p 9090:8080 lode
```

Point your browser to http://localhost:9090/lode/extract?url=localhost:8080/lode/gov-ap.owl

