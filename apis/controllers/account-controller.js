//Default values
const DEFAULT_SCHEMA_ACCOUNTS = "bubbles",
	DEFAULT_SCHEMA_ACCOUNT = "full";


//Modules
const http = require('http'),
{URL} = require('url'),
csv = require('csvtojson'),
querystring = require('querystring');




exports.getAccounts = (req, res) => {
	let endpoint = "http://sdaas:8080/bigdata/sparql",
	query = require('../queries/get-accounts.js'),
	schema = req.params.schema;
	
	//Set schema
	schema = (schema === undefined) ? DEFAULT_SCHEMA_ACCOUNTS : schema;

	getQueryResult(endpoint, query).then(async (result) => {
		let output = await buildJsonAccountsList(result);
		res.send(output);
	})
	.catch((e) => console.error(`problem with request: ${e.message}`));
}

exports.getAccount = (req, res) => {
	let schema = req.params.schema,
		id = req.params.id;
	
	//Set schema
	schema = (schema === undefined) ? DEFAULT_SCHEMA_ACCOUNT : schema;

	console.log(schema + id);
	res.json("account");
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
		let output = await csv().fromString(data);
		output.map(account => {
			//Set new tags

			account.partition = {
				missione: account.missione,
				ministero: account.ministero
			}

			//remove old ones
			delete account.missione;
			delete account.ministero;
		});
		resolve(output);
	});
}