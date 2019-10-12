<?php

/**
 * A collection of custom filters to be used as callback with php data filtering functions
 *  - to allow empty values  if the data is invalid filter MUST returns null
 *  - to deny empty values if the data is invalid filter MUST returns false
 */
class Helper {
    
    const UNITS_REGEXP = '/(hundred|thousand|million|billion)/i';
    const ARTICOLI_REGEX = '/\b(il|lo|l\'|i|gli|la|le|un|uno|una|un\')\b/i'; // matches articoli determinativi e indeterminativi
    const PREPOSIZIONI_PROPRIE_REGEX = '/\b(di|a|da|in|con|su|per|tra|fra)\b/i'; // matches preposizioni proprie
    const PREPOSIZIONI_IMPROPRIE_REGEX = '/\b(davanti|dietro|dopo|fuori|lontano|lungo|mediante|prima|sopra|sotto)\b/i'; // matches preposizioni improprie
    const PREPOSIZIONI_ARTICOLATE_REGEX = '/\b(Del|Dello|Della|Dell\'|Dei|Degli|Delle|Al|Allo|Alla|Ai|Agli|Alle|All\'|Dal|Dallo|Dalla|dall\'|Dai|Dagli|Dalle|Nel|Nello|Nella|Nell\'|Nei|Negli|Nelle|Sul|Sullo|Sulla|Sull\'|Sui|Sugli|Sulle)\b/i';
 
    /**
     * questa funzione genera un codice univoco a partire da una stringa
     * numeri, spazi, segni di interpunzione e vocali sono rimossi
     * la stringa viene convertita in maiuscolo,
     * solo i rimanenti caratteri alfabetici sono significativi.
     *
     * La funzione viene computata per estrarre un identificativo da una descrizione testuale che può subire minime modifiche lessicali (da ignorare)
     */
    public static function STRING_SIGNATURE($string)
    {   
        // move to upper
        $string = strtoupper($string);                            
        
        // remove any abbreviations smaller than 2 caracter
        $string = preg_replace("/[a-z]{1-2}\./i",'', $string);
        
        // ignore attributes
        $string = preg_replace(self::ARTICOLI_REGEX,'', $string);
        $string = preg_replace(self::PREPOSIZIONI_PROPRIE_REGEX,'', $string);
        $string = preg_replace(self::PREPOSIZIONI_IMPROPRIE_REGEX,'', $string);
        $string = preg_replace(self::PREPOSIZIONI_ARTICOLATE_REGEX,'', $string);
        
        // remove other misleading short tockens
        $string = preg_replace('/\b(e|che)\b/i','', $string);

        
        // normalize separators
        $string = preg_replace('/[\s\'",;\.\-:\/]/', '_', $string);
        $string = preg_replace('^/^', '_', $string);
        $string = preg_replace('/__/', '_', $string);
        $string = trim($string,'_');
        
        // remove all non alphanumeric characters
        $string = preg_replace('/[^A-Z0-9_]/', '', $string);
        
        // remove vowels
        $string = preg_replace("/[AEIOU]*/",'', $string);
        
        
        // ignore numbers
        $string = preg_replace("/\b\d+\b/",'', $string);
        
        return  CRC32($string);
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