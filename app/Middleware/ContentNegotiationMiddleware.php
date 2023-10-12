<?php

namespace Vanier\Api\Middleware;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotAcceptableException;

class ContentNegotiationMiddleware implements MiddlewareInterface
{

    public function process(Request $request, RequestHandler $handler): ResponseInterface{
        if($request->getHeaderLine('Accept') == "application/json"){
            $response = new \Slim\Psr7\Response(406);
            
        } else {
            throw new HttpNotAcceptableException($request);
        }

        $response = $handler->handle($request);
        return $response;
    }
}
