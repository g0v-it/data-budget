# Contributing Guide

This project adheres to [The Code Manifesto](http://codemanifesto.com) as its guidelines for contributor interactions.

## How to contribute

This is a collaborative effort. We welcome all contributions submitted as pull requests.

(Contributions on wording & style are also welcome.)

### Bugs

A bug is a demonstrable problem that is caused by the code in the repository. Good bug reports are extremely helpful – thank you!

Please try to be as detailed as possible in your report. Include specific information about the environment – version of PHP, etc, and steps required to reproduce the issue.

### Use docker

To be sure to use a consistent development environment the usage of [docker](https://www.docker.com/) is strongly suggested.


### Pull Requests

Good pull requests – patches, improvements, new features – are a fantastic help. Before create a pull request, please follow these instructions:

* The code must follow the [PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md). 
Run `docker run --rm -ti -v $PWD/.:/app composer composer cs-fix` to fix your code before commit.

- Write tests
- Document any change in `README.md` and `CHANGELOG.md`
- One pull request per feature. If you want to do more than one thing, send multiple pull request

### Running unit tests

```
docker run --rm -ti -v $PWD/.:/app composer composer test
```


### Running functional tests

```bash
docker run --rm -ti -p 8000:8000 -v $PWD/.:/app composer composer functional
```

Install [postman](https://www.getpostman.com) on your workstation

Import tests/system/ekb3_api_smoke_test.postman_collection.json and ekb3_development.postman_environment.json then  call the postman runner.

type `ctrl-c` to stop server.


### Test coverage

To get code html coverage information execute the following command:

```sh
docker run --rm -ti  -v $PWD/.:/app composer composer coverage
```

See coverage test report pointing your browser to coverage/index.html file


