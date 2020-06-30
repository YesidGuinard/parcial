<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpForbiddenException;
use Slim\Psr7\Response;

use App\Utils\AuthJwt;

class AuthMiddleware
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeader('token')??NULL;
        $valido = NULL;
        if(!empty($header[0]))
            $valido = AuthJwt::validarJWT($header[0]);
        else
            throw new \Exception("Token no puede ser Vacio",400);


        if (isset($valido)) {
            $response = $handler->handle($request);
            $existingContent = (string)$response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent);
            return $resp;
        } else {
            throw new HttpForbiddenException($request);
        }
    }
}
