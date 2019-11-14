g0v mef-ap
==============

*"In order to let computers to work for us, they must understand data: not just the grammar and the syntax, but the real meaning of things"* [[KEES](https://linkeddatacenter.github.io/kees/)].

**g0v mef-ap** is a *Semantic Web application profile*, that is a trimmed down version existing formal vocabularies (*ontologies*) to trades some expressive power for the efficiency of reasoning.

g0v mef-ap builds on  the [MEF vocabulary](http://w3id.org/g0v/it/mef#) to capture the specific Italian budget semantic.
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
- each *bgo:HistoryRec*  referring a bgo:Domain in the  qb:dataSet property is a bgo:Account;
- *mef:CapitoloDiSpesa* that are parts of a bgo:Account are bgo:Amount that are used to compose the account breakdown perspective;
- account history is inferred taking into *mef:Azione* individuals with same skos:notation ;
- the bgo:referenceValue, if present, is the amount of the newest entry in the account history ;
- in inferring bgo properties, these rules should be used:
	- the skos:prefLabel can be derived from the class rdfs:label;
    - the bgo:label can be the skos:prefLabel;
    - the bgo:versionLabel can be built from the report versionId;
    - the bgo:title for an account can be derived from skos:prefLabel and skos:notation;
    - the bgo:description can be  derived from skos:definition;
    - the bgo:abstract for an account can be derived from the skos:notation, skos:definition and skos:editorialNote of all related report sections (mef:Section)
 
    

## URIs naming convention

Following convention should apply in URI generation to encode parsable information:


| base type         | uri template                                                                   |
|-------------------|--------------------------------------------------------------------------------|
| skos:Concept      | http://mef.linkeddata.cloud/resource/B*{budget-version}*-*{notation}*          |
| skos:ConcepScheme | http://mef.linkeddata.cloud/resource/B*{budget-version}*-{budget taxonomy id}* |
| mef:Budget        | http://mef.linkeddata.cloud/resource/B{budget-version}*                        |

- all dots must be deleted ;

Examples:

- http://mef.linkeddata.cloud/resource/B201900-M1-2-3-4
- http://mef.linkeddata.cloud/resource/B201900-M
- http://mef.linkeddata.cloud/resource/B201900


## Examples

From this fact :

```turtle
@prefix : <http://mef.linkeddata.cloud/resource/> .
:B201900-U1-1001-1
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 .
```


Looking to the ontology, axioms, and conventions, a **g0v mef-ap reasoner** should be able to derive the following additional informations :

```turtle 

:B201900-U1-1001-1  
	# budget inferred properties:
	a mef:PianoDiGestione, mef:Fact, mef:StructuralComponent ;
	
	# skos inferred properties:	
	a skos:Concept ;
	skos:notation "U1-1001-1"^^mef:Notation ;
	skos:inScheme :B201900-U ;
    
    # fr related inferred properties:
    a fr:Fact, fr:StructuralComponent ;
    qb:dataSet :B201900 ;
    fr:concept :B201900-U1-1001-1;
    fr:isPartOf :B201900-U1-1001 ;
    fr:amount 200000000000.00 ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	sdmx-attribute:unitMeasure <http://publications.europa.eu/resource/authority/currency/EUR> 
.

:B201900
	# budget inferred properties:
	a mef:Budget, mef:BudgetTaxonomy;
	mef:includesAdministrations :B201900-A ;

	# skos inferred properties:	
	a skos:ConceptScheme ;
	skos:hasTopConcept :B201900-A1 ;
	owl:includes :B201900-A, :B201900-U ;
	
	# fr related inferred properties:
	a  fr:FinancialReport ;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	sdmx-attribute:unitMeasure <http://publications.europa.eu/resource/authority/currency/EUR> ;
	fr:versionId "2019-0-0"^^mef:SemanticVersion ;
	
	#bgo related properties
	a bgo:Domain ;
	rdfs:seeAlso <https://budget.g0v.it/> ;
	bgo:title "Disegno Legge di Bilancio 2019"@it
	bgo:description "Disegno di Legge di Bilancio per l'anno 2019 presentato alle Camere"@it ;
.

	
	
:B201900-A
	# budget inferred properties:
	a mef:Administrations, mef:BudgetTaxonomy,
	mef:budgetTaxonomyId "A" ; 
	
	# skos inferred properties:
	a  skos:ConceptScheme ;
	
	#bgo related properties
	bgo:Partition;
	bgo:label "Ministero"@it ;
	rdfs:seeAlso <https://budget.g0v.it/partition/pa> ;
	bgo:hasAccountSubset :B201900-A1 ;
	bgo:partitionId "pa" ;
.

	
:B201900-U
	# budget inferred properties:
	a  mef:BudgetTaxonomy ;
	mef:budgetTaxonomyId "U" ; 
	mef:includesAdministrations :B201900-A ;
	
	# skos inferred properties:
	a  skos:ConceptScheme ;
	owl:includes :B201900-A;
.

:B201900-A1
	# budget inferred properties:
	a mef:Ministero; mef:Component, mef:StructuralComponent ;
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 ;
	
	# skos inferred properties:	
	a skos:Concept ;
	skos:inScheme :B201900-A ;
	skos:notation "A1"^^mef:Notation;
	
	
	# fr related inferred properties:
	a fr:Component, fr:StructuralComponent ;
    fr:concept :B201900-A1;
    fr:amount 200000000000.00 ;
    
    #bgo related properties
    a bgo:AccountSet
.



:B201900-U1-1001
	# budget inferred properties:
	a mef:CapitoloDiSpesa, mef:Component, mef:StructuralComponent ;
	mef:competenza 200000000000.00  ;
	mef:cassa 200000000000.00 ;
	mef:residui 0.00 ;
	
	# skos inferred properties:
	a skos:Concept ;
	skos:notation "U1-1001"^^mef:Notation ;
	skos:inScheme :B201900-U ;
	skos:broader :B201900-A1 ;
    
    # fr related inferred properties:
    a fr:Component, fr:StructuralComponent ;
    qb:dataSet :B201900 ;
    fr:concept :B201900-U1-1001;
	fr:refPeriod <http://reference.data.gov.uk/id/gregorian-interval/2019-01-01T00:00:00/P1Y> ;
	sdmx-attribute:unitMeasure <http://publications.europa.eu/resource/authority/currency/EUR> ;
    fr:amount 200000000000.00 ;
	
.

```

