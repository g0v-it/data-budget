#!/usr/bin/env php
<?php
/**
 * Gateway per trasformare i disegni di legge di bilancio formato csv a rdf g0v-ap
 *
 **/
require_once __DIR__.'/vendor/autoload.php';


isset($argv[1]) || die("Gateway usage: bdap {dataset url}");
\MEF\BDAP::parseBdapId($argv[1], $parsedSource) || die("unexpected source format $source");


$options = array(
	'factsProfile' => array(
	    'model'			=> '\\MEF\\Model\\PianoDiGestione',
	    'modelOptions'		=> array(
	        'budgetId' => array( 'default'=> $parsedSource['budgetId'] ),
	        'budgetType' => array( 'default'=> \MEF\BDAP::stateId2MefType($parsedSource['stateId']) )
	    ),
	    'datamapper'	=> function(array $rawdata){
			$data = [];
			
			// see doc/SchemiBudget.xlsx
			$data['esercizio'] = $rawdata[0]; //	Esercizio Finanziario
			$data['codiceAmministrazione'] = $rawdata[1]; //	Stato di Previsione
			$data['amministrazione'] = $rawdata[2]; //	Amministrazione
			$data['unitaVotoLivello1'] = $rawdata[3];  // 	Unità di voto 1° livello
			$data['unitaVotoLivello2'] = $rawdata[4]; // Unità di voto 2° livello
			$data['codiceCdS'] = $rawdata[5]; //	Numero Capitolo di Spesa
			$data['capitoloSpesa'] = $rawdata[6]; //	Capitolo di Spesa
			$data['codicePdG'] = $rawdata[7]; //	Numero Piano di Gestione
			$data['pianoGestione'] = $rawdata[8]; //	Piano di Gestione
			$data['codiceTitoloSpesa'] = $rawdata[9]; //	Codice Titolo
			$data['titoloSpesa'] = $rawdata[10]; //	Titolo
			$data['codiceCategoriaSpesa'] = $rawdata[11]; //	Codice Categoria
			$data['categoriaSpesa'] = $rawdata[12]; //	Categoria
			$data['codiceMissione'] = $rawdata[17]; //	Codice Missione
			$data['missione'] = $rawdata[18]; //	Missione
			$data['codiceProgramma'] = $rawdata[19]; //	Codice Programma
			$data['programma'] = $rawdata[20]; //	Programma
			$data['codiceCdR'] = $rawdata[21]; //	Codice Centro Responsabilità
			$data['centroResponsabilita'] = $rawdata[22]; //	Centro Responsabilità
			$data['codiceAzione'] = $rawdata[23]; //	Codice Azione
			$data['azione'] = $rawdata[24]; //	Azione
			$data['competenza'] = $rawdata[57]; //	DLB Integrato CP A1
			$data['cassa'] = $rawdata[60]; //	DLB Integrato CS A1
			$data['residui'] = $rawdata[63]; //	DLB Integrato RS A1
			
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
