//get account

module.exports = (top_partition_label, second_partition_label, group_label) => {
	return ({
		query : `
		PREFIX : <urn:local:g0v:api:v1:>
		SELECT ?${group_label} (SUM (?account_amount) AS ?amount)
		WHERE {
     		?accountUri a :ReferenceAccount;
                   :amount ?account_amount;
                   :topPartitionLabel ?top_partition_label;
                   :secondPartitionLabel ?second_partition_label.
     
  			FILTER regex(?top_partition_label, "${top_partition_label}")
  			FILTER regex(?second_partition_label, "${second_partition_label}")
		} GROUP BY ?${group_label} 
	`})
}

