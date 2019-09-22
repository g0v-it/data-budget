Change Log
===========
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [1.2.0]

### Added

- Guzzle PSR7 implementation

### Changed

- Default emitter now uses SapiStreamEmitter
- composer coverage just creates static html files (no execution of server)


## [1.1.0] 2018-10-12

### Added

- ContainerAware trait
- PSR-11 implementation in Application
- BootableProviderInterface
- services 'relay.pimpleResolver' and 'relay.factory' to RelayServiceProvider

### Changed

- Run method always calls boot
- if no uSilex.responseEmitter function provided, the body of the response is emitted
- updated composer,json
- code formatting

### Fixed

- Removed Application Constructor
- Remove dependency from ['debug'] in Psr7 DiactorosServiceProvider
- The signature for uSilex.responseEmitter was wrong
- The signature for uSilex.exceptionHandler  was wrong

## [1.0.1] 2018-10-02

### Added

- CHANGELOG.md
- support of 'handler.queue' in ZendPipeServiceProvider

### Changed

- example structure
- documentation refined


## 1.0.0 2018-10-01

First release. 


[Unreleased]: https://github.com/linkeddatacenter/uSilex/compare/1.2.0...HEAD
[1.2.0]: https://github.com/linkeddatacenter/uSilex/compare/1.2.0...1.1.0
[1.1.0]: https://github.com/linkeddatacenter/uSilex/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/linkeddatacenter/uSilex/compare/1.0.0...1.0.1