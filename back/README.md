# Chalkboard Education Back

## Development

> Note: The `$` stands for your machine CLI, while the `⇒` stands for the VM CLI

### Requirements

* Make
* [VirtualBox 5.0.20+](https://www.virtualbox.org/wiki/Downloads)
* [Vagrant 1.8.4+](https://www.vagrantup.com/downloads.html)
* [Vagrant Landrush 1.0.0+](https://github.com/vagrant-landrush/landrush)

### Setup

Clone the project in your workspace, and launch setup

    $ make setup

You should access the project via http://api.chalkboardeducation.dev/app_dev.php

### Usage

Start/Stop/Ssh

    $ vagrant up/halt/ssh

Build

    ⇒ make build

Admin

* [MailHog](http://api.chalkboardeducation.dev:8025)
* [RTail](http://api.chalkboardeducation.dev:8888)
* [OPcache Dashboard](http://api.chalkboardeducation.dev:2013)
* [PhpMyAdmin](http://api.chalkboardeducation.dev:1979)
