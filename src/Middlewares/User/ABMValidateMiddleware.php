<?php


namespace App\Middleware\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;
use App\Controllers\UsuariosController as UserController;
use Slim\Psr7\Response;

class ABMValidateMiddleware
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $params = $request->getParsedBody() ?? [];

        if (!empty($params['email']) && !empty($params['clave'])
            && !empty($params['tipo']) && !empty($params['nombre']) && !empty($params['legajo'])) {

            if($params['legajo']>2000 || $params['legajo']<1000)
                throw new \Exception("legajo fuera de rango", 403);

            if($params['tipo']>3 || $params['tipo']<1)
                throw new \Exception("Tipo de user fuera de rango", 403);

            $user = UserController::validateUserExists($params['email'], $params['legajo']);
            if (!$user) {
                $response = $handler->handle($request);
                $existingContent = (string)$response->getBody();
                $response = new Response();
                $response->getBody()->write($existingContent);
            } else {
                throw new \Exception("El email o legajo ya se encuentra registrado", 400);
            }
        } else {
            throw new HttpBadRequestException($request);
        }

        return $response;


    }


}
