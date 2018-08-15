# g0v-data
An RDF repository  and APIs  to feed Government Budget Visualization applications. 

** THIS PROJET IS IN A VERY EARLY DEVELOPMENT STAGE **

## project objectives ##

The objective of this project is to provide an RDF repository of data about Government budget that you can query using both SPARQL and custom APIs.

The APIs provide a common data interface to be used by Government Budget Visualization applications.

The project contains:

- an ontology derived by [The W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube)
- a framework to describe th hierarchical structure of a Government Budget items using [SKOS](https://www.w3.org/TR/skos-primer)
- a set of gateways to transform raw e-gov open data into linked data according with g0v-data ontology. In first release only gateways for [BDAP](http://www.bdap.tesoro.it/sites/openbdap) are provided.
- an RDF repository with  a SPARQL end point-
- an automated ingestion process to populate an RDF repository
- a set of APIs that query the RDF repository and produce json data with a schema suitable to be used to a overnment Budget Visualization application.
  following applcations are initially supported: budget.taipei and budget.g0v.it
  
 
