.SILENT:
.PHONY: build test

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Help
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

###############
# Environment #
###############

## Setup environment & Install & Build application
setup:
	if [ -d "./back/vendor" ]; then rm -rf ./back/vendor; fi;
	if [ -d "./back/node_modules" ]; then rm -rf ./back/node_modules; fi;
	install 

###########
# Install #
###########

## Install applications
install: install/back install/front

install/back:
	cd back && make install

install/back@production:
	cd back && make install@production

install/front:
	cd front && make install@production

install/front@production:
	cd front && make install@production

##########
# Build #
##########

## Build applications
build: build/back build/front

## Build back
build/back:
	cd back && make build

build/back@staging:
	cd back && make build@staging

build/back@production:
	cd back && make build@production

## Build front
build/front:
	cd front && make build

#########
# Split #
#########

## Split the monolithic repository to many repositories according to `.gitsplit.yml` config
split@all: split/back split/front

split:
	gitsplit

split/back:
	splitsh-lite --prefix=back/ --target=origin/master

split/front:
	splitsh-lite --prefix=front/ --target=origin/master

##########
# Tests  #
##########

test:
	cd back && vendor/bin/phpunit
	cd back && vendor/bin/behat

##########
# Deploy #
##########

## Deploy applications (Production)
deploy: deploy/back deploy/front

## Deploy back application (Production)
deploy/back:
	ansible-playbook --inventory-file=ansible/hosts.yml ansible/deploy.yml --limit=deploy_production_back_chalkboard

## Deploy front application (Production)
deploy/front:
	ansible-playbook --inventory-file=ansible/hosts.yml ansible/deploy.yml --limit=deploy_production_front_chalkboard