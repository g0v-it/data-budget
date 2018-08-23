———2018————
cat datalake/bdap/2018---Legge-di-Bilancio-Pubblicata-Elaborabile-Spese-Capitolo.csv | php ./gateways/bdap-legge-di-bilancio.php legge_bilancio_2018 > ./data/2018-Legge-di-Bilancio-Pubblicata-Elaborabile-Spese-Capitolo.ttl


———2017————
cat datalake/bdap/2017---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.csv |  php ./gateways/bdap-rendiconto.php rendiconto_2017 > ./data/2017-Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.ttl


———2016————
cat datalake/bdap/2016---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.csv | php ./gateways/bdap-rendiconto-special.php rendiconto_2016 > ./data/2016---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.ttl


———2015————
cat datalake/bdap/2015---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.csv | php ./gateways/bdap-rendiconto-special.php rendiconto_2015 > ./data/2015---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.ttl

———2014————
cat datalake/bdap/2014---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.csv | php ./gateways/bdap-rendiconto-special.php rendiconto_2014 > ./data/2014---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.ttl


———2013————
cat datalake/bdap/2013---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.csv | php ./gateways/bdap-rendiconto-special.php rendiconto_2013 > ./data/2013---Rendiconto-Pubblicato-Elaborabile-Spese-Capitolo.ttl