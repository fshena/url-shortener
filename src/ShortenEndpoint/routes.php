<?php declare(strict_types=1);

/**
 * File used for defining all application's routes
 */

use \Slim\App as App;

$postHandler          = require __DIR__ . '/Handlers/postHandler.php';
$validationMiddleware = require __DIR__ . '/Middleware/validationMiddleware.php';

// group routes in case we want to have versions
$app->group('/v1', function (App $app) use ($postHandler, $validationMiddleware) {
    $app->post('/shorten', $postHandler)->add($validationMiddleware);
});
