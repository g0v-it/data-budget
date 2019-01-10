//Files
const config = require('../config.js'),
rdflib = require('./rdflib.js');
//Default values
const DEFAULT_SCHEMA_ACCOUNTS = "bubbles",
	DEFAULT_SCHEMA_ACCOUNT = "full",
	DEFAULT_ACCEPT = "text/turtle";

const partition1 = "p1_ministero",
partition2 = "p2_missione" ;

//Modules
//const http = require('http'),

const {URL} = require('url'),
csv = require('csvtojson'),
zip = require('lz-string'),
querystring = require('querystring');

//#######################################GET_ROUTES################################################
exports.getAccounts = async (req, res) => {
	let queryAccounts, accountsJson;
	queryAccounts = require('../queries/get-accounts.js');
	accountsJson = await rdflib.parseAccounts(await getQueryResult(config.endpoint, queryAccounts), DEFAULT_ACCEPT);
	res.json(accountsJson);
}

exports.getAccount = async (req, res) => {
	let queryAccount, outputJson;
	queryAccount = require('../queries/get-account.js')(req.params.id);
	outputJson = await rdflib.parseAccount(await getQueryResult(config.endpoint, queryAccount), DEFAULT_ACCEPT);
	res.json(outputJson);
}


exports.getStats = async (req, res) => {
	let queryStats, result;

	queryStats = require('../queries/get-stats.js');
	result = await getQueryResult(config.endpoint, queryStats, "application/json");

	res.send(result);

}

exports.getFilter = async (req, res) => {
	let filters = req.params.filters;
	filters = JSON.parse(zip.decompressFromBase64(filters));
	//Prepare filters
	let filter1 = filters[partition1].join('|');
	let filter2 = filters[partition2].join('|');

	//prepare queries
	let query1 = require('../queries/filter.js')(filter1, filter2, partition1);
	let query2 = require('../queries/filter.js')(filter1, filter2, partition2);
	//prepare data
	let object1 = await buildJsonFilter(await getQueryResult(config.endpoint, query1, 'text/csv'), partition1);
	let object2 = await buildJsonFilter(await getQueryResult(config.endpoint, query2, 'text/csv'), partition2);
	//prepare result
	let result = {};

	result[partition1] = object1;
	result[partition2] = object2;

	res.json(result);
}


/**
	* @endpoint must a be a complete path (e.s. https://query.wikidata.org/sparql)
	* @query must be an object (e.s. {query : "this is a query" })
*/
function getQueryResult(endpoint, query, format = DEFAULT_ACCEPT){
	return new Promise((resolve, reject) => {
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
        const http = require(url.protocol.slice(0, -1));
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



/**
	* @group must be one of the const values @topPartition @secondPartition
*/
async function buildJsonFilter(data, group){
	return new Promise(async (resolve, reject) =>{
		try{
			let output, result = await csv().fromString(data);
			
			output = {};
			result.map(d => {
				output[d[group]] = parseFloat(d.amount);
			})

			resolve(output);
			
		}catch (e){
			reject(e);
		}
	});
}
