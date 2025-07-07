PHPUNIT = ./vendor/bin/phpunit
DOCKER_EXEC = docker exec
PHP_CONTAINER = shp-cart-php

start:
	docker-compose up -d

stop:
	docker-compose stop

restart:
	$(MAKE) stop && $(MAKE) start

build:
	docker-compose up --build -d

prepare:
	${DOCKER_EXEC} -it ${PHP_CONTAINER} composer install --no-interaction
	sleep 4
	${DOCKER_EXEC} -it ${PHP_CONTAINER} php bin/console cache:clear --no-warmup

init-db:
	${DOCKER_EXEC} -it ${PHP_CONTAINER} php bin/console doctrine:migrations:migrate --no-interaction
	${DOCKER_EXEC} -it ${PHP_CONTAINER} php bin/console doctrine:fixtures:load --append
	${DOCKER_EXEC} -it ${PHP_CONTAINER} php php bin/console messenger:setup-transports

migrations:
	${DOCKER_EXEC} -it ${PHP_CONTAINER} php bin/console doctrine:migrations:migrate

ssh:
	${DOCKER_EXEC} -it ${PHP_CONTAINER} bash

phpstan:
	${DOCKER_EXEC} -it ${PHP_CONTAINER} vendor/bin/phpstan analyse -c phpstan.neon

composer-install:
	${DOCKER_EXEC} ${PHP_CONTAINER} composer install --no-interaction

test:
	${DOCKER_EXEC} ${PHP_CONTAINER} ${PHPUNIT} --no-coverage --stop-on-error --stop-on-failure

test-coverage:
	${DOCKER_EXEC} ${PHP_CONTAINER} ${PHPUNIT} --coverage-html var/coverage/
