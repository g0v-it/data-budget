<?php

/**
 * A collection of custom filters to be used as callback with php data filtering functions
 *  - to allow empty values  if the data is invalid filter MUST returns null
 *  - to deny empty values if the data is invalid filter MUST returns false
 */
class Helper {
    
    const UNITS_REGEXP = '/(hundred|thousand|million|billion)/i';

    public static function getAltLabel($label){
        $arrayMinisteri = array(
            "MINISTERO DEGLI AFFARI ESTERI E DELLA COOPERAZIONE INTERNAZIONALE" => "Affari Esteri",
            "MINISTERO DEI BENI E DELLE ATTIVITA' CULTURALI E DEL TURISMO" => "Beni e attività culturali e Turismo",
            "MINISTERO DEL LAVORO E DELLE POLITICHE SOCIALI" => "Lavoro e Politiche Sociali",
            "MINISTERO DELLA DIFESA" => "Difesa",
            "MINISTERO DELLA GIUSTIZIA" => "Giustizia",
            "MINISTERO DELLA SALUTE" => "Salute",
            "MINISTERO DELL'AMBIENTE E DELLA TUTELA DEL TERRITORIO E DEL MARE" => "Ambiente e Territorio",
            "MINISTERO DELLE INFRASTRUTTURE E DEI TRASPORTI" => "Infrastrutture e Trasporti",
            "MINISTERO DELLE POLITICHE AGRICOLE ALIMENTARI E FORESTALI" => "Politiche Agricole Alimentari",
            "MINISTERO DELL'ECONOMIA E DELLE FINANZE" => "Economia e Finanze",
            "MINISTERO DELL'INTERNO" => "Interno",
            "MINISTERO DELL'ISTRUZIONE, DELL'UNIVERSITA' E DELLA RICERCA" => "Istruzione",
            "MINISTERO DELLO SVILUPPO ECONOMICO" => "Sviluppo Economico"
        );
        return $arrayMinisteri[$label] ? $arrayMinisteri[$label] : $label;
    }

    public static function getUri($string){
        $string = strtoupper($string);
        $string = preg_replace('/\s+/', '', $string);
        $string = preg_replace('/[^A-Za-z0-9]/', '', $string);
        $uri = md5($string);
        return $uri;
    }

        /**
     * escape double quotes, backslash and new line
     * empty allowed
     */
    public static function FILTER_SANITIZE_TURTLE_STRING($value)
    {
        $value = preg_replace('/\\\\/', '\\\\\\\\', $value);    // escape backslash
        $value = preg_replace('/\r?\n|\r/', '\\n', $value);  // newline 
        $value = preg_replace('/"/', '\\"', $value);        // escape double quote
        
        return $value?:null;
    }
    
}