//Default values
const DEFAULT_SCHEMA_ACCOUNTS = "bubbles",
	DEFAULT_SCHEMA_ACCOUNT = "full";


//Modules
const https = require('https'),
querystring = require('querystring');




exports.getAccounts = (req, res) => {
	let endpoint = "https://query.wikidata.org/sparql",
	query = require('../queries/example.js'),
	schema = req.params.schema;
	
	//Set schema
	schema = (schema === undefined) ? DEFAULT_SCHEMA_ACCOUNTS : schema;

	getQueryResult(endpoint, query).then((result) => {
		res.send(result);
	});
	// console.log("ekkelo");
	// let result = getQueryResult(endpoint, query);
	// console.log("ekkelo2");
	// res.send(result);
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
		//ONLY PROOF OF CONCEPT
		query.format='json';

		let url = endpoint + '?' + querystring.stringify(query);
		let result;
		const options = {
			method: 'GET'
		};
		const request = https.get(url, (res)=> {
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
  			console.error(`problem with request: ${e.message}`);
  			reject(e);
		});

		request.end();

	});
}


function buildJsonAccounts(meta, accounts, schema){
	return new Promise((resolve, reject) => {

	});
}