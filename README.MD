# URL Shortener

## Requirements

In order to run the project the following requirements must be met:

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/)
- [make tool](http://www.gnu.org/software/make/) (optional)

If _**make**_ tool is not available, the commands inside the "Makefile" can be copied and run directly in the terminal.
## Installation
In order to run the application type the command 
```bash
make docker-up
```
It will create a **_php-fpm_**, **_apache_** and **_redis_** container based on the **_docker-composer.yml_** file and run **_composer install_** 
to install the project's php dependencies.

After the initial build the application will listen to _http:/localhost:8787/v1/shorten_ and the endpoint can be requested. 
Available PROVIDERS are "bitly" & "rebrandly", URL can be any url: 
```bash
curl -X POST -F 'url={URL}' -F 'provider={PROVIDER}' http://localhost:8787/v1/shorten
```
 Response sample:
```bash
{
    "data": {
        "id": "17ecced4aaf8418f800abe04efa0be93",
        "longUrl": "https://vim.rtorr.com/",
        "shortUrl": "rebrand.ly/annbcx"
    }
}
```

## Commands
A Makefile is included containing some basic commands for convenience:
- `make docker-up` runs docker compose "up" command in order to build the images and create the containers.
- `make docker-stop` stops all running containers.
- `make tests-run` runs all phpunit test that are available for the application.
- `make tests-coverage` runs all phpunit test that are available and creates a "coverage" folder inside the "tests" folder with visual 
representation of the code coverage of the tests.

## Folder structure 

+ **config:** the folder contains settings for the Slim framework.
+ **docker:** the folder contains all the Dockerfiles and configurations files needed for the creation of the docker images and containers  
+ **public:** the files that are publicly accessible 
+ **tests** contains the application's tests
+ **src:** 
    + **/Handlers**: functions used for handling the endpoint requests
    + **/Interfaces**: contains all the interfaces used within the application
    + **/Middleware**: contains functionality used as middleware in the application 
    + **/Services**: contains the third party services used for the shortening of the urls
    + **routes.php:** here are specified all the routes for the endpoint
    + **services.php:** here are initialized the services that will be available in the service container

