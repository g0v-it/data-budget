g0v-data application profile (ontology)
---------------------------------------

g0v-ap is an [OWL ontology](https://www.w3.org/TR/owl2-primer/) used to annotate government budget data.  

It captures different perspectives of a general goverment budget data like historical trend, cross-department and component breakdown of tax by government.

The main concept in g0v-ap is the **account** that is an observation of a money amount in a reference period (e.g. year 2018) for a specific scope (e.g. the italian goverment) that it is linked to one or more hierarchical taxonomies ( i.e. the balance structures).

A "budget" is a collection of accounts for that share the same reference period and the same scope.

g0v-ap is an semantic web application profile that builds upon the following existing RDF vocabularies:: 

- the [W3C RDF Data Cube Vocabulary](https://www.w3.org/TR/vocab-data-cube) , to describe the balance accounts observations
- the [Data Catalog Vocabulary](https://www.w3.org/TR/vocab-dcat/) to describe the dataset metadata
- the [SKOS](https://www.w3.org/TR/skos-primer) to describe balance taxonomy.
- The [DCMI Metadata Terms](http://dublincore.org/documents/dcmi-terms/)
- some facilities from [sdmx ontologies] (https://sdmx.org/)
- the [g0v-budget Vocabulary](g0v-budget.rdf) to define some class and bindings

g0v-ap reuses  some reference linked open data provided by [UK e-gov](https://github.com/alphagov/datagovuk_reference) (mainly for reference period time intervals)

See the [g0v-ap UML diagram](https://www.draw.io/?lightbox=1&highlight=0000ff&edit=_blank&layers=1&nav=1&title=g0v-uml-diagram#Uhttps%3A%2F%2Fdrive.google.com%2Fa%2Fe-artspace.com%2Fuc%3Fid%3D1Qa_zoF1Nl8ULUg9uChN-OH3ep2Lta4PY%26export%3Ddownload) for more info about classes and restrictions.


This snippet is an example of a balance account  exprexed with g0v-ap:

```


:legge_bilancio_2018 a g0v:DataSet;
    dct:title       "2018 - DISEGNO LEGGE DI BILANCIO PRESENTATO ELABORABILE SPESE CAPITOLO"@it;
    dct:publisher   [ a foaf:Organization; foaf:homepage <http://www.mef.gov.it>; foaf:name "Minstero dell'Economia e delle Finanze"@it ] ;
    dct:issued      "2018-03-23"^^xsd:date;
    sdmx-attribute:unitMeasure <http://dbpedia.org/resource/Eur> ;
	dcat:distribution [ a dcatDistribution ;
		dcat:accessURL <https://bdap-opendata.mef.gov.it/opendata/spd_lbf_spe_elb_cap_01_2018> ;
		dcat:license <http://creativecommons.org/licenses/by/3.0>
	]
    .

:account_1 a g0v:SimpleAccountBudget;
	dct:subject dbap:azione_0200120001 ;
    qb:dataSet :legge_bilancio_2018 ;
	sdmx-dimension:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	smdx-measure:obsValue 288149000000.00		
    .

:account_2 a g0v:CompositeAccountBudget ;
	dct:subject dbap:azione_0200120002 ;
    qb:dataSet :legge_bilancio_2018 ;
	g0v:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y>;
	dct:hasPart 
		[ a g0v:AccountPartial g0v:subject dbap:capitolo_02001200020001 ; smdx-measure:obsValue 10000.00 ],
		[ a g0v:AccountPartial g0v:subject dbap:capitolo_02001200020002 ; smdx-measure:obsValue 30000.00 ]
    .

	
:account_3 a g0v:SimpleAccountRecord;
	dct:subject dbap:azione_0200120001 ;
    qb:dataSet :bilancio_2017 ;
	sdmx-dimension:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2017-01-01T00:00:00/P1Y>;
	smdx-measure:obsValue 288147000000.00 	
    .

	
:account_4 a g0v:CompositeAccountRecord ;
	dct:subject dbap:azione_0200120002 ;
    qb:dataSet :legge_bilancio_2018 ;
	sdmx-dimension:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2017-01-01T00:00:00/P1Y>;
	dct:hasPart 
		[ a g0v:AccountPartial g0v:subject dbap:capitolo_02001200020001 ; smdx-measure:obsValue 10000.00 ],
		[ a g0v:AccountPartial g0v:subject dbap:capitolo_02001200020002 ; smdx-measure:obsValue 30000.00 ]
    .

```	

