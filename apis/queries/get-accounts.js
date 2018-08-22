//get accounts
module.exports = {
	query : `PREFIX : <urn:local:g0v:api:v1:>

SELECT ?code ?name ?amount ?last_amount ?grandParentLabel ?parentLabel 
WHERE {
	# compute ?last_amount.
	{
		SELECT ?code_history ( MAX (?year) AS ?last_year)
		WHERE {
		  ?account_record a :AccountRecord;
				:code ?code_history ;
				:year ?year             
		} GROUP BY ?code_history 
	}
	?account_record a :AccountRecord; :code ?code_history; :year ?last_year; :amount ?last_amount.

	?s a :Account.
	OPTIONAL { ?s :code ?code}
	OPTIONAL { ?s :name ?name}
	OPTIONAL { ?s :amount ?amount }
	OPTIONAL { ?s :last_amount ?last_amount }
	OPTIONAL { ?s :parentLabel ?parentLabel}
	OPTIONAL { ?s :grandParentLabel ?grandParentLabel}
		
	FILTER (?code = ?code_history) 
}
`
}



//PREFIX g0v: <http://data.budget.g0v.it/g0v-budget/v1>PREFIX xsd: <http://www.w3.org/2001/XMLSchema#> PREFIX skos:     <http://www.w3.org/2004/02/skos/core#> PREFIX dcat:      <http://www.w3.org/ns/dcat#> PREFIX dct:      <http://purl.org/dc/terms/> PREFIX foaf:     <http://xmlns.com/foaf/0.1/> PREFIX interval: <http://reference.data.gov.uk/def/intervals/> PREFIX qb:       <http://purl.org/linked-data/cube#> PREFIX sdmx-dimension:  <http://purl.org/linked-data/sdmx/2009/dimension#> PREFIX sdmx-measure:    <http://purl.org/linked-data/sdmx/2009/measure#> PREFIX sdmx-attribute:  <http://purl.org/linked-data/sdmx/2009/attribute#> PREFIX : <urn:temp:> SELECT  ?actionId ?action_name ?amount ?last_amount ?missione ?ministero WHERE { { SELECT ?actionId_history ?last_amount WHERE { ?s2 a :ActionRecord; :actionId ?actionId_history . ?s2 :year ?year . ?s2 :amount ?last_amount . } ORDER BY ?year } ?s a :Azione. OPTIONAL { ?s :actionId ?actionId} OPTIONAL { ?s :actionName ?action_name} OPTIONAL { ?s :amount ?amount } OPTIONAL { ?s :amount ?last_amount } OPTIONAL { ?s :missione ?missione} OPTIONAL { ?s :ministero ?ministero} FILTER (?actionId = ?actionId_history)
