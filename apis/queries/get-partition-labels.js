module.exports = {
	top_partition_labels: {
		query : `PREFIX : <http://data.budget.g0v.it/g0v-ap-api/v1#>
SELECT ?top_partition ?amount
WHERE {
  ?accountUri a :AccountTopPartition;
                :amount ?amount;
                :label ?top_partition;
}ORDER BY DESC(?amount)`
	},

	second_partition_labels: {
		query : `PREFIX : <http://data.budget.g0v.it/g0v-ap-api/v1#>
SELECT ?second_partition ?amount
WHERE {
  ?accountUri a :AccountSecondPartition;
                :amount ?amount;
                :label ?second_partition;
}ORDER BY DESC(?amount)`
	}
}