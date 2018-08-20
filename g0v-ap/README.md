g0v-data application profile (ontology)
---------------------------------------

g0v-ap is an [OWL ontology](https://www.w3.org/TR/owl2-primer/) used to annotate italian government budget data.  

It captures different perspectives of  government budget data like historical trend, cross-department and component breakdown of tax by government.

The main concept in g0v-ap is the **account** that is an observation of a money amount in a reference period (e.g. year 2018) for a specific subject (e.g. "capitolo di spesa" ) in a  taxonomy ( i.e. the budget breackdown structures).

g0v-ap is an semantic web application profile that builds upon the following existing RDF vocabularies:: 

- the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube) , to describe the balance accounts observations
- the [Data Catalog Vocabulary](https://www.w3.org/TR/vocab-dcat/) to describe the dataset metadata
- the [SKOS](https://www.w3.org/TR/skos-primer) to describe balance taxonomy.
- The [DCMI Metadata Terms](http://dublincore.org/documents/dcmi-terms/)
- some facilities from [sdmx ontologies] (https://sdmx.org/)
- the [g0v-budget Vocabulary](g0v-budget.rdf) to define some class and bindings mainly derived from bdap data structure.

g0v-ap reuses  some reference linked open data provided by [UK e-gov](https://github.com/alphagov/datagovuk_reference) for reference period time intervals.

See the [g0v-ap UML diagram](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-uml-diagram#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Qa_zoF1Nl8ULUg9uChN-OH3ep2Lta4PY%26export%3Ddownload) for more info about restrictions.


This trig snippet is an example snapshot of a knowlege base that contains a budget expressed using with g0v-ap:

```
...

:legge_bilancio_2018 a g0v:Dataset;
	dct:title       "2018 - DISEGNO LEGGE DI BILANCIO PRESENTATO ELABORABILE SPESE CAPITOLO"@it;
	dct:publisher   [ a foaf:Organization; foaf:homepage <http://www.mef.gov.it>; foaf:name "Minstero dell'Economia e delle Finanze"@it ] ;
	dct:issued      "2018-03-23"^^xsd:date;
	sdmx-dimension:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	sdmx-attribute:unitMeasure <http://dbpedia.org/resource/Eur> ;
	dcat:distribution [ a dcat:Distribution ;
		dcat:accessURL <https://bdap-opendata.mef.gov.it/opendata/spd_lbf_spe_elb_cap_01_2018> ;
		dcat:license <http://creativecommons.org/licenses/by/3.0>
	]
	.


[] a g0v:Budget;
	dct:subject :capitolo_02001200010001;
	qb:dataSet :legge_bilancio_2018 ;
	sdmx-measure:obsValue 288149000000.00		
	.

[] a g0v:Budget;
	dct:subject :capitolo_02001200020001;
	qb:dataSet :legge_bilancio_2018 ;
	sdmx-measure:obsValue 88149000000.00		
	.
```	

see a [complete sparql update example file](examples/data.update)

