APIs for g0v financial reporter application
===========================================

This APIs query an RDF graph database containing data according with [BubbleGraph Ontology](https://github.com/linkeddatacenter/LODMAP-ontologies/tree/master/v1/bgo) and produces
a json structure suitable to be used with a g0v financial reporter application.

## usage

### GET /accounts

returns data in format suitable to be used with LODMAP2D Bubble Graph component:

```json
{  
    "title": "Legge di Bilancio 2019",
    "description": "Dati di Spesa relativi alla Legge di Bilancio Approvata per l'esercizio finanziario 2019",
    "source": "http://mef.linkeddata.cloud/resource/spd_lbf_spe_elb_cap_01_2019",
    "um": "EUR",
    "partitionOrderedList": [
        "default",
        "p1_ministero",
        "p2_missione"
    ],
    "partitionScheme": {
        "default": {
            "title": "stato",
            "partitions": []
        },
        "p1_ministero": {
            "title": "ministero",
            "partitions": [
                {
                    "label": "Ambiente e Territorio",
                    "partitionAmount": 845335357
                },
                {
                    "label": "ministero delle politiche agricole alimentari, forestali e del turismo",
                    "partitionAmount": 953197064
                },
                {
                    "label": "debito pubblico",
                    "partitionAmount": 303050130200
                }
            ]
        },     
        "p2_missione": {
            "title": "missione",
            "partitions": [
                {
                    "label": "servizi istituzionali e generali delle amministrazioni pubbliche",
                    "partitionAmount": 42047666908
                },
                {
                    "label": "turismo",
                    "partitionAmount": 44332450
                }
            ]
        }
    },
    "accounts": [
        {
            "code": "17ebc01323e2a557a0e8cc2000a04413",
            "title": "spese di personale per il programma",
            "amount": 1994338,
            "previousValue": 2219761,
            "subject": "gestione del sistema nazionale di difesa civile",
            "partitionLabel": [
                "Ambiente e Territorio",
                "servizi istituzionali e generali delle amministrazioni pubbliche"
            ]
        },
        {
            "code": "183508262a4be1f81a628c867a6e6318",
            "title": "salvaguardia e valorizzazione del patrimonio librario",
            "amount": 11062852,
            "subject": "tutela del patrimonio culturale",
            "partitionLabel": [
                "Ambiente e Territorio",
                "turismo"
            ]
        }
    ]
}
```



### GET /account/{id}


returns data in format suitable to be used with LODMAP2D Bubble AccountDetails component:

```json
{
	"code": "17ebc01323e2a557a0e8cc2000a04413",
	"amount": 1994338,
	"previousValue": 2219761,
	"version": "2019",
	"source": "http://mef.linkeddata.cloud/resource/spd_lbf_spe_elb_cap_01_2019_azione_3906300890_component",
	"title": "spese di personale per il programma",
	"subject": "gestione del sistema nazionale di difesa civile",
	"description": "Iniziative di sviluppo del sistema nazionale ed internazionale di difesa civile. Pianificazione e organizzazione di esercitazioni nazionali e internazionali di difesa civile. Formazione per la gestione di situazioni di crisi. Gestione organizzativa e logistica della struttura operativa centrale di difesa civile. Supporto alle prefetture per la progettazione e il funzionamento delle Sale Operative integrate di protezione civile e di difesa civile e nelle attività di pianificazione di protezione civile. Contributo all'attività normativa in materia di protezione civile. Organizzazione e gestione dei Centri Assistenziali di Pronto Intervento. Partecipazione alla gestione delle emergenze di protezione civile e assistenza alle popolazioni in occasione di pubbliche calamità. Programmazione e gestione delle risorse per l'acquisto di materiali assistenziali.",
	"partitionLabel": [
		"Interno",
		"soccorso civile"
	],
	"isVersionOf": [
		{
			"version": "2017",
			"amount": 2446865
		},
		{
			"version": "2018",
			"amount": 2219761
		}
	],
	"hasPart": [
		{
			"title": "1805 - somma occorrente per la concessione dei buoni pasto al personale civile",
			"amount": 70003
		},
		{
			"title": "1810 - somme dovute a titolo di imposta regionale sulle attivita' produttive sulle retribuzioni corrisposte al personale civile",
			"amount": 118584
		},
		{
			"title": "1812 - competenze fisse ed accessorie al personale dell'amministrazione civile dell'interno al netto dell'imposta regionale sulle attivita' produttive",
			"amount": 1805751
		}
	],
	"um": "EUR"
}
```


### GET /filter/{filtersid}

It calculates partitions total taking into account a filter in {filtersid}.

{filtersid}  is a string that is compressed with zlib an encoded with base64 that contains required filter in the form of (here sintax) e.g. `here example` producing something like:

```json
{
	"p1_ministero": {
		"Ambiente e Territorio": 845335357
	},
	"p2_missione": {
		"servizi istituzionali e generali delle amministrazioni pubbliche": 31731260,
		"ricerca e innovazione": 91179221,
		"sviluppo sostenibile e tutela del territorio e dell'ambiente": 722424876
	}
}
```


### GET /

Returns some internal information about this interface (not to be used)


## Test

To test api server you need:

- run the data store and api server provider 
- populate the graph database with example data (see how in [sdaas component](../sdaas/README.md))
- test api using a browser or any api client pointing it to:


```
curl http://localhost:8080/
```

Roadmap
-------

This interface will soon be migrated to  [Linked Data Platform](https://www.w3.org/TR/ldp-primer/) standard interface compatible with  [Solid standards](https://github.com/solid/solid#standards-used)


