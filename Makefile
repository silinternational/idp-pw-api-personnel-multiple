start: deps

bash:
	docker-compose run --rm cli bash

clean:
	docker-compose kill
	docker-compose rm -f

deps:
	docker-compose run --rm cli composer install --no-scripts

depsshow:
	docker-compose run --rm cli bash -c "composer show -Df json > versions.json"

depsupdate:
	docker-compose run --rm cli composer update --no-scripts
	make depsshow

test:
	docker-compose run --rm cli /data/vendor/bin/phpunit -c /data/tests/phpunit.xml

testci:
	docker-compose run --rm cli bash -c "/data/run-tests.sh"

