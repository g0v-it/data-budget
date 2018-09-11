//get accountsmeta
module.exports = {
	query : `PREFIX g0v: <http://data.budget.g0v.it/g0v-ap/v1#>
PREFIX interval: <http://reference.data.gov.uk/def/intervals/>
PREFIX dct: <http://purl.org/dc/terms/> 
PREFIX dcat: <http://www.w3.org/ns/dcat#> 
PREFIX dbpedia: <http://dbpedia.org/property/>
PREFIX time: <http://www.w3.org/2006/time#>
PREFIX : <http://data.budget.g0v.it/g0v-ap-api/v1#>

SELECT DISTINCT ?year ?um ?update ?source ?title ?description 
WHERE {
	?source a :ReferenceDataset ;
        dct:title ?title;
		dct:modified ?update ;
		g0v:refPeriod/time:hasBeginning/interval:ordinalYear ?year.

    OPTIONAL {?source dct:description ?description;}
	OPTIONAL {?source g0v:unit/dbpedia:isoCode ?um_from_dbpedia}
	BIND (COALESCE(?um_from_dbpedia, "") AS ?um)
} ORDER BY DESC(?year) LIMIT 1
`
}