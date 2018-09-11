//get account

module.exports = (id) => {
	return ({
	query : `
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
PREFIX interval: <http://reference.data.gov.uk/def/intervals/>
PREFIX time: <http://www.w3.org/2006/time#>
PREFIX g0v: <http://data.budget.g0v.it/g0v-ap/v1#>
PREFIX : <http://data.budget.g0v.it/g0v-ap-api/v1#>

SELECT DISTINCT ?account ?code ?name ?description ?amount ?last_amount ?top_partition_label ?second_partition_label ?year ?history_amount ?fact_uri ?fact_label ?fact_amount
WHERE {
    ?account a :Account;
               :identifier ?code;
               :name ?name;
               :amount ?amount;
               :topPartitionLabel ?top_partition_label;
               :secondPartitionLabel ?second_partition_label.
               
   OPTIONAL {
   		?account :hasHistoryRec ?history_rec.
   		?history_rec :refPeriod/time:hasBeginning/interval:ordinalYear ?year;
   			:amount ?history_amount
   }          
               
   OPTIONAL {?account :last_amount ?last_amount}
   OPTIONAL {?account :description ?description}
  
   OPTIONAL {
     ?fact_uri a g0v:Fact; 
             g0v:isPartOf ?account;
             g0v:amount ?fact_amount;
             g0v:concept/skos:prefLabel ?fact_label
   }
  
   FILTER (?code = "${id}") 
} ORDER BY ?account DESC(?year)
`})
}

//module.exports = (id) => {
//	return ({
//	query : `PREFIX : <http://data.budget.g0v.it/g0v-ap-api/v1#>
//PREFIX interval: <http://reference.data.gov.uk/def/intervals/>
//PREFIX time: <http://www.w3.org/2006/time#>
//
//
//SELECT ?code ?name ?amount ?last_amount ?top_partition_label ?second_partition_label ?year ?history_amount
//WHERE {
//    ?account a :Account;
//               :code ?code;
//               :name ?name;
//          :amount ?amount;
//               :topPartitionLabel ?top_partition_label;
//               :secondPartitionLabel ?second_partition_label.
//               
//               
//   OPTIONAL { 
//      ?account_record a :AccountRecord;
//            :code ?code;
//                :year/time:hasBeginning/interval:ordinalYear ?year.
//    }
//    OPTIONAL {?account :last_amount ?last_amount}
//  
//  FILTER (?code = "${id}") 
//}ORDER BY DESC(?year)
//`})
//}
