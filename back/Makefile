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

###########
# Install #
###########

## Install application
install:
	composer install --verbose
	bin/console doctrine:database:create --if-not-exists
	yarn install

install@test: export SYMFONY_ENV = test
install@test:
	# Composer
	composer install --verbose --no-progress --no-interaction
	# Doctrine
	bin/console doctrine:database:drop --force --if-exists
	bin/console doctrine:database:create --if-not-exists
	bin/console doctrine:schema:update --force

install@staging: export SYMFONY_ENV = prod
install@staging:
	# Composer
	composer install --verbose --no-progress --no-interaction --prefer-dist --optimize-autoloader
	# Yarn
	yarn install
	# Symfony cache
	bin/console cache:warmup --no-debug

install@prod: export SYMFONY_ENV = prod
install@prod:
	# Composer
	composer install --verbose --no-progress --no-interaction --prefer-dist --optimize-autoloader --no-dev
	# Yarn
	yarn install
	# Symfony cache
	bin/console cache:warmup --no-debug

##########
# Build #
##########

build:
	./node_modules/.bin/encore dev
watch:
	./node_modules/.bin/encore dev --watch
build@staging:
	./node_modules/.bin/encore dev
build@prod:
	./node_modules/.bin/encore production

##########
# Custom #
##########

init-db:
	bin/console doctrine:schema:drop --force; \
	bin/console doctrine:schema:create; \
	bin/console doctrine:fixtures:load -n; \

##########
# Tests  #
##########

test:
	phpunit
	vendor/bin/behat
