# ChalkboardEducationV2

This project is splited in two directories:

- `/back` : Symfony app backend
- `/front` : Vue.js / webpack app frontend

## Front

``` bash
$ cd front

# install dependencies
$ npm install

# serve with hot reload at localhost:8080
$ npm run dev

# build for production with minification
$ npm run build

# build for production and view the bundle analyzer report
$ npm run build --report

# run unit tests
$ npm run unit

# run e2e tests
$ npm run e2e

# run all tests
$ npm test
```

You should access the front via http://localhost:8080/

For detailed explanation on how things work, checkout the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).

## Back

> Note: The `$` stands for your machine CLI, while the `⇒` stands for the VM CLI

    $ cd back

### Requirements

* Make
* [VirtualBox 5.0.20+](https://www.virtualbox.org/wiki/Downloads)
* [Vagrant 1.8.4+](https://www.vagrantup.com/downloads.html)
* [Vagrant Landrush 1.0.0+](https://github.com/vagrant-landrush/landrush)

### Setup

Clone the project in your workspace, and launch setup

    $ make setup

You should access the back via http://chalkboardeducationv2.dev/app_dev.php

### Usage

Start/Stop/Ssh

    $ vagrant up/halt/ssh

Build

    ⇒ make build

Admin

* [MailHog](http://chalkboardeducationv2.dev:8025)
* [Supervisor](http://chalkboardeducationv2.dev:9001)
* [RTail](http://chalkboardeducationv2.dev:8888)
* [OPcache Dashboard](http://chalkboardeducationv2.dev:2013)
* [PhpMyAdmin](http://chalkboardeducationv2.dev:1979)
* [PhpPgAdmin](http://chalkboardeducationv2.dev:1980)
* [PhpRedisAdmin](http://chalkboardeducationv2.dev:1981)
* [MongoExpress](http://chalkboardeducationv2.dev:8081)
* [Elasticsearch](http://chalkboardeducationv2.dev:9200/_plugin/head/)
* [Ngrok](http://chalkboardeducationv2.dev:4040)
* [InfluxDB](http://chalkboardeducationv2.dev:8083)
