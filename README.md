# DataMaps

DataMaps is a microservice specialized in displaying data on maps .

# Install

```console
git clone git@github.com:logipro-fr/datamaps.git
```

# To Contribute to Datamaps

## Requirements

* docker
* git


## Tests

### Unit tests

```console
bin/phpunit
```

Using Test-Driven Development (TDD) principles (thanks to Kent Beck and others), following good practices (thanks to Uncle Bob and others) and the great book 'DDD in PHP' by C. Buenosvinos, C. Soronellas, K. Akbary

### Integration tests
```console
bin/phpunit-integration
```

### Behavour tests
```console
bin/behat
```

## Manual tests

```console
./start
```
have a local look at http://172.17.0.1:10180/ in your navigator

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

## WSL
On windows with WSL for tests purpose ?

Either set HOST_IP in your .env.local at root project folder:
```bash
HOST_IP=0.0.0.0
```

or use start ip parameter:

```console
./start --ip 0.0.0.0
ip address show eth0
```
You will get something like :
```console
eth0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc mq state UP group default qlen 1000
    link/ether 00:15:5d:11:7a:e8 brd ff:ff:ff:ff:ff:ff
    inet 172.27.110.178/20 brd 172.27.111.255 scope global eth0
       valid_lft forever preferred_lft forever
    inet6 fe80::215:5dff:fe11:7ae8/64 scope link 
       valid_lft forever preferred_lft foreve
```

Pick <your adress IP> in the example it is 172.27.110.178
have a local look at http://<your adress IP>:50002/ in your navigator

```console
./stop
```

