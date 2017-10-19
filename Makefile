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
	if [ -d  "./back/var/cache" ]; then rm -rf ./back/var/cache; fi;
	if [ -d "./back/var/logs" ]; then rm -rf ./back/var/logs; fi;
	if [ -d "./back/var/sessions" ]; then rm -rf ./back/var/sessions; fi;
	vagrant up --no-provision
	vagrant provision
	vagrant ssh -- "cd /srv/app && make install && make build"

## Update environment
update: export ANSIBLE_TAGS = manala.update
update:
	vagrant provision

## Update ansible
update-ansible: export ANSIBLE_TAGS = manala.update
update-ansible:
	vagrant provision --provision-with ansible

## Provision environment
provision: export ANSIBLE_EXTRA_VARS = {"manala":{"update":false}}
provision:
	vagrant provision --provision-with app

## Provision nginx
provision-nginx: export ANSIBLE_TAGS = manala_nginx
provision-nginx: provision

## Provision php
provision-php: export ANSIBLE_TAGS = manala_php
provision-php: provision

## Provision files
provision-files: export ANSIBLE_TAGS = manala_files
provision-files: provision

###########
# Install #
###########

## Install applications
install: install/back install/front

install/back:
	cd back && make install

install/front:
	cd front && make install

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

build/back@prod:
	cd back && make build@prod

## Build front
build/front:
	cd front && make build

watch: watch-back
	cd back && make watch

##########
# Custom #
##########

init-db: init-db/back

init-db/back:
	cd back && make init-db

##########
# Deploy #
##########


##########
# Deploy #
##########

## Deploy applications (Staging)
deploy@staging: deploy/back@staging deploy/front@staging

deploy/back@staging:
	ansible-playbook ansible/deploy.yml --inventory-file=ansible/hosts --limit=deploy_staging

deploy/front@staging:
	ansible-playbook ansible/deploy.yml --inventory-file=ansible/hosts --limit=deploy_staging

## Deploy applications (Production)
deploy@prod: deploy/back@prod deploy/front@staging

deploy/back@prod:
	ansible-playbook --inventory-file=ansible/hosts.yml ansible/deploy.yml \
		--limit=deploy_production_back_chalkboard

deploy/front@prod:
	ansible-playbook --inventory-file=ansible/hosts.yml ansible/deploy.yml \
		--limit=deploy_production_front_chalkboard

#########
# Split #
#########

## Split the monolithic repository to many repositories according to `.gitsplit.yml` config
split:
	gitsplit

##########
# Tests  #
##########

test:
	cd back && phpunit
	cd back && vendor/bin/behat
