# Load environment variable safely
-include .env
APP_ENV ?= production


# Load database variables safely
-include php-fpm/.env
DB_USER ?= postgres
DB_PASSWORD ?= postgres
DB_NAME ?= postgres


# File args
DOCKER_COMPOSE := -f docker-compose.prod.yml
ENV := --env-file .env --env-file php-fpm/.env


# Override of the docker-compose on development
ifneq ($(APP_ENV),production)
    $(info Using development environment -> adding docker-compose.dev.yml)
    DOCKER_COMPOSE += -f docker-compose.dev.yml
endif


# Build containers
.PHONY: build upd stop down restart debug

build:
	$(eval SERVICE := $(word 2, $(MAKECMDGOALS)))
	$(if $(SERVICE), \
		docker-compose $(DOCKER_COMPOSE) $(ENV) build $(SERVICE), \
		docker-compose $(DOCKER_COMPOSE) $(ENV) build \
	)

upd:
	$(eval SERVICE := $(word 2, $(MAKECMDGOALS)))
	$(if $(SERVICE), \
		docker-compose $(DOCKER_COMPOSE) $(ENV) up -d --build --remove-orphans $(SERVICE), \
		docker-compose $(DOCKER_COMPOSE) $(ENV) up -d --build --remove-orphans \
	)

stop:
	$(eval SERVICE := $(word 2, $(MAKECMDGOALS)))
	$(if $(SERVICE), \
		docker-compose $(DOCKER_COMPOSE) $(ENV) stop $(SERVICE), \
		docker-compose $(DOCKER_COMPOSE) $(ENV) stop \
	)

down:
	docker-compose $(DOCKER_COMPOSE) $(ENV) down

restart:
	$(eval SERVICE := $(word 2, $(MAKECMDGOALS)))
	$(if $(SERVICE),,$(error you must specify a container to restart, for example: make restart backend))
	docker-compose $(DOCKER_COMPOSE) $(ENV) restart $(SERVICE)

debug:
	$(eval SERVICE := $(word 2, $(MAKECMDGOALS)))
	$(if $(SERVICE),,$(error you must specify a container to debug, for example: make debug backend))
	docker-compose $(DOCKER_COMPOSE) $(ENV) run --rm --entrypoint sh $(SERVICE)


# Shell access containers
.PHONY: nginx php-fpm postgres

nginx:
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec nginx /bin/sh

php-fpm:
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec php-fpm /bin/sh

postgres:
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec -e PGPASSWORD=$(DB_PASSWORD) postgres psql -U $(DB_USER) -d $(DB_NAME)


# Development
.PHONY: install phpstan pint phpunit

install:
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec php-fpm composer install
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec php-fpm php bin/doctrine.php orm:schema:create
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec php-fpm php bin/console.php load:fixtures

phpstan:
	$(eval ARGS := $(wordlist 2, $(words $(MAKECMDGOALS)), $(MAKECMDGOALS)))
	$(eval CI :=)
	$(foreach arg,$(ARGS),\
		$(if $(filter ci,$(arg)),\
			$(eval CI := --no-progress)))
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec -T php-fpm bin/phpstan.sh $(CI)

pint:
	$(eval ARGS := $(wordlist 2, $(words $(MAKECMDGOALS)), $(MAKECMDGOALS)))
	$(eval CI :=)
	$(foreach arg,$(ARGS),\
		$(if $(filter ci,$(arg)),\
			$(eval CI := --test)))
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec -T php-fpm vendor/bin/pint --ansi --config=pint.json $(CI)

phpunit:
	$(eval ARGS := $(filter-out $@,$(MAKECMDGOALS)))
	$(eval FINAL_ARGS :=)
	$(foreach arg,$(ARGS),\
		$(if $(filter coverage,$(arg)),\
			$(eval FINAL_ARGS += --coverage),\
			$(eval FINAL_ARGS += --filter=$(arg))\
		)\
	)
	docker-compose $(DOCKER_COMPOSE) $(ENV) exec -T php-fpm bin/phpunit.sh $(FINAL_ARGS)

# Catch-all pattern rule to prevent Make from complaining about unknown targets
%:
	@:
