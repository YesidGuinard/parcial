<?php


namespace App\Middleware\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\Helper;

class SpecificOutAllUserMiddleware
{
// after
   public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $valid = true;
        if ($valid) {
            $response = $handler->handle($request);
            $existingContent = (string)$response->getBody();
            $res = json_decode($existingContent) ?? $existingContent;
            $data = $res->data;
            foreach ($data as $user) {
                $user->nombre = strtoupper($user->nombre);
                $user->clave = "*****";
            }
            $response = new Response();
            $response->getBody()->write(json_encode($res));

            return $response;

        } else {
            throw new \Exception("SpecificOutAllUserMiddleware", 407);
        }


    }


}
