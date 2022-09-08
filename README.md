# API made with Symfony 6

An API built with Symfony 6 with php 8 application.

The database is built with MySQL8.

You need to have **docker** installed on your computer to run the project.

### Install the project
run command:

    ./install

### Load the fixtures
run commands:

    docker-compose run --rm php php bin/console d:f:l --no-interaction
	docker-compose run --rm php php bin/console d:f:l --env=test --no-interaction

### Load the tests
run commands:

    docker-compose run --rm php vendor/bin/phpunit

### Test the app with postman
import **Mycar.postman_collection.json** in **Postman** to be able to test endpoints

### Others (should not be taken into account - for information)

    openssl genrsa -out config/jwt/private.pem -aes256 4096
    passphrase: password
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
    passphrase: password
