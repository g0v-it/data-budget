# g0v-kb gateways

This project contains some gateways that transform the raw data provided by the Italian government (BDAP catalog) into linked data compliant with g0v-ap ontology:

. a gateway to process csv file about the "legge di bilancio" with the format used from 2018.
. a gateway to process csv file about the "rendiconto di bilancio" with the format used from 2017.
. a gateway to process csv file about the "rendiconto di bilancio" with the format used from  2013-2016.

Gateway are simple stand-alone php7 scripts that read a csv stream row by row from STDIN and write RDF turtle statements on STDOUT. 

All gateways require as a mandatory parameter a dataset id that is the name of a resource defined in the *http://data.budget.g0v.it/resource/* name space and that represents a dcat:accessURL of a distribution of a source dataset.