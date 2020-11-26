##########################

EXEC_PHP            = php
DOCKER              = docker
DOCKER_COMPOSE      = docker-compose

DOCKER_COMPOSE_UP   = $(DOCKER_COMPOSE) up -d
DOCKER_COMPOSE_EXEC = $(DOCKER_COMPOSE) exec
EXEC_WWW            = $(DOCKER_COMPOSE_UP) www && $(DOCKER_COMPOSE_EXEC) www

##########################

# DOCKER-COMPOSE RELATED COMMANDS
dc-up:
	$(DOCKER_COMPOSE) up -d

dc-stop:
	$(DOCKER_COMPOSE) stop

dc-down:
	$(DOCKER_COMPOSE) down

dc-build:
	$(DOCKER_COMPOSE) build

dc-bash: ## Connect to the application container
	$(DOCKER_COMPOSE) exec www bash

##########################

# COMPOSER COMMANDS
app-composer-install:
	$(EXEC_WWW) php /usr/local/bin/composer install

app-composer-update:
	$(EXEC_WWW) php /usr/local/bin/composer update

app-composer-require:
	$(EXEC_WWW) php /usr/local/bin/composer require $(dep)

app-composer-remove:
	$(EXEC_WWW) php /usr/local/bin/composer remove $(dep)

##########################

# DOCTRINE COMMANDS
app-database-init: app-doctrine-migration app-dsu-f

app-dsu-f:
	$(EXEC_WWW) php bin/console doctrine:schema:update -f

app-doctrine-migration:
	$(EXEC_WWW) php bin/console doctrine:migration:migrate --no-interaction

##########################

# PHPUNIT
test-start:
	$(EXEC_WWW) php bin/phpunit

##########################
