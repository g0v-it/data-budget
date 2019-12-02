<?php
/**
 * Data Dictiornary for MEF Budget 
 * see https://bdap-opendata.mef.gov.it/sites/default/files/metadata_updfile/report/2670_Legge%20di%20Bilancio%20Pubblicata%20Elaborabile%20Spese_0.pdf
 */
namespace MEF\Model;

class NotaIntegrativaAzioni extends PianoDiGestione
{
      
    protected static $DEFAULT_OPTIONS  = array(
        'criteri' => array(
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\MEF\Filters::FILTER_SANITIZE_DEFINITION',
            'flags'  	=> FILTER_REQUIRE_SCALAR
		)
        
	);
	
	
	public function asTurtleFragment()
	{
	       
		if(is_null($this->rdf)) {
		    
		    $idAzione = sprintf('%sa%sm%sp%sa%s',
		        $this->data['budgetId'],
		        intval($this->data['codiceAmministrazione']),
		        intval($this->data['codiceMissione']),
		        intval($this->data['codiceProgramma']),
		        intval($this->data['codiceAzione'])
		    );

		    $this->addFragment("resource:$idAzione mef:criteri \"%s\"@it .", $this->data['criteri'], true);

		}

		return $this->rdf;
	}
}