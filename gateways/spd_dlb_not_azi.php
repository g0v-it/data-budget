#!/usr/bin/env php
<?php
/**
 * Gateway per trasformare le note integrative azioni alla legge di bilancio formato csv a rdf g0v-ap
 *
 **/
require_once __DIR__.'/vendor/autoload.php';


isset($argv[1]) || die("Gateway usage: bdap {dataset url}");
\MEF\BDAP::parseBdapId($argv[1], $parsedSource) || die("unexpected source format $source");


$options = array(
	'factsProfile' => array(
	    'model'			=> '\\MEF\\Model\\NotaIntegrativaAzioni',
	    'modelOptions'		=> array(
	        'budgetId' => array( 'default'=> $parsedSource['budgetId'] )
	    ),
	    'datamapper'	=> function(array $rawdata) {
			$data = [];
			
			// see doc/SchemiBudget.xlsx
			$data['codiceAmministrazione'] = $rawdata[3]; //	STP
			$data['codiceMissione'] = $rawdata[5]; //	Missione
			$data['codiceProgramma'] = $rawdata[7]; //	Codice Programma
			$data['codiceAzione'] = $rawdata[9]; //	Codice Azione
			$data['criteri'] = $rawdata[11]; //	Criteri
			return $data;
	    },		
		// no data error allowed
		'entityThreshold'         => 1,
		'resilienceToErrors' 	  => 0.0 , 
		'resilienceToInsanes'	  => 0.0 , 
	),
	'fieldDelimiter' => ';',
	'bufferSize'     => 10000
);

BOTK\SimpleCsvGateway::factory($options)->run();
