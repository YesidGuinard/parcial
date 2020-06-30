<?php


namespace App\Middleware\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

class LoginValidateMiddleware
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {

        $params = $request->getParsedBody()??[];

        if (!empty($params['email']) && !empty($params['clave'])) {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $response = new Response();
            $response->getBody()->write($existingContent);

        }else {
            throw new HttpBadRequestException($request);
        }

        return $response;


    }


}
