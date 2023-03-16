#-----------------------------------------------------------
# Docker
#-----------------------------------------------------------

# Wake up docker containers
up:
	docker-compose up -d

# Shut down docker containers
down:
	docker-compose down

# Show a status of each container
status:
	docker-compose ps

# Status alias
s: status

# Show logs of each container
logs:
	docker-compose logs

# Restart all containers
restart: down up

# Build and up docker containers
build:
	docker-compose up -d --build

build-prod:
	docker-compose -f production.yml up -d --build
	docker-compose exec -T php composer install --optimize-autoloader --no-dev --no-interaction
	docker-compose exec -T php php artisan config:cache
	docker-compose exec -T php php artisan route:cache
	docker-compose exec -T php php artisan storage:link
	docker-compose exec -T php php artisan migrate --force

# Build containers with no cache option
build-no-cache:
	docker-compose build --no-cache

# Build and up docker containers
rebuild: down build

# Run terminal of the php container
php:
	docker-compose exec php bash


#-----------------------------------------------------------
# Logs
#-----------------------------------------------------------

# Clear file-based logs
logs-clear:
	sudo rm docker/nginx/logs/*.log
	sudo rm storage/logs/*.log


#-----------------------------------------------------------
# Database
#-----------------------------------------------------------

# Run database migrations
db-migrate:
	docker-compose exec php php artisan migrate

# Migrate alias
migrate: db-migrate

# Run migrations rollback
db-rollback:
	docker-compose exec php php artisan migrate:rollback

# Rollback alias
rollback: db-rollback

# Run seeders
db-seed:
	docker-compose exec php php artisan db:seed

# Fresh all migrations
db-fresh:
	docker-compose exec php php artisan migrate:fresh

# Fresh all migrations and run seeder
db-migrate-seed:
	docker-compose exec php php artisan m:fresh --seed

# Migrate seed alias
migrate-seed: db-migrate-seed

# Dump database into file
db-dump:
	docker-compose exec postgres pg_dump -U app -d app > docker/postgres/dumps/dump.sql


#-----------------------------------------------------------
# Redis
#-----------------------------------------------------------

redis:
	docker-compose exec redis redis-cli

redis-flush:
	docker-compose exec redis redis-cli FLUSHALL

redis-install:
	docker-compose exec php composer require predis/predis


#-----------------------------------------------------------
# Queue
#-----------------------------------------------------------

# Restart queue process
queue-restart:
	docker-compose exec php php artisan queue:restart


#-----------------------------------------------------------
# Testing
#-----------------------------------------------------------

# Run phpunit tests
test:
	docker-compose exec php vendor/bin/phpunit --order-by=defects --stop-on-defect

# Run all tests ignoring failures.
test-all:
	docker-compose exec php vendor/bin/phpunit --order-by=defects

# Run phpunit tests with coverage
coverage:
	docker-compose exec php vendor/bin/phpunit --coverage-html tests/report

# Run phpunit tests
dusk:
	docker-compose exec php php artisan dusk

# Generate metrics
metrics:
	docker-compose exec php vendor/bin/phpmetrics --report-html=tests/metrics app


#-----------------------------------------------------------
# Dependencies
#-----------------------------------------------------------

# Install composer dependencies
composer-install:
	docker-compose exec php composer install

# Update composer dependencies
composer-update:
	docker-compose exec php composer update


#-----------------------------------------------------------
# Tinker
#-----------------------------------------------------------

# Run tinker
tinker:
	docker-compose exec php php artisan tinker


#-----------------------------------------------------------
# Installation
#-----------------------------------------------------------

# Copy the Laravel environment file
env:
	cp .env.docker apps/.env

# Add permissions for Laravel cache and storage folders
permissions:
	sudo chmod -R 777 apps/bootstrap/cache
	sudo chmod -R 777 apps/storage

# Permissions alias
perm: permissions

# Generate a Laravel app key
key:
	docker-compose exec php php artisan key:generate --ansi

# Generate a Laravel storage symlink
storage:
	docker-compose exec php php artisan storage:link

# PHP composer autoload comand
autoload:
	docker-compose exec php composer dump-autoload

# Install the environment
install: build env composer-install key storage permissions migrate-seed


#-----------------------------------------------------------
# Git commands
#-----------------------------------------------------------

git-undo:
	git reset --soft HEAD~1

git-wip:
	git add .
	git commit -m "WIP"

#-----------------------------------------------------------
# Clearing
#-----------------------------------------------------------

# Shut down and remove all volumes
remove-volumes:
	docker-compose down --volumes

# Remove all existing networks (usefull if network already exists with the same attributes)
prune-networks:
	docker network prune

# Laravel
reinstall-laravel:
	sudo rm -rf apps
	mkdir apps
	docker-compose restart
	docker-compose exec php composer create-project --prefer-dist laravel/laravel .
# 	sudo chown ${USER}:${USER} -R apps
	sudo chmod -R 777 apps/bootstrap/cache
	sudo chmod -R 777 apps/storage
	sudo rm apps/.env
	cp .env.docker apps/.env
	docker-compose exec php php artisan key:generate --ansi
	docker-compose exec php composer require predis/predis
	docker-compose exec php php artisan --version