# ChalkboardEducationV2

This project is splited in two directories:

- `/back` : Symfony app backend
- `/front` : Vue.js / webpack app frontend

## Front

### Requirements

You can install Yarn through the Homebrew package manager. This will also install Node.js if it is not already installed.

    $ brew install yarn

### Setup

``` bash
$ cd front

# install dependencies
$ yarn install

# serve with hot reload at localhost:8080
$ yarn run dev

# build for production with minification
$ yarn run build

# build for production and view the bundle analyzer report
$ yarn run build --report

# run unit tests
$ yarn run unit

# run e2e tests
$ yarn run e2e

# run all tests
$ yarn test
```

You should access the front via http://localhost:8080/

For detailed explanation on how things work, checkout the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).

## Back

> Note: The `$` stands for your machine CLI, while the `⇒` stands for the VM CLI

    $ cd back

And consult the [README.md](back/README.md) for the back to setup the environment
