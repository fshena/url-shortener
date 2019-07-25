docker-up:
	docker-compose -f ./docker/docker-compose.yml up

docker-stop:
	docker-compose -f ./docker/docker-compose.yml stop

tests-run:
	./vendor/phpunit/phpunit/phpunit --bootstrap vendor/autoload.php tests

tests-coverage:
	./vendor/phpunit/phpunit/phpunit --bootstrap vendor/autoload.php \
	--coverage-html tests/coverage \
	--whitelist src/ tests

