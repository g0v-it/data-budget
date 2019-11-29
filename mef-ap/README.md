g0v mef-ap
==============

*"In order to let computers to work for us, they must understand data: not just the grammar and the syntax, but the real meaning of things"* [[KEES](https://linkeddatacenter.github.io/kees/)].

**g0v mef-ap** is a *Semantic Web application profile*, that is a trimmed down version existing formal vocabularies (*ontologies*) to trades some expressive power for the efficiency of reasoning.

g0v mef-ap builds on  the [MEF vocabulary](http://w3id.org/g0v/it/mef) to capture the specific Italian budget semantic.
g0v mef-ap reuses some individual references to linked open data provided by [UK e-gov](https://github.com/alphagov/datagovuk_reference) and by 
[Currency EU vocabulary](http://publications.europa.eu/resource/authority/currency).

g0v mef-ap provides a mapping to a [Bubble Graph Ontology](http://linkeddata.center/lodmap-bgo/v1) (BGO) for data exploration concepts.
In order to get a manageable number of bgo:Accounts, mef-ap considers the fourth level of the italian budget taxonomy (i.e. "azioni") 
as the focus for the Bubble Graph Ontology; the financial facts (i.e. mef:capitoli) are modeled as bgo:Account breakdowns.

## Bubble Graph Ontology mapping

In this release only budget expense with a reduced taxonomy  is used.
The following axioms should be used to derive a Bubble Graph Ontology:

- the BGO Domain is the newest known *MEF Financial Report* ;
- each *mef:Azione* is a *bgo:HistoryRec* ;
- each *bgo:HistoryRec* referring a bgo:Domain in the qb:dataSet property is a bgo:Account;
- *bgo:account* a sub-property of *mef:competenza*;
- *mef:CapitoloDiSpesa* that are parts of a bgo:Account are bgo:Amount that are used to compose the account breakdown perspective;
- account history is inferred from *mef:Azione* versioning (dct:isVersionOf) ;
- the bgo:referenceValue, if present, is the amount of the newest entry in the account history ;
- in inferring bgo properties, these rules should be used:
	- the skos:prefLabel can be derived from the class rdfs:label;
    - bgo:label := coalesce(skos:prefLabel, rdfs:label STRAFTER(?uri, "http://mef.linkeddata.cloud/resource/"))
    - bgo:versionLabel := qb:dataSet/fr:versionId ;
    - bgo:title := coalesce(dct:title || bgo:label)
    - bgo:description := coalesce((dct:title, skos:definition)
    - bgo:abstract := coalesce( dct:abstratct,  skos:editorialNote , rdfs:comment )
 
    

## URIs and naming convention

g0v mef-ap defines a set of naming convention for:

- notation
- identifiers
- URIs


**Naming conventions for notations **

Every skos concept  must have a notation. Following rules apply:

- two concepts with the same notation are related with the skos:closeConcept property.
- all concept related with the same budget ( qb:dataset) mus have an unique notation.


As corollary, the notation is used to infer links between different budget reports.

| base type               | notation template                                                                        | esempio   |
|-------------------------|------------------------------------------------------------------------------------------|-----------|
| mef:Spesa               | s                                                                                        | s         |
| mef:Entrata             | e                                                                                        | e         |
| mef:Ministero           | a{codice MEF ministero}                                                                  | a12       |
| mef:CentroResponabilita | a{codice MEF ministero}r{id centro responsabilità}                                       | a1r123    |
| mef:MissioneMinistero   | a{codice MEF ministero}m{codice MEF missione}                                            | a1m1      |
| mef:Programma           | a{codice MEF ministero}m{codice MEF missione}p{codice MEF programma}                     | a1m1p1    |
| mef:Azione              | a{codice MEF ministero}m{codice MEF missione}p{codice MEF programma}a{codice MEF azione} | a1m1p1a1  |
| mef:CapitoloDiSpesa     | a{codice MEF ministero}c{codice MEF capitolo}                                            | a1c123    |
| mef:PianoDiGestione     | a{codice MEF ministero}c{codice MEF capitolo}p{codice MEF piano}                         | a1c123p1  |
| mef:Missione            | m{codice MEF missione}                                                                   | m1        |
| mef:TitoloSpesa         | s{codice MEF titolospesa}                                                                | s1        |
| mef:CategoriaSpesa      | s{codice MEF titolospesa}c{codice MEFcategoria}                                          | s1c1      |
| mef:TitoloEntrata       | e{codice MEF titolo entrata}                                                             | e1        |
| mef:Natura              | e{codice MEF titolo entrata}n{codice MEF natura}                                         | e1n1      |
| mef:Tipologia           | e{codice MEF titolo entrata}t{codice MEF tipologia}                                      | e1t1      |
| mef:Provento            | e{codice MEF titolo entrata}t{codice MEF tipologia}p{codice MEF provento}                | e1t1p1    |
| mef:CapitoloEntrata     | e{codice MEF titolo entrata}c{codice MEFcapitolo}                                        | e1c1123   |
| mef:Articolo            | e{codice MEF titolo entrata}c{codice MEFcapitolo}a{codice MEF articolo}                  | e1c1123a1 |


**Identifies (dct:identifier) naming convention **

| base type               | regexp                                                   | esempio         |
|-------------------------|----------------------------------------------------------|-----------------|
| mef:Budget              | \d\d(L|D|P|R)*                                           | 19B             |
| skos:ConceptScheme      | {mef budgetid}T(SMMPACP|SMRMP|SMTC|STCCP|SMP|ETNT|ETTPCA)| 19ATSMMPACP     |
| mef:StructuralComponent | {mef budgetid}[SE]{skos:notation}                        | 19A1Sa1m1p1a1   |




**URIs naming convention **

Following convention should apply in URI generation to encode parsable information:

http://mef.linkeddata.cloud/resource/{identifier}



Examples:

- http://mef.linkeddata.cloud/resource/19LBSa1m2p3a4 is an expense Action in first edition of 2019  Legge di Bilancio 
- http://mef.linkeddata.cloud/resource/19LBTSMP is the taxonomy Missione->Programma in  first edition of 2019  Disegno di Legge di Bilancio 
- http://mef.linkeddata.cloud/resource/19LB2 is the third edition or the 2019  Legge di Bilancio 


## Examples

From this fact :

```turtle
@prefix : <http://mef.linkeddata.cloud/resource/> .
:19La1c123p1
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 .
```


here are some of the new rdf triples that a **g0v mef-ap reasoner** should be able to derive looking to the fact, ontology, axioms and conventions:

```turtle 

:19La1c123p1 a mef:PianoDiGestione ;
	rdfs:comment "Piano di gestione del Ministero 1  nel capitolo di spesa 123 della Legge di Bilancio 2019"@it ;
	dtc:identifier "2019BSa1c123p1"
	
	# skos inferred properties:	
	skos:notation "a1c123p1";
	skos:inScheme :19LTSMMPACP, :19LTSTCCP ;
    
    # fr related inferred properties:
    qb:dataSet :19L ;
    fr:isPartOf :19La1c123 
.

:19L a mef:LeggeBilancio;
	dct:title "Legge di Bilancio 2019"@it ;
	dct:description "Legge di Bilancio per l'anno 2019 approvata dalle Camere"@it ;
	
	mef:hasSchemeSMMPACP :19LTSMMPACP ;
	mef:hasSchemeSTCCP :19LTSTCCP ;
	mef:spese :19LSs ;
	
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	sdmx-attribute:unitMeasure <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:versionId "2019B"^^mef:BudgetVersion ;
.

:19LBTSMMPACP a skos:ConcetpScheme ;
	rdfs:comment "Spese in Legge di bilancio 2019 per Ministero/Missione/Programma/Azione/Capitolo/pdg"@it ;
	skos:hastTopConcept :19LBSA1 ;
.
	

:19Ls a mef:Spesa;
    qb:dataSet :19L ;
	rdfs:comment "Legge di Bilancio 2019: spese"@it ;
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 .
.
	
:19La1 a mef:Ministero;
    qb:dataSet :19L ;
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 .
.

:19La1c123 a mef:CapitoloDiSpesa;
    qb:dataSet :19L ;
	rdfs:comment "Voce di spesa del Ministero 1 relativa al capitolo di spesa 123 riportata nella prima edizione della Legge di Bilancio dell'anno 2019"@it ; 
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 .
	
	skos:notation "a1c123" ;
	skos:narrower :19La1c123p1 ;
.


# Bgo related inferences:

:19L a bgo:Domain ;
	rdfs:seeAlso <https://budget.g0v.it/> ;
	bgo:title "Legge di Bilancio 2019"@it
	bgo:description "Legge di Bilancio per l'anno 2019 approvata dalle Camere"@it ;
.

:19La1c123 a bgo:Amount ;
	bgo:title  "2019BSa1c123p1" ;
	bgo:amount 200000000000.00  ;
.


```

