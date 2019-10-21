g0v fr-ap-mef
==============

*"In order to let computers to work for us, they must understand data: not just the grammar and the syntax, but the real meaning of things"* [[KEES](https://linkeddatacenter.github.io/kees/)].

g0v fr-ap-mef  is a *Semantic Web application profile*, that is a trimmed down version existing formal vocabularies (*ontologies*) to trades some expressive power for the efficiency of reasoning. An application profile contains some domain specific axioms and restrictions.

g0v fr-ap-mef is a specialization of the more general [g0v Financial Report application profile](https://g0v-it.github.io/ontologies/fr-ap) that uses the [MEF vocabulary](http://w3id.org/g0v/it/mef#) to capture the specific Italian budget semantic.

g0v fr-ap-mef also provides a mapping to a [Bubble Graph Ontology](http://linkeddata.center/lodmap-bgo/v1) (BGO) for data exploration concepts.
In order to get a manageable number of bgo:Accounts, fr-ap-mef considers the fourth level of the italian budget taxonomy (i.e. "azioni") 
as the focus for the Bubble Graph Ontology; the financial facts (i.e. mef:capitoli) are modeled as bgo:Account breakdowns.

The following picture summarizes the mappings introduced by fr-ap-mef profile:

![UML diagram](uml-diagram.png)

## Axioms

The following axioms extend the fr-ap ones :

- the financial report hierarchy maps exactly the financial report taxonomy;
- *part of* means *broader* (`(x fr:isPartOf y) ⇒ (x skos:broader y)`);
- sdmx-attribute:unitMeasure is always <http://publications.europa.eu/resource/authority/currency/EUR>
- the fr:refPeriod of a mef:Report is always a full solar year (from 1.1 to 31.12)
- similar concepts in different taxonomies use the same skos:notation

the following axioms should be used to derive a Bubble Graph Ontology:


- the source of the BGO Domain must be is the newest *MEF Financial Report* in the knowledge graph;
- the source of a bgo:Account must be an *Azione* (i.e. the fourth level of a MEF financial reports);
- *capitolo di spesa* is a breakdown of a bgo:Account;
- account history is inferred taking into account similar components concepts ;
- the bgo:referenceValue, if present, is the amount of the newest entry in the account history ;
- in bgo editorial properties, these rules should be used:
    - the bgo:versionLabel is built from report class and report year.
    - the bgo:title can be  derived from skos:notation
    - the bgo:description for an account can be derived from skos:definition 
    - the bgo:abstract for an account can be derived from the skos:notation, skos:definition and skos:editorialNote of all related report sections (mef:Section)


## Examples

In the example the following namespaces assumed:

```turtle
@prefix skos: <http://www.w3.org/2004/02/skos/core#> .
@prefix bgo: <http://linkeddata.center/lodmap-bgo/v1#> .
@prefix fr: <http://linkeddata.center/botk-fr/v1#>.
@prefix qb: <http://purl.org/linked-data/cube#> .
@prefix mef: <http://w3id.org/g0v/it/mef#> .
@prefix : <#>.
```


Suppose to have two RDF web resources containing a MEF financial reports expressed according to fr-ap-mef:

Resource **http://example.org/ITA-DLDB-2019.ttl**:

```turtle
:budget_report a mef:DisegnoLeggeDiBilancio ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	dct:title "Disegno di Legge di Bilancio 2019"@it ;
	dct:description "Note al disegno di legge di Bilancio 2019"@it .
	
:cap_1 a mef:Capitolo;
	qb:dataSet :budget_report ;
	fr:isPartOf :azione_1 ;
	skos:notation "1.1.1.1.1" ;
	fr:amount 200000000000.00	.

:cap_2 a mef:Capitolo;
	qb:dataSet :budget_report ;
	skos:notation "1.1.1.1.2" ;
	fr:isPartOf :azione_1 ;
	fr:amount 100000000000.00	.
	
:azione_1 fr:isPartOf :programma_1 ; skos:notatation "1.1.1.1" .
:programma_1 fr:isPartOf :missione_1 ; skos:editorialNote "note specifiche del programma"; skos:notatation "1.1.1" .
:missione_1 fr:isPartOf :ministero_1 ; skos:notatation "1.1" .
:ministero_1 skos:notation "1" .
```


Resource **http://example.org/ITA-LDB-2018.ttl**:

```turtle
:budget_report a mef:LeggeDiBilancio ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> .
	
:cap_1 a mef:Capitolo;
	qb:dataSet :budget_report ;
	fr:isPartOf :azione_1 ;
	skos:notation "1.1.1.1.1" ;
	fr:amount 100000000000.00	.

:cap_2 a mef:Capitolo;
	qb:dataSet :budget_report ;
	skos:notation "1.1.1.1.2" ;
	fr:isPartOf :azione_1 ;
	fr:amount 100000000000.00	.
	
:azione_1 fr:isPartOf :programma_1 ; skos:notatation "1.1.1.1" .
:programma_1 fr:isPartOf :missione_1 ; skos:notatation "1.1.1" .
:missione_1 fr:isPartOf :ministero_1 ; skos:notatation "1.1" .
:ministero_1 skos:notation "1" .

```

A g0v fr-ap-mef reasoner should able to add these inferences to the knowledge graph :

```turtle 
@prefix ns1: <http://example.org/ITA-DLDB-2019.ttl#> .
@prefix ns2: <http://example.org/ITA-LDB-2018.ttl#> .

ns1:cap_1 a qb:Observation, skos:Concept ;
    fr:concept ns1:cap_1;
    skos:closeConcept ns2:cap_1;
    skos:broader ns1:azione_1 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> .
		
ns1:cap_2 a qb:Observation, skos:Concept, bgo:HistoryRec ;
    fr:concept ns1:cap_2;
    skos:closeConcept ns2:cap_2;
    skos:broader ns1:azione_1 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	bgo:amount 100000000000.00	.

ns1:azione_1 a qb:Observation, fr:Component, skos:Concept ;
    fr:concept ns1:azione_1;
    skos:closeConcept ns2:azione_1;
    skos:broader ns1:programma_1 ;
    skos:narrower ns1:cap_1, ns1:cap_2 ;
	qb:dataSet ns1:budget_report ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:amount 300000000000.00	.
   
ns1:programma_1 a qb:Observation, fr:Component,  skos:Concept;
    fr:concept ns1:programma_1;
    skos:closeConcept ns2:programma_1;
    skos:broader ns1:missione_1 ;
    skos:narrower ns1:azione_1 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:amount 300000000000.00 .
   
ns1:missione_1 a qb:Observation, fr:Component,  skos:Concept;
    fr:concept ns1:missione_1;
    skos:closeConcept ns2:missione_1;
    skos:broader ns1:ministero_1 ;
    skos:narrower ns1:programma_1 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:amount 300000000000.00 .
   
ns1:ministero_1 a qb:Observation, fr:Component,  skos:Concept;
    fr:concept ns1:ministero_1;
    skos:closeConcept ns2:ministero_1;
    skos:narrower ns1:missione_1 ;
	qb:dataSet ns1:budget_report ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:amount 300000000000.00 ;
	bgo:hasAccount ns1:azione_1 .
    

ns1:budget_report a fr:FinancialReport, qb:DataSet, skos:ConceptScheme; 
	skos:hasTopConcept ns1:ministero_1  . 


ns2:cap_1 a qb:Observation, skos:concept ;
    fr:concept ns2:cap_1;
    skos:closeConcept ns1:cap_1;
    skos:broader ns2:azione_1 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> .
		
ns2:cap_2 a qb:Observation, skos:concept, bgo:HistoryRec ;
    fr:concept ns2:cap_2;
    skos:closeConcept ns1:cap_2;
    skos:broader ns2:azione_1 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	bgo:amount 100000000000.00	.

ns2:azione_1 a qb:Observation, fr:Component, skos:Concept ;
    fr:concept ns2:azione_1;
    skos:closeConcept ns1:azione_1;
    skos:broader ns2:programma_1 ;
    skos:narrower ns2:cap_1, ns2:cap_2 ;
	qb:dataSet ns2:budget_report ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:amount 300000000000.00	.
   
ns2:programma_1 a qb:Observation, fr:Component,  skos:Concept;
    fr:concept ns2:programma_1;
    skos:closeConcept ns1:programma_1;
    skos:broader ns2:missione_1 ;
    skos:narrower ns2:azione_1 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:amount 300000000000.00 .
   
ns2:missione_1 a qb:Observation, fr:Component,  skos:Concept;
    fr:concept ns2:missione_1;
    skos:closeConcept ns1:missione_1;
    skos:broader ns2:ministero_1 ;
    skos:narrower ns2:programma_1 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:amount 300000000000.00 .
   
ns2:ministero_1 a qb:Observation, fr:Component,  skos:Concept;
    fr:concept ns2:ministero_1;
    skos:closeConcept ns1:ministero_1;
    skos:narrower ns2:missione_1 ;
	qb:dataSet ns2:budget_report ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2018-01-01T00:00:00/P1Y> ;
	fr:unit <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:amount 300000000000.00 ;
	bgo:hasAccount ns2:azione_1 .
    

ns2:budget_report a fr:FinancialReport, qb:DataSet, skos:ConceptScheme; 
	skos:hasTopConcept ns2:ministero_1  .


:domain a bgo:Domain ;
	dct:source 2019:budget_report ;
	bgo:title "Disegno di Legge di Bilancio 2019"@it ;
	bgo:description "Note al disegno di legge di Bilancio 2019"@it .
.

:account_1 a bgo:Account ;
	dct:source 2019:azione_1 ;
	bgo:versionLabel "D2019" ;
	bgo:title "Azione 1.1.1.1"@it ;
	bgo:abstract "Note specifiche del programma"@it ;
    bgo:hasBreakdown 
    	[ bgo:title "Cap. 1.1.1.1.1"@it ; bgo:amount 200000000000.00	] ,
    	[ bgo:title "Cap. 1.1.1.1.2"@it ; bgo:amount 100000000000.00	] ,
    bgo:amount 300000000000.00	;
    bgo:hasHistoryRec [ dct:source 2018:azione_1; bgo:versionLabel "L2018" ; bgo:amount 200000000000.00 ]	
    bgo:referenceAmount 200000000000.00 ;
.

```
