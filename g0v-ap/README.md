g0v-data application profile (ontology)
---------------------------------------

g0v-ap is an general [OWL ontology](https://www.w3.org/TR/owl2-primer/) suitable to annotate a government budget data with the purpose of supporting budget visualization applications (e.g. http://budget.g0v.it/).  

It captures different perspectives of a government budget data like historical trends, cross-department and component breakdown of tax by government.

g0v-ap is an semantic web application profile that builds upon the following RDF vocabularies:: 

- the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube), to describe the outgoings/incomes accounts observations
- the [Data Catalog Vocabulary](https://www.w3.org/TR/vocab-dcat/) to describe the dataset metadata
- the [SKOS](https://www.w3.org/TR/skos-primer) to describe balance taxonomy.
- The [DCMI Metadata Terms](http://dublincore.org/documents/dcmi-terms/)
- some facilities from [sdmx ontologies](https://sdmx.org/)
- the [g0v-budget Vocabulary](g0v-budget.ttl) that defines few classes, attributes and bindings.

g0v-ap also reuses some individual references to linked open data provided by [UK e-gov](https://github.com/alphagov/datagovuk_reference) and by [dbpedia](http://dbpedia.org/).

The two main concepts in g0v-ap are **Budget** and **Record** that are observations of money amount in a reference period (e.g. year 2018) for a specific account defined in a taxonomy . The g0v:Budget class defines a forecast value for an account defined in a budget report (e.g. italian "legge di bilancio"), the g0v:Record class defines the consolidated historical account value for a specific time period (e.g. Rendiconto 2017).

Very often, the account taxonomy can change on a year basis. This could teorically prevent an account historical analysis. In a practical use, these taxonomies do not change very much. For this reason it is possible to map different account concepts defined in different taxonomies using the skos:closeMatch attribute. A reasoning  uses this information to compute a "light" historical trend for a specific account.

See the [g0v-ap UML diagram](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-uml-diagram#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Qa_zoF1Nl8ULUg9uChN-OH3ep2Lta4PY%26export%3Ddownload) for more info about restrictions.


This snippet (in RDF turtle format) find some budget information expressed as linked data with g0v-ap:

```
PREFIX g0v: <http://data.budget.g0v.it/g0v-budget/v1#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#> 
PREFIX skos:     <http://www.w3.org/2004/02/skos/core#> 
PREFIX dcat:      <http://www.w3.org/ns/dcat#> 
PREFIX dct:      <http://purl.org/dc/terms/> 
PREFIX foaf:     <http://xmlns.com/foaf/0.1/> 
PREFIX interval: <http://reference.data.gov.uk/def/intervals/> 
PREFIX qb:       <http://purl.org/linked-data/cube#> 
PREFIX sdmx-dimension:  <http://purl.org/linked-data/sdmx/2009/dimension#> 
PREFIX sdmx-measure:    <http://purl.org/linked-data/sdmx/2009/measure#> 
PREFIX sdmx-attribute:  <http://purl.org/linked-data/sdmx/2009/attribute#> 
PREFIX : <#>

:legge_bilancio_2018 a g0v:Dataset ;
	sdmx-dimension:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	sdmx-attribute:unitMeasure <http://dbpedia.org/resource/Euro> .
	
:rendiconto_2017 a g0v:Dataset ;
	sdmx-dimension:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2017-01-01T00:00:00/P1Y> ;
	sdmx-attribute:unitMeasure <http://dbpedia.org/resource/Euro> .
	
[] a g0v:Budget;
	g0v:subject :capitolo_02001200010001 ;
	qb:dataSet :legge_bilancio_2018 ;
	g0v:obsValue 288149000000.00	.

[] a g0v:Budget;
	g0v:subject :capitolo_02001200020001 ;
	qb:dataSet :legge_bilancio_2018 ;
	g0v:obsValue 881493000.00	.
	
[] a g0v:Record;
	g0v:subject :capitolo_02001200010001 ;
	qb:dataSet :rendiconto_2017 ;
	g0v:obsValue 278149000000.00	.

[] a g0v:Budget;
	g0v:subject :capitolo_02001200020001;
	qb:dataSet :rendiconto_2017 ;
	g0v:obsValue 88149123400.00	.

:capitolo_A102001200010001 a skos:Concept ;skos:broader :azione_A10200120001 .
:azione_A10200120001 a skos:Concept ; skos:broader :programma_A1020012 .
:programma_A1020012 a skos:Concept ; skos:broader :missione_A102 .
::missione_A102 a skos:Concept ; skos:broader :amministrazione_A1 .
:amministrazione_A1 a skos:Concept .
:tassonomia_spese_amministrazioni a g0v:BudgetTaxonomy; skos:hasTopConcept :amministrazione_A1 .

...

```

Here a complete [example data file](examples/example_data.ttl) ready to be loaded in any RDF datastore.


## How to use g0v-ap ontology

If you want to write an application that analyze/visualize budget data you have to transform the government budget data in RDF linked data using gov-ap classes and properties. The produced linked data can be stored in a RDF store and queried using the [SPARQL language](http://www.w3.org/TR/sparql11-query/).
With SPARQL update you can also easily write rules to generate (i.e. infers) other data to simplify the development of your application.

For example, the [api server implementation](../apis/README.md) to feed vue-budget components with data uses an applicatio platfor g0v-ap and and axioms to feed vue-budget components.

See [in this picture the typical dataflow](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-budget-datafow#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1iXdW0V08-gUK_SL1EkYmnofGvs1L1UD4%26export%3Ddownload)


## Test the ontology

Instal docker and run:

```	
cd ..
docker-compose up -d sdaas 
```

1. Connect to [blazegraph console SPARQL update tab](http://localhost:9999/bigdata/#update)
2. Load example_data.ttl in a RDF triple store that support SPARQL Query and SPARQL Update.
3. Execute g0v_axioms.sparql_update to load external data and create maps between taxonomies
4. Execute api_axioms.sparql_update to generate application level data (e.g. budget amount aggregations)
5. Try SPARQL queries
