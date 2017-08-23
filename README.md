# ChalkboardEducationV2

This project is splited in two directories:

- `/back` : Symfony app backend
- `/front` : React / Apollo Graphql / webpack app frontend

## Front

### Requirements

You can install Yarn through the Homebrew package manager. This will also install Node.js if it is not already installed.

    $ brew install yarn

### Setup

``` bash
$ cd front

# install dependencies
$ yarn install

# serve with hot reload at localhost:3000
$ yarn start

# build for production with minification
$ yarn build

# run tests
$ yarn test
```

You should access the front via http://localhost:3000/

To login as a user:

http://localhost:3000/login/api-key-token-user-1

## Back

> Note: The `$` stands for your machine CLI, while the `⇒` stands for the VM CLI

    $ cd back

And consult the [README.md](back/README.md) for the back to setup the environment
