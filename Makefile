.DEFAULT_GOAL := help
SHELL := /bin/bash

COMPOSE_FILE = .deploy/docker-compose.dev.yaml
DOCKER_COMPOSE = docker compose -f $(COMPOSE_FILE)
DOCKER_EXEC = $(DOCKER_COMPOSE) exec app_fpm

# Define environment variables
export COMPOSER_HOME = .docker/composer
export PATH := $(COMPOSER_HOME)/vendor/bin:$(PATH)

# Colors
COLOR_RESET = \033[0m
COLOR_BOLD = \033[1m
COLOR_GREEN = \033[92m
COLOR_CYAN = \033[96m
COLOR_MAGENTA = \033[95m

# Separator
define SEPARATOR

endef

# Help command
help:
	@echo -e "$(COLOR_CYAN)Welcome to the club$(COLOR_RESET)"
	@echo ""
	@echo -e "$(COLOR_BOLD)Available commands:$(COLOR_RESET)"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  $(COLOR_CYAN)%-20s$(COLOR_RESET) %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init:
		docker network create app_network
		cp .env.example .env
		make update

update: ## Rebuild and up the prod container
		docker compose -f .deploy/docker-compose.dev.yaml up -d --build

shell: ## Start the Docker containers
		docker exec -it app_fpm sh

up: ## Start the Docker containers
		@$(DOCKER_COMPOSE) up -d

down: ## Stop and remove Docker containers
		@$(DOCKER_COMPOSE) down

restart: docker-down docker-up ## Restart Docker containers

logs: ## View Docker container logs
		@$(DOCKER_EXEC) tail -f storage/logs/laravel-$(shell date +'%Y-%m-%d').log

docker-logs: ## View Docker container logs
		docker logs -f app_fpm

container-logs: ## View Docker container logs
		@$(DOCKER_COMPOSE) logs -f

composer-install: ## Install Composer dependencies
		@$(DOCKER_EXEC) composer install

composer-update: ## Update Composer dependencies
		@$(DOCKER_EXEC) composer update

# Artisan targets
partisan: ## Run an Artisan command (e.g., make partisan cmd="optimize")
		@$(DOCKER_EXEC) php artisan $(cmd)

# Artisan targets
migrate: ## Run an Artisan command (e.g., make migrate cmd="fresh")
		@$(DOCKER_EXEC) php artisan migrate:$(cmd)

.PHONY: help docker-up docker-down docker-restart docker-logs composer-install composer-update artisan-command octane-start octane-stop test-run lint-run fix
