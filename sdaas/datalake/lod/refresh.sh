#!/bin/sh

# Load reference years from reference.data.gov.uk
for i in 2010 2011 2012 2013 2014 2015 2016 2017 2018 2019 2020 2021 2022
do
  curl -L "http://reference.data.gov.uk/doc/gregorian-interval/$i-01-01T00:00:00/P1Y" -o "year_$i.rdf"
done


# Load currency data from dbpedia

curl -L "http://dbpedia.org/data/Euro.n3" -o eur.ttl
curl -L "http://dbpedia.org/data/United_States_dollar.n3" -o usd.ttl
curl -L "http://dbpedia.org/data/Pound_sterling.n3" -o gbp.ttl
