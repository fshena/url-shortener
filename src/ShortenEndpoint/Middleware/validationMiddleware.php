<?php declare(strict_types=1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Respect\Validation\Validator as v;
use \Respect\Validation\Exceptions\NestedValidationException;
use \ShortenEndpoint\Services\ServiceFactory;

/**
 * Validate the POST request body params.
 *
 * @param Request  $request
 * @param Response $response
 * @param callable $next
 *
 * @return Response
 */
return function (Request $request, Response $response, callable $next): Response {
    $errors = [];

    $params = $request->getParsedBody();

    /** @var ServiceFactory $serviceFactory */
    $serviceFactory = $this->get('shortenUrlFactory');

    // "url" is mandatory, "provider" is optional
    $urlValidator      = v::url()->length(1, 2000);
    $providerValidator = v::optional(v::in($serviceFactory->getAvailableServices()));

    try {
        $urlValidator->assert($params['url']);
        $providerValidator->assert($params['provider']);
    } catch (NestedValidationException $exception) {
        $errors = array_merge($errors, $exception->getMessages());
    }

    if (count($errors) > 0) {
        return $response->withJson(['errors' => $errors]);
    }

    return $next($request, $response);
};
