g0v mef-ap
==============

*"In order to let computers to work for us, they must understand data: not just the grammar and the syntax, but the real meaning of things"* [[KEES](https://linkeddatacenter.github.io/kees/)].

**g0v mef-ap** is a *Semantic Web application profile*, that is a trimmed down version existing formal vocabularies (*ontologies*) to trades some expressive power for the efficiency of reasoning.

g0v mef-ap builds on  the [MEF vocabulary](http://w3id.org/g0v/it/mef) to capture the specific Italian budget semantic.

g0v mef-ap provides a mapping to a [Bubble Graph Ontology](http://linkeddata.center/lodmap-bgo/v1) (BGO) for data exploration concepts.
In order to get a manageable number of bgo:Accounts, mef-ap considers the fourth level of the italian budget taxonomy (i.e. "azioni") 
as the focus for the Bubble Graph Ontology; the financial facts (i.e. mef:capitoli) are modeled as bgo:Account breakdowns.

## Additional restrictions

Following axioms are added to mef ontology ones:

- mef:Budget should be related (dct:source) with some documents described with [DCAT](https://www.dati.gov.it/content/dcat-ap-it-v10-profilo-italiano-dcat-ap-0) (according with DCAT-AP_IT v1.0 profile) vocabulary.
- the bgo:accountId must be defined as the distinguish part of the account uri.
- mef:Accoung has foaf:isPrimaryTopicOf attribute pointing to the URL of a visualization tool (e.g. https://budget.g0v.it/)

## Bubble Graph Ontology mapping

In this release only budget expense with a reduced taxonomy is used.
The following axioms should be used to derive a Bubble Graph Ontology:

- the BGO Domain is the newest known *MEF Financial Report* ;
- each *mef:Azione* is a *bgo:HistoryRec* having *bgo:versionLabel* equals to the referred mef:Budget mer:versionId property ;
- *bgo:account* a sub-property of *mef:competenza*;
- *mef:CapitoloDiSpesa* that are parts of a bgo:Account are bgo:Amount that are used to compose the account breakdown perspective;
- the account history perspective is composed by all *bgo:HistoryRec* sharing the same *bgo:versionLabel* of the account ;
- the bgo:referenceValue, if present, is the amount of the newest history rec in the ccount history perspective ;

    

## URIs and naming convention

g0v mef-ap defines a set of naming convention for:

- notation
- identifiers
- URIs


**Naming conventions for notations **

Every struttural component  must have a notation. Following rules apply:

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
| mef:Budget              | `\d\d(L\|D\|P\|R)*`                                         | 19B             |
| skos:ConceptScheme      | `{mef budgetid}T(SMMPACP\|SMRMP\|SMTC\|STCCP\|SMP\|ETNT\|ETTPCA)| 19ATSMMPACP`   |
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


here are **some** of the new rdf triples that a **g0v mef-ap reasoner** should be able to derive looking to the fact, ontology, axioms and conventions:

```turtle 

:19La1c123p1 a mef:PianoDiGestione ;
	mef:notation "a1c123p1";
    mef:inBudget :19L ;
	mef:inTaxonomy :19LTSMMPACP, :19LTSTCCP ;
    mef:isPartOf :19La1c123 
.

:19L a mef:LeggeBilancio ;
	mef:versionId "19L" ;
	mef:esercizio 2019 ;
	mef:hasSchemeSMMPACP :19LTSMMPACP ;
	mef:hasSchemeSMRMP :19LTSMRMP ;
	mef:hasSchemeSMTC :19LTSMTC ;
	mef:hasSchemeSTCCP :19LTSTCCP ;
	mef:hasSchemeSMP :19LTSMP ;
	mef:hasSchemeETNT :19LTETNT ;
	mef:hasSchemeETTPCA :19LTETTPCA ;
	mef:spese :19LSs 
.
:19LTSMMPACP a mef:Taxonomy .
:19LTSMRMP a mef:Taxonomy .
:19LTSMTC a mef:Taxonomy .
:19LTSTCCP a mef:Taxonomy .
:19LTSMP a mef:Taxonomy .
:19LTETNT a mef:Taxonomy .
:19LTETTPCA  a mef:Taxonomy .

:19Ls a mef:Spesa ;
    mef:inBudget :19L ;
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 ;
	mef:notation "s" 
.
	
:19La1 a mef:Ministero ;
    mef:inBudget :19L ;
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 ;
	mef:inTaxonomy :19LTSMMPACP ;
	mef:notation "a1" 
.

:19La1c123 a mef:CapitoloDiSpesa ;
    mef:inBudget :19L ;
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 ;
	mef:inTaxonomy :19LTSMMPACP, :19LTSTCCP ;
	mef:notation "a1c123" 
.

...

# Bgo related inferences:

:19L a bgo:Domain .
:19La1c123 a bgo:Amount ; bgo:amount 200000000000.00 .

...

```

