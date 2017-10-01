<?php

namespace App;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewareTest implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $response = $delegate->process($request);
        $response->getBody()->write('Test middleware (' . $request->getUri()->getPath() . ')');

        return $response;
    }
}
