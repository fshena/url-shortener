help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'

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
