# g0v-kb gateways

This project contains a gateway that transform the raw data provided by the Italian government (BDAP catalog) into linked data compliant with g0v-ap ontology:

Gateways are simple stand-alone php7 scripts that read a csv stream row by row from STDIN and write RDF turtle statements on STDOUT. 

The gateway requires as a mandatory parameters:

- a *dataset id* that is the name of a resource defined in the *http://data.budget.g0v.it/resource/name space 
-a *schema id* with the proper csv field mapping
    *schema id* is :
        1 for 'leggi di bilancio' from 2017
        2 for 'rendiconti' from 2017
        3 for 'rendiconti' before 2017