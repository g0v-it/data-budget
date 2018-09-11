//get accounts
module.exports = {
	query : `
PREFIX : <http://data.budget.g0v.it/g0v-ap-api/v1#>

SELECT DISTINCT ?code ?name ?amount ?last_amount ?top_partition_label ?second_partition_label
WHERE {

	?s a :ReferenceAccount; :identifier ?code.
	OPTIONAL { ?s :name ?name}
	OPTIONAL { ?s :amount ?amount }
	OPTIONAL { ?s :last_amount ?last_amount }
	OPTIONAL { ?s :secondPartitionLabel ?second_partition_label}
	OPTIONAL { ?s :topPartitionLabel ?top_partition_label}

} ORDER BY DESC(?amount)
`
}
