//get account

module.exports = (id) => {
	return ({
	query : `
PREFIX dct: <http://purl.org/dc/terms/>
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>

CONSTRUCT { 
   ?bubbleUri a bgo:Account ;
		bgo:inBubbleGraph ?bubbleGraph;
		bgo:code ?code ;
		dct:title ?title ;
		dct:description ?description ;
		dct:subject ?subject ;
		dct:source ?fact ;
		bgo:amount ?amount ;
		bgo:version ?year;
		bgo:previousValue ?previousValue ;
		bgo:partitionLabel ?partitionLabel ;
  		bgo:isVersionOf ?historyRec ;
  		bgo:hasPart ?part .
     
    ?historyRec a bgo:VersionedAmount ;  
    	bgo:version ?historyVersion ; 
    	bgo:amount ?historyAmount .
    	
    ?part a bgo:PartialAmount ;
    	dct:title ?partTitle ; 
    	bgo:amount ?partAmount . 
    	
    ?bubbleGraph a bgo:BubbleGraph; bgo:um ?um .
} WHERE {
   ?bubbleUri a bgo:Account ;
		bgo:inBubbleGraph ?bubbleGraph;
		bgo:code ?code ;
		dct:title ?title ;
		dct:description ?description ;
		dct:subject ?subject ;
		dct:source ?fact ;
		bgo:amount ?amount ;	
		bgo:version ?year;
		bgo:partitionLabel ?partitionLabel.
  	
  	OPTIONAL { ?bubbleUri bgo:previousValue ?previousValue }
  	OPTIONAL { 
      ?bubbleUri bgo:isVersionOf ?historyRec .
      ?historyRec bgo:version ?historyVersion; bgo:amount ?historyAmount
    }
  	OPTIONAL { 
      ?bubbleUri bgo:hasPart ?part .
      ?part bgo:amount ?partAmount ; dct:title ?partTitle .
    }
  
    ?bubbleGraph bgo:um ?um.
    # FILTER (?code = "13a951ff5817943558e151b3a391b4ab")
    FILTER (?code = "${id}") 
}
`})
}
