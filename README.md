# Mycar

### Prerequisites

You need to have:

- __Docker__ and __Docker Compose__ installed on your computer.
- Your __SSH Keys__ configured on your __Gitea__ settings
- __Mkcert__ installed on your computer

### Installation

First step, clone the project on your computer

    git clone git@github.com:dcorroyer/mycar-back.git

Then you have to generate your ssl keys (no passphrase) and install them in your computer:

    mkcert -key-file key.pem -cert-file cert.pem mycar.local
    mkcert -install

Then you have to move (or copy) the keys in the project:

    mv cert.pem path-to-project/docker/nginx/mycar.cert.pem
    mv key.pem path-to-project/docker/nginx/mycar.key.pem

A shell script is used to set up the docker containers and de project,
it also write the url __mycar.local__ in your __/etc/hosts__:

run command:

    ./install

### Load the fixtures
run commands:

    docker-compose run --rm php php bin/console d:f:l --no-interaction
	docker-compose run --rm php php bin/console d:f:l --env=test --no-interaction

### Load the tests
run command:

    docker-compose run --rm php vendor/bin/phpunit

### Test the app with postman
import **Mycar.postman_collection.json** in **Postman** to be able to test endpoints

### Others

    openssl genrsa -out config/jwt/private.pem -aes256 4096
    passphrase: password
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
    passphrase: password

A file `./dc` is used to wrap the docker-compose commands, example:

    ./dc up -d <=> docker-compose up -d

A `Makefile` is up to provide some useful commands:

    make up <=> docker-compose up -d