Change Log
===========
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased

## 4.0.0

Allineato a legge di bilancio 2021. Confronto con leggi di bilancio dal 2017

## 3.2.2

Rimosso il disegno di legge e evidenziate le differenze tra anni precedenti.


## 3.2.1

n.b. questa versione mantiene sia il disegno di legge che la legge per evidenziare
le differenze intercorse tra le due aprovazioni.

E' prevista la versione 3.2.2 in cui il disegno di legge sarà rimosso.

### Added

- Legge di bilancio 2020 releases (keept disegno di legge)


### Changed

- STRINGA LEGENDA TREND (DA VARIARE ALLA VERSIONE 3.2.2)


## 3.2.0

### Added

- checksum

### Changed

- Aligned with the last release of mef ontology
- refactory
- sample queries

### Removed

- dependencies to skos and fr



## 3.1.0

### Added

- Aligned with the last release of mef ontology
- The knowledge base facts now aligned with the MEF "piano di gestione" level
- added support to "note di bilancio azioni" dataset

### Changed

- complete code refactory to support the full mef ontology

### Fixed

- Some bug fixed


## 3.0.0-RC2

- Disegno di Legge di bilancio 2020
- cambiata notation per miisteri
- cambiata policy label
- rimossa descrizione programmi (in attesa integrazioni da note di bilancio)


## 3.0.0-RC1

A major release with a complete project refactory. 

- removed API (now you use LODMAP2D-api)
- Aligned with new releases of mef, fr, and bgo ontologies.
- compatible with LODMAP2D and LODMAP2D-api project
- support to mef-ap profile 1.0.0RC1
- support to latest release of BGO 1.0.0RC1

**WARNING this release IS NOT compatible with web-budget 2.x**


## 2.1.1

Bug fixing release

## 2.1.0

Extends the 2.0.x features managing five years of financial reports at mef. 
The resulting knowlege base can support https://budget.g0v.it/ and other kind of data visualizations that sperads variuos budget versions.

## 2.0.0

A major release with a complete refactory. Not compatible with 1.x releases

- Legge di bilancio 2019 approvata
- compatible with web-budget v. 2.x
- refactory struttura knowledge base

**WARNING this release IS NOT compatible wit web-budget 1.x**


## 1.0.0

First official release
Disegno di legge del bilancio 2019.

