![copernicani](../copernicani-logo.png)

Viste d'autore
===============

Questa pagina è dedicata a chi vuole interrogare il  Knowlege Graph attraverso l'endpoint [SPARQL](http://www.w3.org/TR/sparql11-query/) disponibile all'indirizzo https://data.budget.g0v.it/sparql .


## Spese per azioni

Visualizza tutte i componenti di spesa per le azioni contenute nell'ultimo bilancio pubblicato su https://budget.g0v.it/.
Per ogni azione è visualizzato l'URI ai Linked Dati e la pagina su budget.g0v.it corrispondente.

E' equivalente alla visualizzazione sulla home page budget.g0vit 

```sparql
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>

SELECT DISTINCT ?linked_data ?budget_g0v_it 
WHERE {
  ?linked_data a bgo:Account ; foaf:isPrimaryTopicOf ?budget_g0v_it .
} 
```

Entrambi i link sono navigabili, il primo come Linked Data, il secondo su https://budget.g0v.it/



## I dieci maggiori incrementi di spesa in valore assoluto (report grafico)

Individua quali sono le 10 azioni che hanno avuto il più alto incremento di spesa rispetto alla legge di bilancio precedente.
Le cifre sono espresse in euro


```sparql
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>

SELECT ?title  ?difference 
WHERE { 
  ?account a bgo:Account ; 
           bgo:title ?title ;
           bgo:amount ?amount; 
           bgo:referenceAmount ?previousAmount .
  BIND( ?amount - ?previousAmount AS ?difference)
  FILTER (?difference != 0)
} ORDER BY DESC(ABS(?difference)) LIMIT 10
```



## Ricerca semantica

Cerca stringhe riferibili alla pandemia  nella definizione nei fatti dell'ultimo bilancio pubblicato (`bgo:Domain`). 
Per ciascun fatto rilevante, esprime il valore di budget per competenza(in euro). Il risultato è limitato ai 10 fatti più rilevanti.

I fatti sono definiti nei *Piani di Gestione*  (`mef:PianoDiGestione`) che rappresentano il livello più dettagliato  delle voci di spesa
presenti nella Legge di Bilancio. 


```sparql
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX bds: <http://www.bigdata.com/rdf/search#>
PREFIX mef: <http://w3id.org/g0v/it/mef#>

SELECT DISTINCT ?pdg ?competenza ?definition 
WHERE { 
    ?definition bds:search "vaccino vaccini covid19 sars" ;
        bds:matchAllTerms "false" ; 
        bds:minRelevance 0.10 .

    ?budget a bgo:Domain .
    ?pdg a mef:PianoDiGestione ; 
        mef:inBudget ?budget ;  
        mef:definition ?definition ;
        mef:competenza ?competenza 
} ORDER BY DESC(?competenza) LIMIT 10
```

Questa query, oltre a BGO,  utilizza 
l'[Ontologia del Bilancio (mef)](http://w3id.org/g0v/it/mef) e una estensione di BLAZEGRAPH al linguaggio SPARQL standard.

Le informazioni ritornate non sono direttamente visibili dall'interfaccia a bolle perchè riguardano un'analisi più profonda del bilancio


## Ricerca semantica su azione

Come sopra ma ritorna l'URL della bolla (i.e. Azione ) che contiene il piano di gestione con le maggiori uscite riferite al termine ricercato.


```sparql
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX bds: <http://www.bigdata.com/rdf/search#>
PREFIX mef: <http://w3id.org/g0v/it/mef#>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>

SELECT ?budget_g0v_it (SUM(?competenza) AS ?rilevanza )  
WHERE{ 
    ?definition bds:matchAllTerms "true" ; bds:minRelevance 0.25; bds:search 

    #### type below your search:
    "vigili del fuoco" .
    
    ?budget a bgo:Domain .
    ?pdg a mef:PianoDiGestione ; 
         mef:inBudget ?budget ;  
         mef:definition ?definition ;
         mef:competenza ?competenza ;
         mef:isPartOf+ ?azione . 
    ?azione a bgo:Account ; foaf:isPrimaryTopicOf ?budget_g0v_it .
} GROUP BY ?budget_g0v_it HAVING( ?rilevanza > 0 ) ORDER BY DESC(?rilevanza) LIMIT 10

```

## quali sono le nuove azioni create ?

Questa query identifica le azioni che non erano presenti in precedenti edizioni del bilancio:

```sparql
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX mef: <http://w3id.org/g0v/it/mef#>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>

SELECT DISTINCT ?visualizza ?ministero ?descrizione_azione ?valore 
WHERE{ 
    ?azione a mef:Azione;
      mef:definition ?descrizione_azione ;
      foaf:isPrimaryTopicOf ?visualizza ;
      mef:competenza ?valore ;
  	  mef:isPartOf/mef:isPartOf/mef:isPartOf ?amministrazione .
    ?amministrazione a mef:Ministero ;
  		mef:definition ?ministero ;
  FILTER NOT EXISTS { ?azione bgo:referenceAmount [] }
} ORDER BY ?ministero DESC(?valore)  
```
