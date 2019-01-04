//get accounts
module.exports = {
	query : `
PREFIX dct: <http://purl.org/dc/terms/>
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>


CONSTRUCT { ?s ?p ?o } WHERE {
  {
  	?s a bgo:BubbleGraph ; ?p ?o
  }
  UNION {
    ?bgo a bgo:BubbleGraph ; bgo:partitionScheme ?s .
    ?s ?p ?o
  }
  UNION {
    ?bgo a bgo:BubbleGraph. ?bgo bgo:partitionScheme/bgo:partition ?s .
    ?s ?p ?o
  } 
  UNION {
    VALUES ?p { 
    	rdf:type 
    	bgo:code
    	dct:title
    	bgo:amount
    	bgo:previousValue
    	dct:subject
    	bgo:partitionLabel
    }
    ?s a bgo:Account; ?p ?o
  }
} 
`
}
