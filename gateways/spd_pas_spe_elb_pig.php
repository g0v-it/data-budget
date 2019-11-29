#!/usr/bin/env php
<?php
/**
 * Gateway per trasformare i disegni di legge di bilancio formato csv a rdf g0v-ap
 *
 **/
require_once __DIR__.'/vendor/autoload.php';


isset($argv[1]) || die("Gateway usage: bdap {dataset url}");
$source=$argv[1];


$options = array(
	'factsProfile' => array(
		'model'			=> '\\MEF\\Model\\PianoDiGestione',
	    'datamapper'	=> function(array $rawdata) use ($source){
			$data = [];
			$data['source'] = $source;
			
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
			$data['codiceCdR'] = $rawdata[23]; //	Codice Centro Responsabilità
			$data['centroResponsabilita'] = $rawdata[24]; //	Centro Responsabilità
			$data['codiceAzione'] = $rawdata[25]; //	Codice Azione
			$data['azione'] = $rawdata[26]; //	Azione
			$data['residui'] = $rawdata[36]; //	Previsioni Assestate RS A1
			$data['competenza'] = $rawdata[37]; //	Bilancio Assestato CP A1
			$data['cassa'] = $rawdata[38]; //	Bilancio Assestato CP A1
			
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
