//get filter

module.exports = (first, second,  groupBy) => {
	return ({
		query : `
		PREFIX dct: <http://purl.org/dc/terms/> 
		PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
		PREFIX accounts: <https://data.budget.g0v.it/ldp/accounts#>
		PREFIX resource: <http://mef.linkeddata.cloud/resource/>
		PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
		PREFIX fr: <http://linkeddata.center/botk-fr/v1#>

		SELECT ?${groupBy} (SUM (?account_amount) AS ?amount)
		WHERE {
		  {
		  	SELECT DISTINCT ?accountUri ?p1_ministero ?p2_missione ?account_amount 
		    WHERE  {
		      ?accountUri a bgo:Account ;
		                bgo:amount ?account_amount ;
		                bgo:partitionLabel ?p1_ministero ;
		                bgo:partitionLabel ?p2_missione  .
		  	
		      accounts:ministero bgo:partition ?ministero .
		      accounts:missione bgo:partition ?missione .

		      ?ministero bgo:label ?p1_ministero .
		      ?missione bgo:label ?p2_missione .
		    }
		  }
		  
		  FILTER regex(?p1_ministero, "${first}")
		  FILTER regex(?p2_missione , "${second}")
		} GROUP BY ?${groupBy} ORDER BY (?amount)
		`
})
}

