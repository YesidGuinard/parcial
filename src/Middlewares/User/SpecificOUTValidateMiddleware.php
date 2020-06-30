<?php


namespace App\Middleware\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\Helper;

class SpecificOUTValidateMiddleware
{
// after


    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $valid = true;
        if ($valid) {
            $response = $handler->handle($request);

                  $existingContent = (string) $response->getBody();
                   $res = json_decode($existingContent) ?? $existingContent;

                   $nombre = $res->data->nombre;
                   $nombreM = strtoupper($nombre);
                   $res->data->nombre = $nombreM;
                   $res->data->clave = "------";
                   // $response->getBody()->write('AFTER');
                   $response = new Response();
                   $response->getBody()->write(json_encode($res));

            return $response;

        } else {
            throw new \Exception("SpecificOutValidateMiddleware", 407);
        }


    }


}
