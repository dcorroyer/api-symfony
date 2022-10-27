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

composer:
	docker-compose run --rm php composer install

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
