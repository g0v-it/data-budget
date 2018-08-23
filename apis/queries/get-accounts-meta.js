//get accountsmeta
module.exports = {
	query : `PREFIX g0v: <http://data.budget.g0v.it/g0v-budget/v1#>
PREFIX interval: <http://reference.data.gov.uk/def/intervals/>
PREFIX dct:      <http://purl.org/dc/terms/> 
PREFIX dcat:      <http://www.w3.org/ns/dcat#> 
PREFIX qb:       <http://purl.org/linked-data/cube#> 
PREFIX sdmx-dimension:  <http://purl.org/linked-data/sdmx/2009/dimension#> 
PREFIX sdmx-measure:    <http://purl.org/linked-data/sdmx/2009/measure#> 
PREFIX sdmx-attribute:  <http://purl.org/linked-data/sdmx/2009/attribute#> 
PREFIX dbpedia: <http://dbpedia.org/property/>
PREFIX time: <http://www.w3.org/2006/time#>

SELECT  ?year ?um ?update ?source
WHERE {
	?budget a g0v:Budget; qb:dataSet ?legge_di_bilancio .
	?legge_di_bilancio dct:issued ?update ;
		sdmx-dimension:refPeriod/time:hasBeginning/interval:ordinalYear ?year;
		dcat:distribution/dcat:accessURL ?source.

	OPTIONAL {?legge_di_bilancio sdmx-attribute:unitMeasure/dbpedia:isoCode ?um_from_dbpedia}
	BIND (COALESCE(?um_from_dbpedia, "") AS ?um)
} ORDER BY DESC(?year) LIMIT 1
`
}