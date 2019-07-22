# URL Shortener

## Requirements

In order to run the project the following requirements must be met:

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/)
- [make tool](http://www.gnu.org/software/make/) (optional)

If _**make**_ tool is not available, the commands inside the "Makefile" can be copied and run directly in the terminal.
## Installation
In order to run the application type the command `make docker-up`.
It will create a **_php-fpm_**, **_apache_** and **_redis_** container based on the **_docker-composer.yml_** file and run composer install 
to install the project's php dependencies.

## Commands
A Makefile is included containing some basic commands for convenience:
- `make help` shows all available commands
- `make docker-up` runs docker compose "up" command in order to build the images and create the containers.
- `make docker-stop` stops all running containers.
- `make test-run` runs all phpunit test that are available for the application.
- `make test-coverage` runs all phpunit test that are available and creates a "coverage" folder inside the "tests" folder with visual 
representation of the code coverage of the tests.

## Folder structure 

+ **config:** the folder contains settings for the Slim framework.
+ **docker:** the folder contains all the Dockerfiles and configurations files needed for the creation of the docker images and containers  
+ **public:** the files that are publicly accessible 
+ **src:** 
    + **/Handlers**: functions used for handling the endpoint requests
    + **/Interfaces**: contains all the interfaces used within the application
    + **/Middleware**: contains functionality used as middleware in the application 
    + **/Services**: contains the third party services used for the shortening of the urls
    + **routes.php:** here are specified all the routes for the endpoint
    + **services.php:** here are initialized the services that will be available in the service container

