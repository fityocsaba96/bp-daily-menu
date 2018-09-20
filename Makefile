start:
	docker-compose start

stop:
	docker-compose stop

down:
	docker-compose down

build:
	docker-compose build

up:
	docker-compose up -d

compose:
	docker-compose exec webserver /bin/bash -l -c "composer install"

migrate:
	docker-compose exec webserver /bin/bash -l -c "vendor/bin/phinx migrate -e development && vendor/bin/phinx migrate -e test"

seed:
	docker-compose exec webserver /bin/bash -l -c "vendor/bin/phinx seed:run -e development && vendor/bin/phinx seed:run -e test"

ssh:
	docker-compose exec webserver /bin/bash

test:
	docker-compose exec test /bin/bash -l -c "vendor/bin/phpunit tests --colors --bootstrap tests/bootstrap.php"

mysql:
	docker-compose exec mysql mysql -u academy -pacademy

install: down build up compose migrate seed