<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../config/settings.php';

// Instantiate the app
$app = new \Slim\App(new Slim\Container($settings));

// load registered services
require __DIR__. '/../src/ShortenEndpoint/services.php';

// load routes
require __DIR__. '/../src/ShortenEndpoint/routes.php';

try {
    $app->run();
} catch (Exception $exception) {
}
