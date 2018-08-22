//Files
const config = require('../config.js');

//Default values
const DEFAULT_SCHEMA_ACCOUNTS = "bubbles",
	DEFAULT_SCHEMA_ACCOUNT = "full";


//Modules
const http = require('http'),
{URL} = require('url'),
csv = require('csvtojson'),
querystring = require('querystring');


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


/**
	* @endpoint must a be a complete path (e.s. https://query.wikidata.org/sparql)
	* @query must be an object (e.s. {query : "this is a query" })
*/
function getQueryResult(endpoint, query){
	return new Promise((resolve, reject) => {
		let url = new URL(endpoint),
		result,
		options = {
			host: url.hostname,
			port: url.port,
			path: url.pathname,
			method: 'POST',
			headers: {
          		'Accept': 'text/csv',
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
			let json, output;

			json = await csv().fromString(data);
			output = json[0];

			output.past_values= {};
			output.partitions = {};

			json.map(account => {
				output.past_values[account.year] = account.history_amount;
			});

			output.partitions = {
				top_partition: output.top_partition_label,
				second_partition: output.second_partition_label
			}
			
			output.top_level =  output.top_partition_label;
			
			//remove old ones
			delete output.history_amount;
			delete output.year;
			delete output.top_partition_label;
			delete output.second_partition_label;
			resolve(output);
		
		}catch (e){
			reject(e);
		}
		
	});
}





