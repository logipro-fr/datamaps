# DataMaps

DataMaps is a microservice specialized in displaying data on maps.

# Install

```console
git clone git@github.com:logipro-fr/datamaps.git
```

# To Contribute to Datamaps

## Requirements

* docker
* git


## Unit test

```console
bin/phpunit
```

Using Test-Driven Development (TDD) principles (thanks to Kent Beck and others), following good practices (thanks to Uncle Bob and others) and the great book 'DDD in PHP' by C. Buenosvinos, C. Soronellas, K. Akbary

## Manual tests

```console
./start
```
have a local look at http://127.0.0.1:11080/ in your navigator

```console
./stop
```

## Quality

* phpcs PSR12
* phpstan level 9
* coverage 100%
* infection MSI >99%

Quick check with:
```console
./codecheck
```

Check coverage with:
```console
bin/phpunit --coverage-html var
```
and view 'var/index.html' with your browser

Check infection with:
```console
bin/infection
```
and view 'var/infection.html' with your browser

## CLONE TO Gitlab écrase version modifiée sur gitlab
