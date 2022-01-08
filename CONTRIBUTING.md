# Programming data-budget


Contributions to this project are always welcome. You make our lives easier by
sending us your contributions through pull requests.

Pull requests for bug fixes must be based on the current stable branch whereas
pull requests for new features must be based on `master`.


## Pull Request Process

1. Ensure any install or build dependencies are removed before the end of the layer when doing a 
   build.
2. Update the README.md with details of changes to the interface, this includes new environment 
   variables, exposed ports, useful file locations and container parameters.
3. Edit [unreleased] tag in CHANGELOG.md and save your changes, additions, fix and delete to what this version that this
   Pull Request would represent. The versioning scheme we use is [SemVer](http://semver.org/).
4. You may merge the Pull Request in once you have the sign-off of two other developers, or if you 
   do not have permission to do that, you may request the second reviewer to merge it for you.

We are trying to follow the [PHP-FIG](http://www.php-fig.org)'s standards, so
when you send us a pull request, be sure you are following them.

Please see http://help.github.com/pull-requests/.

We kindly ask you to add following sentence to your pull request:

“I hereby assign copyright in this code to the project, to be licensed under the same terms as the rest of the code.”

## Updating the knowledge base

This project contains all needed for setting up and update a knowledge base ready to be used by the budget.g0v.it 

The data ingestion process is managed by the [LinkedData.Center SDaaS platform community edition](https://github.com/linkeddatacenter/sdaas-ce) according [KEES](http://linkeddata.center/kees) specifications. 

knowledge base build process requires to:

- edit files in the *data* directory to describe web app configuration data and other static stuffs
- develop the *gateways* for transforming raw data resources in linked data. See [gateways doc.](gateways/README.md)
- write *axioms* and rules to generate new data. See [axioms directory](axioms)
- edit the *build script* that drives the data ingestion process.
- run sdaas


### Build your skills

The following skills are suggested in developing this project:

- Git
- Docker
- Semantic Web fundamentals:
    - RDF 1.1 primer
    - Turtle 1.1 primer
    - Sparql 1.1 (for axioms develoment)
- base php 7 ( for gateway development)
- SDaaS Platform Community edition

Useful learning starting points:

- https://guides.github.com/
- https://docs.docker.com/get-started/
- http://rubenverborgh.github.io/WebFundamentals/semantic-web/
- https://www.php.net/manual/en/tutorial.php
- https://github.com/linkeddatacenter/sdaas-ce


### Start the SDaaS platform

To start sdaas cli:

```
docker run -d -p 9999:8080 -e JAVA_OPTS="-Xmx2g" -v ${PWD}:/workspace --name kb linkeddatacenter/sdaas-ce:2.4.0
docker exec -ti kb bash
apk --no-cache add php7 php7-json php7-mbstring
chmod +x gateways/*.php axioms/03-bgo-mapping/*.php
```

Access the SDaaS workbench pointing browser to http://localhost:9999/sdaas


### Edit local data

There are some data file local to this project in the *data* directory:

- **app.ttl** contains the bgo static objects (menus, views and UI components) 
- **kees.ttl** contains some metadata about the knowledge base itself



### Stand alone gateways development and testing

Gateways that transform the raw data provided by the Italian government (BDAP catalog) into linked data compliant with the [g0v financial report application profile for MEF data](../mef-ap) (mef-ap)

Gateways are simple stand-alone php7 scripts that read a csv stream row by row from STDIN and 
write RDF turtle statements to STDOUT. Following gateways are available:

See more in [README file in gateways directory](gateways/READEME.md)

### Axioms development

SDaaS recognizes three types of axioms containers:

- **.construct** file that contains a SPARQL QUERY 1.1 construct directive. The Construct query is evaluated and the result pumped in the knowledge graph. This axiom type is not used in data-budget project.
- **.update** a file containing a set of SPARQL QUERY update. 
- **.reasoner** a bash script that execute any process finalized to materialize inferences in the knowledge graph. Usually a reasoner extract some 
from the knowledge graph, launch a program that elaborate the data creating some insert statements the finally are executed.
In data-budget a reasoner is defined to generate the  bgo tag cloud from the  Account descriptions
 
Axioms containers must be executed in alphabetic order. The `SD_EVAL_RULESET` SDaaS command automates the whole process.


### debugging the build script

The knowledge build script (build.sdaas) is bash script that runs under the control of the SDaaS platform.
The test of the build script require dat least 2GB of ram available to the docker machine:

```
sdaas -f build.sdaas --reboot
```

logs info and debug traces will be created in .cache directory . The  `--debug` option increases the logging features.


### Stop the SDaaS platform
To exit cli and free docker resource:

```
rm -rf .cache
exit
docker rm -f kb
```

### publishing the knowledge base

You can pack data and services with :

```
docker build -t copernicani/data-budget-sdaas --no-cache .
docker tag copernicani/data-budget-sdaas copernicani/data-budget-sdaas:x.y.z
docker push copernicani/data-budget-sdaas
docker push copernicani/data-budget-sdaas:x.y.z
```

for x.y.z use [SemVer](http://semver.org/) specification.

The resulting container will provide a read only distribution of the whole knowledge base in a stand-alone graph database with a sparql interface.


## Directory structure

- the **build.sdaas** file is a script to populate the knowledge base from scratch. It requires sdaas platform community edition 2.0+
- the **axioms** directory contains rules to be processed during reasoning windows.
- the **data** directory contains local data files
- the **gateways** directory contains the code to transform raw data in linked data
- the **tests** directory contains data and axiom to test programs and the knowledge garph integrity
- the **.cache** a temporary directory created by sdaas command that contains logs and debugging info. Not saved in repo.

