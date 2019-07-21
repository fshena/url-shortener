<?php declare(strict_types=1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \ShortenEndpoint\Interfaces\ServiceInterface;

/**
 * Function that handles POST request to "/shorten" endpoint.
 *
 * @param Request  $request
 * @param Response $response
 * @param array    $args
 *
 * @return Response
 */
return function (Request $request, Response $response, array $args): Response {

    $params = $request->getParsedBody();

    /** @var ServiceInterface $shortenUrlService */
    $shortenUrlService = $this->shortenUrlFactory->create($params['provider']);

    return $response->withJson([
        'data' => $shortenUrlService->shortenUrl($params['url']),
    ]);
};
