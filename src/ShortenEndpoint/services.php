<?php declare(strict_types=1);

/**
 * File used for defining all the application's services.
 */

$c = $app->getContainer();

if (!$c) {
    return;
}

$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withJson([ 'error' => $exception->getMessage() ], 500);
    };
};

$c['shortenUrlFactory'] = function ($c) {
    return new ShortenEndpoint\Services\ServiceFactory();
};

