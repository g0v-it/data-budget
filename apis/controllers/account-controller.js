//ACCOUNT CONTROLLER
const https = require('https'),
querystring = require('querystring');


exports.getAccounts = (req, res) => {
	let endpoint = "https://query.wikidata.org/sparql";
	let query = require('../queries/example.js');

	getQueryResult(endpoint, query).then((result) => {
		res.json(result);
	})
}

exports.getAccount = (req, res) => {
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
				//console.log(chunk);
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


