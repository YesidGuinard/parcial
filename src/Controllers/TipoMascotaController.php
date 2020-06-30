<?php

namespace App\Controllers;

use App\Models\TipoMascota;
use mysql_xdevapi\Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\Helper;


class TipoMascotaController
{

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = Helper::formatResponse(true, TipoMascota::all());
        $response->getBody()->write($rta);
        return $response;
    }

    public function getByID(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        if (!empty($id)) {
            $user = TipoMascota::find($id);
            if (!empty($user)) {
                $rta = Helper::formatResponse(true, $user);
                $response->getBody()->write($rta);
                return $response;
            } else {
                throw new \Exception("Tipo Mascota no encontrado", 404);
            }
        } else {
            throw new \Exception("Id no puede ser inferior a 1", 400);
        }

    }

    public function add(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $tipoMascota = new TipoMascota();
        $tipoMascota->tipo = $parametros["tipo"];
        $rta = Helper::formatResponse($tipoMascota->save(), "Tipo Mascota guardado en DB");
        $response->getBody()->write($rta);
        return $response;
    }

    static public function validateTipoExists($input)
    {
        return TipoMascota::where('tipo', $input)->exists();

    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = $args["id"] ?? NULL;
        $usuario = TipoMascota::findOrFail($id);
        $rta = Helper::formatResponse($usuario->delete(), "Soft deleted, Done!");
        $response->getBody()->write($rta);
        return $response;
    }


}