//Files
const config = require('../config.js');

//Default values
const DEFAULT_SCHEMA_ACCOUNTS = "bubbles",
	DEFAULT_SCHEMA_ACCOUNT = "full",
	DEFAULT_ACCEPT = "text/csv";

const topPartition = "top_partition_label"
secondPartition = "second_partition_label";


//Modules
const http = require('http'),
{URL} = require('url'),
csv = require('csvtojson'),
querystring = require('querystring');

//#######################################GET_ROUTES################################################
exports.getAccounts = async (req, res) => {
	let queryAccounts, queryAccountsMeta, schema, accountsJson, metaJson, outputJson;

	//Fetch Queries
	queryAccounts = require('../queries/get-accounts.js');
	queryAccountsMeta = require('../queries/get-accounts-meta.js');
	
	//Set schema
	schema = req.params.schema;
	schema = (schema === undefined) ? DEFAULT_SCHEMA_ACCOUNTS : schema;

	accountsJson = await buildJsonAccountsList(
		await getQueryResult(config.endpoint, queryAccounts));
	metaJson = await csv().fromString(
		await getQueryResult(config.endpoint, queryAccountsMeta));
	
	//Build OutputJson
	outputJson = {};

	outputJson.meta = metaJson[0];
	outputJson.accounts = accountsJson;
	res.json(outputJson);
}

exports.getAccount = async (req, res) => {
	let queryAccount, schema, outputJson;
	
	//Fetch Queries
	queryAccount = require('../queries/get-account.js')(req.params.id);

	//Set schema
	schema = req.params.schema;
	schema = (schema === undefined) ? DEFAULT_SCHEMA_ACCOUNT : schema;

	outputJson = await buildJsonAccount(await getQueryResult(config.endpoint, queryAccount));
	res.json(outputJson);
}


exports.getPartitionLabels =  async (req, res) => {
	//Variables
	let queriesPartitionLabels, topPartitionLabelsJson, secondPartitionLabelsJson, outputJson;

	queriesPartitionLabels = require('../queries/get-partition-labels.js');
	
	//Get the lables
	topPartitionLabelsJson = await csv().fromString(
		await getQueryResult(config.endpoint, queriesPartitionLabels.top_partition_labels));
 	secondPartitionLabelsJson = await csv().fromString(
		await getQueryResult(config.endpoint, queriesPartitionLabels.second_partition_labels));

 	//Build the json
 	outputJson = {};
 	outputJson.top_partition = topPartitionLabelsJson;
 	outputJson.second_partition = secondPartitionLabelsJson;

 	res.json(outputJson);
}

exports.getStats = async (req, res) => {
	let queryStats, result;

	queryStats = require('../queries/get-stats.js');
	result = await getQueryResult(config.endpoint, queryStats, "application/json");

	res.send(result);

}


//#######################################POST_ROUTES#################################################
exports.filter = async (req, res) => {
	let topQueryFilter, secondQueryFilter, result, top_filter, second_filter,
	filter = req.body;


	result = {};
	//Params
	let top_partition = filter.top_partition.join('|'),
	second_partition = filter.second_partition.join('|');

	//Get queries
	topQueryFilter = require('../queries/filter.js')(top_partition, second_partition, topPartition);
	secondQueryFilter = require('../queries/filter.js')(top_partition, second_partition, secondPartition);

	//get Top and second filter
	top_filter = await buildJsonFilter(await getQueryResult(config.endpoint, topQueryFilter), topPartition);
	second_filter = await buildJsonFilter(await getQueryResult(config.endpoint, secondQueryFilter), secondPartition);

	//output
	result[topPartition] = top_filter;
	result[secondPartition] = second_filter;
	res.send(result);
}

exports.getFilter = async (req, res) => {
	let topQueryFilter, secondQueryFilter, result, top_filter, second_filter,
	filter = req.body;


	result = {};
	//Params
	let top_partition = filter.top_partition.join('|'),
	second_partition = filter.second_partition.join('|');

	//Get queries
	topQueryFilter = require('../queries/filter.js')(top_partition, second_partition, topPartition);
	secondQueryFilter = require('../queries/filter.js')(top_partition, second_partition, secondPartition);

	//get Top and second filter
	top_filter = await buildJsonFilter(await getQueryResult(config.endpoint, topQueryFilter), topPartition);
	second_filter = await buildJsonFilter(await getQueryResult(config.endpoint, secondQueryFilter), secondPartition);

	//output
	result[topPartition] = top_filter;
	result[secondPartition] = second_filter;
	res.send(result);
}


/**
	* @endpoint must a be a complete path (e.s. https://query.wikidata.org/sparql)
	* @query must be an object (e.s. {query : "this is a query" })
*/
function getQueryResult(endpoint, query, format = DEFAULT_ACCEPT){
	return new Promise((resolve, reject) => {
		//set Format
		//format = (format === undefined) ? DEFAULT_ACCEPT : format;

		let url = new URL(endpoint),
		result,
		options = {
			host: url.hostname,
			port: url.port,
			path: url.pathname,
			method: 'POST',
			headers: {
          		'Accept': format,
          		'Content-Type': 'application/x-www-form-urlencoded'
      		}
		};

		query = querystring.stringify(query);

		const request = http.request(options, (res)=> {
			result = ""; //inizialize
			res.on('data', (chunk) => {
				result += chunk;
			});

			res.on('end', ()=> {
				console.log('No more data in response');
				resolve(result);
			});

			res.on('error', (e) => {
  				console.error(`problem with request: ${e.message}`);
				reject(e);
			});
		});

		request.on('error', (e) => {	
  			reject(e);
		});

		request.write(query);
		request.end();

	});
}


async function buildJsonAccountsList(data){
	return new Promise(async (resolve, reject) =>{
		try{
			let output = await csv().fromString(data);
			output.map(account => {
				//Set new tags
				//top_partition_label second_partition_label
				account.partitions = {
					top_partition: account.top_partition_label,
					second_partition: account.second_partition_label
				}
				account.amount = parseFloat(account.amount);
				account.last_amount = parseFloat(account.last_amount);
				account.top_level = account.top_partition_label;
				//remove old ones
				delete account.top_partition_label;
				delete account.second_partition_label;
			});
			resolve(output);
			
		}catch (e){
			reject(e);
		}
	});
}

async function buildJsonAccount(data){
	return new Promise(async (resolve, reject) =>{
		try{
			let json, output, singleCds, put;

			json = await csv().fromString(data);
			output = json[0];

			output.past_values= {};
			output.partitions = {};
			output.cds = [];

			json.map((account) => {
				output.past_values[account.year] = parseFloat(account.history_amount);
				singleCds = {
					name: account.fact_label,
					amount: parseFloat(account.fact_amount),
				}
				put = containsObject(singleCds, output.cds);
				if(!put) {
					output.cds.push(singleCds);	
    			}
			});

			output.partitions = {
				top_partition: output.top_partition_label,
				second_partition: output.second_partition_label
			}
			output.amount = parseFloat(output.amount);
			output.last_amount = parseFloat(output.last_amount);
			output.top_level =  output.top_partition_label;
			
			//remove old ones
			delete output.history_amount;
			delete output.year;
			delete output.top_partition_label;
			delete output.second_partition_label;

			//remove capitoli di spesa
			delete output.fact_uri;
			delete output.fact_label;
			delete output.fact_amount;


			resolve(output);
		
		}catch (e){
			reject(e);
		}	
	});
}

function containsObject(obj, list) {
	for (let i = 0; i < list.length; i++) {
		if(list[i].name.localeCompare(obj.name) == 0){
			return true;
		}
	}
    return false;
}

/**
	* @group must be one of the const values @topPartition @secondPartition
*/
async function buildJsonFilter(data, group){
	return new Promise(async (resolve, reject) =>{
		try{
			let output, result = await csv().fromString(data);
			
			output = {};
			result.map(d => {
				output[d[group]] = d.amount;
			})

			resolve(output);
			
		}catch (e){
			reject(e);
		}
	});
}
