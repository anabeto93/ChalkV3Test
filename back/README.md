# Chalkboard Education Back

## Development

> Note: The `$` stands for your machine CLI, while the `⇒` stands for the VM CLI

### Requirements

* Make
* [VirtualBox 5.0.20+](https://www.virtualbox.org/wiki/Downloads)
* [Vagrant 1.8.4+](https://www.vagrantup.com/downloads.html)
* [Vagrant Landrush 1.0.0+](https://github.com/vagrant-landrush/landrush)

### Setup

Launch setup to install the VM for the back project

    $ make setup

You should access the project via http://api.chalkboardeducation.dev/app_dev.php/admin/

### Usage

Launch vagrant box, and ssh into it

        $ vagrant up
        $ vagrant ssh

Build admin assets

        ⇒ make build

Load fixtures into DB

        ⇒ make init-db

Tests

        ⇒ make test

### Tools

Enable/Disable php xdebug

        ⇒ elao_php_xdebug [on|off]

* [MailHog](http://api.chalkboardeducation.vm:8025)
* [RTail](http://api.chalkboardeducation.vm:8888)
* [OPcache Dashboard](http://api.chalkboardeducation.vm:2013)
* [PhpMyAdmin](http://api.chalkboardeducation.vm:1979)
