APIs for g0v financial reporter application
===========================================

This APIs query an RDF graph database containing data according with [BubbleGraph Ontology](https://github.com/linkeddatacenter/LODMAP-ontologies/tree/master/v1/bgo) and produces
a json structure suitable to be used with a g0v financial reporter application.

## usage

### GET /accounts

returns data in format suitable to be used with LODMAP2D Bubble Graph component:

```json
{  
   "title":"Consuntivo Finanziario Gestionale 2017",
   "description":"Dati dell'ultimo bilancio consuntivo finanziario gestionale entrate/uscite pubblicato da INPS",
   "source":"http://inps.linkeddata.cloud/resource/ultimo_bilancio_disponibile",
   "um":"EUR",
   "partitionOrderedList":[  
      "default",
      "entrate_uscite"
   ],
   "partitionScheme":{  
      "default":{  
         "title":"INPS",
         "partitions":[  
         ]
      },
      "entrate_uscite":{  
         "title":"entrate/uscite",
         "partitions":[  
            {  
               "label":"ENTRATE",
               "partitionAmount":432152274282.93
            },
            {  
               "label":"USCITE",
               "partitionAmount":-428142366474.04
            }
         ]
      }
   },
   "accounts":[  
      {  
         "code":"0015e68ba033ec06ae0d293e4ecf577d",
         "title":"RIMBORSO DA PARTE DELLE REGIONI DEGLI ONERI DERIVANTI DA ASSEGNI DI UTILIZZO IN ATTIVITA' SOCIALMENTE UTILI, A PARTIRE DAL 1° LUGLIO 2001, AI SENSI DEL D.LGS. 81/2000, CHE ECCEDONO GLI STANZIAMENTI A CARICO DEL FONDO PER L'OCCUPAZIONE",
         "amount":0,
         "previousValue":0,
         "subject":"Trasferimenti da parte delle Regioni (ENTRATE)",
         "partitionLabel":[  
            "ENTRATE"
         ]
      },
      {  
         "code":"0079efb015208cf960dfa5b21d46cacf",
         "title":"CONTRIBUTI DELL'AGENZIA PER LE RELAZIONI SINDACALI DELLE PUBBLICHE AMMINISTRAZIONI (ARAN) AI SENSI DELL'ART. 50, C. 8, LETT. A), D.LGS N. 29/1993",
         "amount":-89590,
         "previousValue":-95532.7,
         "subject":"Trasferimenti passivi (USCITE)",
         "partitionLabel":[  
            "USCITE"
         ]
      }
   ]
}
```



### GET /account/{id}


returns data in format suitable to be used with LODMAP2D Bubble AccountDetails component:

```json
{  
   "code":"0079efb015208cf960dfa5b21d46cacf",
   "amount":-89590,
   "previousValue":-95532.7,
   "version":"2017",
   "source":"http://inps.linkeddata.cloud/resource/bilancio_uscite_2017_G204",
   "title":"CONTRIBUTI DELL'AGENZIA PER LE RELAZIONI SINDACALI DELLE PUBBLICHE AMMINISTRAZIONI (ARAN) AI SENSI DELL'ART. 50, C. 8, LETT. A), D.LGS N. 29/1993",
   "subject":"Trasferimenti passivi (USCITE)",
   "description":"Sezione USCITE. Capitolo di spesa 4U1206061 appartenete alla categoria 'Trasferimenti passivi'. Parte della UPB 'Risorse Umane'.",
   "partitionLabel":[  
      "Risorse Umane",
      "USCITE"
   ],
   "isVersionOf":[  
      {  
         "version":"2014",
         "amount":-101621.1
      },
      {  
         "version":"2015",
         "amount":-99023.3
      },
      {  
         "version":"2016",
         "amount":-95532.7
      }
   ],
   "hasPart":[  

   ],
   "um":"EUR"
}
```


### GET /filter/{filtersid}

It calculates partitions total taking into account a filter in {filtersid}.

{filtersid}  is a string that is compressed with zlib an encoded with base64 that contains required filter in the form of (here sintax) e.g. `here example` producing something like:

```json
{  
   "p1_entrate_uscite":{  
      "USCITE":-428142366474.04
   },
   "p2_categorie":{  
      "Uscite per prestazioni istituzionali":-312149179257.54,
      "Uscite aventi natura di partite di giro":-65435115413.92,
      "Rimborsi di anticipazioni passive":-18307000000,
      "Poste correttive e compensative di entrate correnti":-15149221355.23,
      "Concessioni di crediti e anticipazioni":-7650017625.07,
      "Trasferimenti passivi":-5320196700.41,
      "Oneri per il personale in attività di servizio":-1652741837.77,
      "Uscite non classificabili in altre voci":-876634375.79,
      "Uscite per l'acquisto di beni di consumo e di servizio":-565374646.44,
      "Oneri per il personale in quiescenza":-287180065.97,
      "Partecipazioni e acquisto di valori mobiliari":-199000000,
      "Oneri tributari":-192457713.22,
      "Acquisizione di immobilizzazioni tecniche":-157172753.13,
      "Indennità di anzianità e similari al personale cessato dal servizio":-94429185.6,
      "Estinzione debiti diversi":-83274490.41,
      "Oneri finanziari":-16079086.09,
      "Acquisizione beni di uso durevole e opere immobiliari":-4048234.88,
      "Uscite per gli organi dell'Ente":-3243732.57
   },
   "p3_upb":{  
      "Pensioni":-277997312201.81,
      "Altre strutture di Direzione Generale":-86791528179.56,
      "Prestazioni a sostegno del reddito":-43648651463.14,
      "UPB Entrate":-16236765265.96,
      "Risorse Umane":-2370288250.45,
      "Risorse Strumentali":-1097821113.12
   }
}
```




TBD

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


