PHP APIs for LODMAP2D application
=================================

This SDK is part of LODMAP2D project. Prlease refer to LODMAP2D license and readme file.

These APIs query an RDF graph database containing data according with [BubbleGraph Ontology](https://github.com/linkeddatacenter/LODMAP-ontologies/tree/master/v1/bgo) 
exposing a linked data endpoint compliant with the [LODMAP2D application](https://github.com/linkeddatacenter/LODMAP2D) data configuration:


resource                       | payload
------------------------------ | -------------------
/app.ttl                       | common application layout data.
/account/*account_id*.ttl      | data for *account_id* account
/partition/*partition_id*.ttl  | data for *partition_id* partition
/credits.ttl                   | contains credits data 
/terms.ttl                     | contains terms & conditions data 
/accounts.ttl                  | contains an index of all accounts, including just information used to render account tooltips


For eKB APIs documentation refer to http://LinkedData.Center/api


## Developers quick start

Install [docker](https://www.docker.com/) and run

```
docker run --rm -ti -v $PWD/.:/app composer composer install
docker run --rm -ti -v $PWD/.:/app composer composer cs-fix

docker run -it --rm  -p 8000:8000 -v $PWD/.:/app -w /app php:7.2-cli php -S "0.0.0.0:8000" index.php
```

