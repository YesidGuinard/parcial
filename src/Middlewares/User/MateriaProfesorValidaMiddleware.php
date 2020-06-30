<?php


namespace App\Middleware\User;

use App\Controllers\UsuariosController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MateriaProfesorValidaMiddleware
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getParsedBody() ?? [];

        if (!empty($params['materia']) && !empty($params['cuatrimestre'])
            && !empty($params['vacantes']) && !empty($params['profesor'])) {

            if (!UsuariosController::validateTipoProfesor($params['profesor'])) {
                throw new \Exception("id profesor invalido", 400);
            }
            $response = $handler->handle($request);
            $existingContent = (string)$response->getBody();
            $response = new Response();
            $response->getBody()->write($existingContent);

        } else {
            throw new \Exception("Algunos campos son invalidos", 400);
        }
        return $response;
    }


}
