<?php
/**
 * Some helpes for BDAP 
 */
namespace MEF;

class BDAP  
{
    const CKAN_DATASET = 'https://bdap-opendata.mef.gov.it/SpodCkanApi/api/1/rest/dataset/';
    const CONTENT_PREFIX = 'https://bdap-opendata.mef.gov.it/content/';
    
    public static function stateId2MefType($stateId)
    {
        switch ($stateId) {
            case 'D': $type='DisegnoLeggeBilancio'; break;
            case 'L': $type='LeggeBilancio'; break;
            case 'P': $type='AssestamentoBilancio'; break;
            case 'R': $type='RendicontoBilancio'; break;
            default: throw new \Exception("unexpected id format $budgetId");break;
        }
        
        return $type ;
    }
        
    
    public static function bdapType2StateId($bdapType)
    {
        switch ($bdapType) {
            case 'lbf': $stateId='L'; break;
            case 'dlb': $stateId='D'; break;
            case 'pas': $stateId='P'; break;
            case 'rnd': $stateId='R'; break;
            default: throw new \Exception("unexpected bdap type format $bdapType");break;
        }
        
        return $stateId ;
    }
    
    /**
     * Parse a string with the format
     * ...<type>_<area>_<entity>_<object>_<version>_20<year>
     * it generate an additional parsed group:
     * 
     *   stateId
     *   budgetId
     * 
     * @return same interface as preg_match
    */
    public static function parseBdapId($uri, &$matches)
    {
        $return=preg_match('/spd_(?<type>lbf|rnd|pas|dlb)_(?<area>spe|ent|not)_(?<entity>elb|azi)_(?<object>pig|azioni)_(?<version>01)_20(?<year>\d{2})(?<extension>\.csv)?$/', $uri, $matches);
        if ($return) {
            $matches['stateId'] = self::bdapType2StateId($matches['type']);
            $matches['budgetId'] = $matches['year'].$matches['stateId'];
        }
        
        return $return;
    }
    
    public static function titleToAccessUrl($title)
    {
        return self::CONTENT_PREFIX. str_replace ('_','-', \BOTK\Filters::FILTER_SANITIZE_ID($title));
    }
}