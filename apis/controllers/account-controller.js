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
	let queryAccounts = require('../queries/get-accounts.js'),
	queryAccountsMeta = require('../queries/get-accounts-meta.js');
	
	schema = req.params.schema;
	
	//Set schema
	schema = (schema === undefined) ? DEFAULT_SCHEMA_ACCOUNTS : schema;

	let accounts = await buildJsonAccountsList(
		await getQueryResult(config.endpoint, queryAccounts));
	let meta = await csv().fromString(
		await getQueryResult(config.endpoint, queryAccountsMeta));
	
	//Build Json
	let output = {};

	output.meta = meta;
	output.accounts = accounts;
	res.send(output);
}

exports.getAccount = async (req, res) => {
	let schema = req.params.schema,
		id = req.params.id;
	
	let query = require('../queries/get-account.js')(id);

	//Set schema
	schema = (schema === undefined) ? DEFAULT_SCHEMA_ACCOUNT : schema;

	let account = await buildJsonAccount(await getQueryResult(config.endpoint, query));
	res.json(account);
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

				account.partitions = {
					ministero: account.grandParentLabel,
					misisone: account.parentLabel
				}
				account.top_level = account.grandParentLabel;
				
				//remove old ones
				delete account.grandParentLabel;
				delete account.parentLabel;
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
			let json = await csv().fromString(data);
			let output = {};
			output.past_values= {};

			json.map(account => {
				output.past_values[account.year] = account.amount;
			});
			resolve(output);
		}catch (e){
			reject(e);
		}
		
	});
}



