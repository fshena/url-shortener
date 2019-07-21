docker-up:
	docker-compose -f ./docker/docker-compose.yml up -d

docker-stop:
	docker-compose -f ./docker/docker-compose.yml stop

tests-run:
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests

tests-coverage:
	./vendor/bin/phpunit --bootstrap vendor/autoload.php \
	--coverage-html tests/coverage \
	--whitelist src/ tests
