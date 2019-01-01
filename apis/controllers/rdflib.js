//RDFLIB
const $rdf = require("rdflib");
//Namspaces
const DCT = $rdf.Namespace("http://purl.org/dc/terms/"), BGO = $rdf.Namespace("http://linkeddata.center/lodmap-bgo/v1#"),
RDF = $rdf.Namespace("http://www.w3.org/1999/02/22-rdf-syntax-ns#");

//Attributes devided by namespace
let accountSimpleWordsBGO = [
    "code",
    "amount",
    "previousValue",
    "version"
], accountSimpleWordsDCT = [
    "source",
    "title",
    "subject",
    "description"
];

exports.parseAccounts = async (data, accept) => {
    return new Promise(async (resolve, reject) =>{
        let store = $rdf.graph(),
        base = 'https://data.budget.g0v.it/ldp',
        bubbleGraph, accounts, partitions, //My subjects
        output = {}, json = {}, jsonPartitions = {}, jsonArray = [], partitionSchemeId; //My data Structures
        

        $rdf.parse(data, store, base, accept);
        //Set the subjects
        bubbleGraph = store.any(undefined, RDF("type"), BGO("BubbleGraph"));
        accounts = store.each(undefined, RDF("type"), BGO("Account"));
        partitions = store.each(undefined, RDF("type"), BGO("PartitionScheme"));


        output['title'] = store.any(bubbleGraph, DCT("title")).value;
        output['description'] = store.any(bubbleGraph, DCT("description")).value;
        output['source'] = store.any(bubbleGraph, DCT("source")).value;
        output['um'] = store.any(bubbleGraph, BGO("um")).value;

        //OrderdList treated as a collection with a bunch of element
        //jsonPartition
        output['partitionOrderedList'] = [];
        //store.any(bubbleGraph, BGO("partitionOrderedList")).elements.forEach(node => {
        store.each(bubbleGraph, BGO("partitionScheme")).forEach(node => {
            partitionSchemeId = store.any(node, BGO("partitionSchemeId")).value; //Get Partition Schema id to be used later
            json['title'] = store.any(node, DCT("title")).value;
            json['partitions'] = [];

            store.each(node, BGO("partition")).forEach((subNode) => {
                //Construnction of object
                json['partitions'].push({
                    label : store.any(subNode, BGO("label")).value,
                    partitionAmount : parseFloat(store.any(subNode, BGO("partitionAmount")).value)
                });
            });
            json['partitions'] = json['partitions'].sort((a,b) => (a.partitionAmount > b.partitionAmount) ? 1 : ((b.label > a.label) ? -1 : 0));
            //Putting everything together
            jsonPartitions[partitionSchemeId] = json;
            output['partitionOrderedList'].push(partitionSchemeId);
            json = {};
        });
        output['partitionOrderedList'].sort();
        output['partitionScheme'] = jsonPartitions;

        //Finally, the accounts
        accounts.forEach((account) => {
            json['code'] = store.any(account, BGO("code")).value;
            json['title'] = store.any(account, DCT("title")).value;
            json['amount'] = parseFloat(store.any(account, BGO("amount")).value);
            json['previousValue'] = store.any(account, BGO("previousValue")) ? parseFloat(store.any(account, BGO("previousValue")).value) : undefined;
            json['subject'] = store.any(account, DCT("subject")).value;
            json["partitionLabel"] = [];
            store.each(account, BGO("partitionLabel")).forEach((partition) => {
                json["partitionLabel"].push(partition.value);
            });
            jsonArray.push(json);
            json = {}; 
        });
        //console.log(jsonArray);
        output['accounts'] = jsonArray;

        resolve(output);
    });
}

exports.parseAccount = async (data, accept) => {
    return new Promise(async (resolve, reject) =>{
        //resolve(data);
        let store = $rdf.graph(),
        base = 'https://data.budget.g0v.it/ldp/account',
        isVersionOfObject = {}, hasPartObject = {}, output = {}, //my data structure
        account, bubbleGraph; //My subject

        $rdf.parse(data, store, base , accept);  // pass base URI
        account = store.any(undefined, RDF("type"), BGO("Account")); //Gets the subject based on Type (it may change)
        bubbleGraph = store.any(undefined, RDF("type"), BGO("BubbleGraph")); //Gets the subject based on Type (it may change)
        
        //Build Simple Params
        let value;
        accountSimpleWordsBGO.forEach((word) => {
            value = store.any(account, BGO(word));
            value = value ? value.value : undefined;
            if((word == "amount" || word == "previousValue" ) && value)
                value = parseFloat(value);
            output[word] = value;
        });
        accountSimpleWordsDCT.forEach((word) => {
            value = store.any(account, DCT(word));
            value = value ? value.value : undefined
            output[word] = value;
        });

        //Partitions partitionLabel
        output["partitionLabel"] = [];
        store.each(account, BGO("partitionLabel")).forEach((partition) => {
            output["partitionLabel"].push(partition.value);
        });

        //IsVersionOf
        output['isVersionOf'] = [];
        store.each(account, BGO("isVersionOf")).forEach(node => {
            isVersionOfObject["version"] = store.any(node, BGO("version")).value;
            isVersionOfObject["amount"] = parseFloat(store.any(node, BGO("amount")).value);
            output['isVersionOf'].push(isVersionOfObject);
            isVersionOfObject = {};
        });

        //Haspart
        output['hasPart'] = [];
        store.each(account, BGO("hasPart")).forEach(node => {
            hasPartObject["title"] = store.any(node, DCT("title")).value;
            hasPartObject["amount"] = parseFloat(store.any(node, BGO("amount")).value);
            output['hasPart'].push(hasPartObject);
            hasPartObject = {};
        });
        output['um'] = store.any(bubbleGraph, BGO("um")).value;

        resolve(output);
    });
}

function getUri(code){
    //return "http://lodmap-bgo.example.com/ldp/account/" + code;
    //return "http://lodmap-bgo.example.com/ldp/account" + "/" + code
    return code;
}

//RDFLIB