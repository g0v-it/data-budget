![copernicani](../copernicani-logo.png)

Viste d'autore
===============

Questa pagina è dedicata a chi vuole interrogare il  Knowlege Graph attraverso l'endpoint [SPARQL](http://www.w3.org/TR/sparql11-query/) disponibile all'indirizzo https://data.budget.g0v.it/sparql .


## Spese per azioni

Visualizza tutte i componenti di spesa per le azioni contenute nell'ultimo bilancio pubblicato su https://budget.g0v.it/.
Per ogni azione è visualizzato l'URI ai Linked Dati e la pagina su budget.g0v.it corrispondente.

E' equivalente alla visualizzazione sulla home page budget.g0vit..

```sparql
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>

SELECT DISTINCT ?linked_data ?budget_g0v_it 
WHERE {
  ?linked_data a bgo:Account ; bgo:accountId ?accountId .
  BIND( IRI(CONCAT("https://budget.g0v.it/partition/overview?s=",?accountId)) AS ?budget_g0v_it)
} 
```

Questa query utilizza la ontologia [Bubble Graph Ontology (BGO)](http://linkeddata.center/lodmap-bgo/v1) per effettuare ricerche
direttamente sugli elementi del grafico a bolle.

La query inserisce nella variabile `?linked_data` tutti gli URI delle bolle (`bgo:Account`) e 
nella variabile `?budget_g0v_it` viene  costruito il corrisponente URL per isolare la bolla tra tutto il bilancio.

Entrambi i link sono navigabili, il primo come Linked Data, il secondo su https://budget.g0v.it/

[Provala su YasGUI](http://yasgui.org/short/fDdbWcvdw)


## I dieci maggiori incrementi di spesa in valore assoluto (report grafico)

Individua quali sono le 10 azioni che hanno avuto il più alto incremento di spesa rispetto alla legge di bilancio precedente.
Le cifre sono espresse in euro


```sparql
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>

SELECT ?title  ?difference
WHERE { 
  ?account a bgo:Account ; 
           bgo:title ?title ;
           bgo:amount ?amount; 
           bgo:referenceAmount ?previousAmount.
  BIND( ?amount - ?previousAmount AS ?difference)
} ORDER BY DESC(?difference) LIMIT 10
```

Questa query usa la ontologia [Bubble Graph Ontology](http://linkeddata.center/lodmap-bgo/v1) per effettuare ricerche 
direttamente sugli elementi del grafico a bolle.

[Provala su YasGUI](http://yasgui.org/short/K-9k5XVOz) , con grafico a torta



## Ricerca semantica

Cerca stringhe simili a *vigili del fuoco* nella definizione nei fatti dell'ultimo bilancio pubblicato (`bgo:Domain`). 
Per ciascun fatto rilevante trovato, esprime il valore di bilancio per cassa, per competenza e per residui (in euro).

I fatti sono definiti nei *Piani di Gestione*  (`mef:PianoDiGestione`) che rappresentano il livello più dettagliato  delle voci di spesa
presenti nella Legge di Bilancio. 


```sparql
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX qb: <http://purl.org/linked-data/cube#>
PREFIX bds: <http://www.bigdata.com/rdf/search#>
PREFIX skos: <http://www.w3.org/2004/02/skos/core#>
PREFIX mef: <http://w3id.org/g0v/it/mef#>

SELECT DISTINCT ?pdg ?competenza ?cassa ?residui ?definition 
WHERE { 
    ?definition bds:search "vigili del fuoco" ;
        bds:matchAllTerms "true" ; 
        bds:minRelevance 0.25 .

    ?budget a bgo:Domain .
    ?pdg a mef:PianoDiGestione ; 
        qb:dataSet ?budget ;  
        skos:definition ?definition ;
        mef:competenza ?competenza ;
        mef:cassa ?cassa ; 
        mef:residui ?residui .
} ORDER BY DESC(?competenza)
```

Questa query utilizza una estensione di BLAZEGRAPH al linguaggio SPARQL standard e
l'[Ontologia del Bilancio (mef)](http://w3id.org/g0v/it/mef).

Le informazioni ritornate non sono direttamente visibili dall'interfaccia a bolle perchè riguardano un'analisi più profonda del bilancio

[Provala su YasGUI](http://yasgui.org/short/2QGjSggsB)