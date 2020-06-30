<?php


namespace App\Middleware\User;

use App\Controllers\TipoMascotaController;
use App\Models\TipoMascota;
use App\Utils\AuthJwt;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;

class AdminTypeMiddleware
{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $token = $request->getHeader('token')[0];
        $valido = AuthJwt::validarJWT($token);
        $tipoUser = $valido->tipo;

        if ($tipoUser === 1) {
            $params = $request->getParsedBody() ?? [];
            if (!empty($params['tipo'])) {
                if (!TipoMascotaController::validateTipoExists($params['tipo'])) {
                    $response = $handler->handle($request);
                    $existingContent = (string)$response->getBody();
                    $response = new Response();
                    $response->getBody()->write($existingContent);
                } else {
                    throw new \Exception("El tipo de mascota Ya se encuentra registrado", 400);
                }

            } else {
                throw new \Exception("El tipo de mascota No debe ser vacio", 400);
            }
        } else {
            throw new \Exception("Solo los tipo Admin tienen Acceso", 403);
        }

        return $response;


    }


}
