<?php
/**
 * Data Dictiornary for MEF Budget 
 * see https://bdap-opendata.mef.gov.it/sites/default/files/metadata_updfile/report/2670_Legge%20di%20Bilancio%20Pubblicata%20Elaborabile%20Spese_0.pdf
 */
namespace MEF\Model;

class PianoDiGestione extends \BOTK\Model\AbstractModel implements \BOTK\ModelInterface  
{
    /**
     * known vocabularies
     */
    protected static $VOCABULARY  = array(
        'mef'            => 'http://w3id.org/g0v/it/mef#',
        'resource'       => 'http://mef.linkeddata.cloud/resource/'
    );

      
    protected static $DEFAULT_OPTIONS  = array(
        'budgetId'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d{2}[A-Z]$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'budgetType'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^(DisegnoLeggeBilancio|LeggeBilancio|AssestamentoBilancio|RendicontoBilancio)$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'esercizio'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,	
            'options' 	=> array('regexp'=>'/^20\d\d$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'unitaVotoLivello1'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d{2}$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'unitaVotoLivello2'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d{2}$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'codiceAmministrazione'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,	
            'options' 	=> array('regexp'=>'/^\d+$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'amministrazione' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'codiceCdS'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d{4}$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'capitoloSpesa' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'codicePdG'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'pianoGestione' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'codiceTitoloSpesa'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,	
            'options' 	=> array('regexp'=>'/^[1-3]$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'titoloSpesa' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'codiceCategoriaSpesa'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'categoriaSpesa' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'codiceMissione'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'missione' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'codiceProgramma'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'programma' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'codiceCdR'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'centroResponsabilita' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'codiceAzione'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'azione' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		), 
        'competenza'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+\.\d{2}$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'cassa'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+\.\d{2}$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        'residui'	=> array(
            'filter'    => FILTER_VALIDATE_REGEXP,
            'options' 	=> array('regexp'=>'/^\d+\.\d{2}$/'),
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
        
	);
	
	
	public function asTurtleFragment()
	{
	    static $notations = [];
	    
	       
		if(is_null($this->rdf)) {
		    
		    $budgetId = $this->data['budgetId'];
		    $budgetType = $this->data['budgetType'];
		    
		    // build notations
		    $notationSpesa = 's';
		    $notationAmministrazione = 'a' . intval($this->data['codiceAmministrazione']) ;
		    $notationMissione = 'm' . intval($this->data['codiceMissione']) ;
		    $notationMissioneMinistero = $notationAmministrazione . $notationMissione ;
		    $notationProgramma = $notationMissioneMinistero . 'p' . intval($this->data['codiceProgramma']) ;
		    $notationAzione = $notationProgramma . 'a' . intval($this->data['codiceAzione']) ;
		    $notationCdR = $notationAmministrazione . 'r' . intval($this->data['codiceCdR']) ;
		    $notationCdS = $notationAmministrazione . 'c' . intval($this->data['codiceCdS']) ;
		    $notationPdG = $notationCdS . 'p' . intval($this->data['codicePdG']) ;
		    $notationTitoloSpesa = 's' . intval($this->data['codiceTitoloSpesa']) ;
		    $notationCategoriaSpesa = $notationTitoloSpesa .'c' . intval($this->data['codiceCategoriaSpesa']) ;

		    $this->addFragment('resource:%s a mef:PianoDiGestione ;', $budgetId . $notationPdG, false);
		    $this->addFragment(  'mef:codice "%s" ;', $this->data['codicePdG'], false);
		    $this->addFragment(  'mef:notation "%s" ;', $notationPdG, false);
		    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			$this->addFragment(  'mef:competenza %s ;', $this->data['competenza'], false);
			$this->addFragment(  'mef:cassa %s ;', $this->data['cassa'], false);
			$this->addFragment(  'mef:residui %s ;', $this->data['residui'], false);
			$this->addFragment(  'mef:definition "%s"@it ;', $this->data['pianoGestione'], true);
			$this->addFragment(  'mef:isPartOf resource:%s .', $budgetId . $notationCdS, false);
			
			if (!isset($notations[$notationCdS])) {
			    $notations[$notationCdS] = true;
			    $this->addFragment('resource:%s a mef:CapitoloDiSpesa ;', $budgetId . $notationCdS, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceCdS'], false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationCdS, false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['capitoloSpesa'], true);
			    $this->addFragment(  'mef:isPartOf resource:%s ,', $budgetId . $notationAzione, false);
			    $this->addFragment(      'resource:%s . ', $budgetId . $notationCategoriaSpesa, false);
			}
			
			if (!isset($notations[$notationCategoriaSpesa])) {
			    $notations[$notationCategoriaSpesa] = true;
			    $this->addFragment('resource:%s a mef:CategoriaSpesa ;', $budgetId . $notationCategoriaSpesa, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceCategoriaSpesa'], false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationCategoriaSpesa, false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['categoriaSpesa'], true);
			    $this->addFragment(  'mef:isPartOf resource:%s . ', $budgetId . $notationTitoloSpesa, false);
			}
			
			if (!isset($notations[$notationTitoloSpesa])) {
			    $notations[$notationTitoloSpesa] = true;
			    $this->addFragment('resource:%s a mef:TitoloSpesa ;', $budgetId . $notationTitoloSpesa, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceTitoloSpesa'], false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationTitoloSpesa, false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['titoloSpesa'], true);
			    $this->addFragment(  'mef:isPartOf resource:%s . ', $budgetId . $notationSpesa, false);
			}
			
			if (!isset($notations[$notationAzione])) {
			    $notations[$notationAzione] = true;
			    $this->addFragment('resource:%s a mef:Azione ;', $budgetId . $notationAzione, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceAzione'], false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationAzione, false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['azione'], true);
			    $this->addFragment(  'mef:isPartOf resource:%s . ', $budgetId . $notationProgramma, false);
			}
			
			if (!isset($notations[$notationProgramma])) {
			    $notations[$notationProgramma] = true;
			    $this->addFragment('resource:%s a mef:Programma ;', $budgetId . $notationProgramma, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceProgramma'], false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationProgramma, false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:codiceUnitaVoto "%s" ;', $this->data['codiceAmministrazione']. $this->data['unitaVotoLivello1'] .$this->data['unitaVotoLivello2'] , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['programma'], true);
			    $this->addFragment(  'mef:isPartOf resource:%s , ', $budgetId . $notationMissioneMinistero, false);
			    $this->addFragment(      'resource:%s . ', $budgetId . $notationMissione, false);
			}
			
			if (!isset($notations[$notationMissioneMinistero])) {
			    $notations[$notationMissioneMinistero] = true;
			    $this->addFragment('resource:%s a mef:MissioneMinistero ;', $budgetId . $notationMissioneMinistero, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceMissione'], false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationMissioneMinistero, false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['missione'], true);
			    $this->addFragment(  'mef:codiceUnitaVoto "%s" ;', $this->data['codiceAmministrazione']. $this->data['unitaVotoLivello1'] , false);
			    $this->addFragment(  'mef:isPartOf resource:%s ,', $budgetId . $notationMissione, false);
			    $this->addFragment(      'resource:%s ,', $budgetId . $notationCdR, false);
			    $this->addFragment(      'resource:%s . ', $budgetId . $notationAmministrazione, false);
			}
			
			if (!isset($notations[$notationMissione])) {
			    $notations[$notationMissione] = true;
			    $this->addFragment('resource:%s a mef:Missione ;', $budgetId . $notationMissione, false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationMissione, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceMissione'], false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['missione'], true);
			    $this->addFragment(  'mef:isPartOf resource:%s . ', $budgetId . $notationSpesa, false);
			}
			
			if (!isset($notations[$notationCdR])) {
			    $notations[$notationCdR] = true;
			    $this->addFragment('resource:%s a mef:CentroResponsabilita ;', $budgetId . $notationCdR, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceCdR'], false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationCdR, false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['centroResponsabilita'], true);
			    $this->addFragment(  'mef:isPartOf resource:%s . ', $budgetId . $notationAmministrazione, false);
			}
			
			if (!isset($notations[$notationAmministrazione])) {
			    $notations[$notationAmministrazione] = true;
			    $this->addFragment('resource:%s a mef:Ministero ;', $budgetId . $notationAmministrazione, false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationAmministrazione, false);
			    $this->addFragment(  'mef:codice "%s" ;', $this->data['codiceAmministrazione'], false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it ;', $this->data['amministrazione'], true);
			    $this->addFragment(  'mef:isPartOf resource:%s . ', $budgetId . $notationSpesa, false);
			}
			
			if (!isset($notations[$notationSpesa])) {
			    $notations[$notationSpesa] = true;
			    $this->addFragment('resource:%s a mef:Spesa ;', $budgetId . $notationSpesa, false);
			    $this->addFragment(  'mef:notation "%s" ;', $notationSpesa, false);
			    $this->addFragment(  'mef:inBudget resource:%s ;', $budgetId , false);
			    $this->addFragment(  'mef:definition "%s"@it . ', "spese", true);
			}
			
			if (!isset($notations[$budgetId])) {
			    $notations[$budgetId] = true;
			    $this->addFragment( "resource:$budgetId a mef:%s ;", $budgetType, false);
			    $this->addFragment(  'mef:versionId "%s"^^mef:BudgetVersion ;', $budgetId, false);
			    $this->addFragment(  'mef:spese resource:%s ;', $budgetId . $notationSpesa, false);
			    $this->addFragment(  'mef:esercizio %s .',  $this->data['esercizio'], false);
			}
		}

		return $this->rdf;
	}
}
