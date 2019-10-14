# data.budget.gov.it knowledge graph


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

This project contains all needed for setting up and update a knowledge base ready to be used by the budget.g0v.it APIs.

The data ingestion process is managed by the [LinkedData.Center SDaaS platform community edition](https://github.com/linkeddatacenter/sdaas-ce) according [KEES](http://linkeddata.center/kees) specifications. 

knowledge base build process requires to:

- edit files in the *data* directory to describe metadata and static stuffs
- develop the *gateways* for transforming MEF open data resources in linked data. See [gateways doc.](gateways/README.md)
- write *axioms* and rules to generate new data. See [axioms doc.](axioms/README.md)
- edit the *build script* that drives the data ingestion process.
- run sdaas

### debugging the build script with docker

the test of the build script require dat least 2GB of ram available to the docker machine:

```
docker run -d -p 9999:8080 -v $PWD/.:/workspace --name kb linkeddatacenter/sdaas-ce
docker exec -ti kb bash
apk --no-cache add php7 php7-json
sdaas --debug -f build.sdaas --reboot
# Access the workbench pointing browser to http://localhost:9999/sdaas
exit
docker rm -f kb
```

logs info and debug traces will be created in .cache directory


 
### publishing  the knowledge base

You can pack data and services with :

```
docker build . -t sdaas
docker run -d -p 8889:8080 --name datastore sdaas
```

The resulting container will provide a read only distribution of the whole knowledge base in a stand-alone graph database with a sparql interface.


## Directory structure

- the **build.sdaas** file is a script to populate the knowledge base from scratch. It requires sdaas platform community edition 2.0+
- the **axioms** directory contains rules to be processed during reasoning windows.
- the **data** directory contains local data files
- the **gateways** directory contains the code to transform raw data in linked data
- the **scripts** directory contains the code for local extensions to sdaas
- the **.cache** temporary directory that contains logs and debugging info. Not saved in repo.

