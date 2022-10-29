up:
	docker-compose up -d

down:
	docker-compose down

reup:
	docker-compose down
	docker-compose up -d

build:
	docker-compose build

rebuild:
	docker-compose build --no-cache

back:
	docker-compose exec php bash

composer-install:
	docker-compose run --rm php composer install

composer-update:
	docker-compose run --rm php composer update

tests:
	docker-compose run --rm php vendor/bin/phpunit

dbcreate:
	docker-compose run --rm php php bin/console d:d:c

dbupdate:
	docker-compose run --rm php php bin/console doctrine:migrations:migrate

dbfixtures:
	docker-compose run --rm php php bin/console doctrine:fixtures:load

dbcreate-test:
	docker-compose run --rm php php bin/console --env=test doctrine:database:create

dbupdate-test:
	docker-compose run --rm php php bin/console --env=test doctrine:migrations:migrate

dbfixtures-test:
	docker-compose run --rm php php bin/console --env=test doctrine:fixtures:load
