<?php

namespace App\Controllers;

use App\Models\Materia;
use App\Models\TipoMascota;
use App\Models\Turnos;
use App\Models\Usuario;
use App\Utils\AuthJwt;
use mysql_xdevapi\Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\Helper;


class MateriaController
{

/*    public function getMaterias(Request $request, Response $response, $args)
    {
        $rta = Helper::formatResponse(true, Materia::all());
        $response->getBody()->write($rta);
        return $response;
    }*/

    public function getByID(Request $request, Response $response, $args)
    {
        $token = $request->getHeader('token')[0];
        $valido = AuthJwt::validarJWT($token);
        $tipoUser = $valido->tipo;
        $id = $args["id"];
        if (!empty($id)) {
            $materia = Materia::find($id);
            if (!empty($materia)) {

                if ($tipoUser == 1) {
                    $materia->vacantes = "";
                    $materia->profesor_id = "";

                }
                $rta = Helper::formatResponse(true, $materia);
                $response->getBody()->write($rta);
                return $response;
            } else {
                throw new \Exception("id Materia no encontrado", 404);
            }
        } else {
            throw new \Exception("Id no puede ser inferior a 1 o vacio", 400);
        }

    }

    public function addMateria(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $materia = new Materia();
        $materia->materia = $parametros["materia"];
        $materia->cuatrimestre = $parametros["cuatrimestre"];
        $materia->vacantes = $parametros["vacantes"];
        $materia->profesor_id = $parametros["profesor"];
        $rta = Helper::formatResponse($materia->save(), "Tipo Materia guardado en DB");
        $response->getBody()->write($rta);
        return $response;
    }

    public function AsignaProfe(Request $request, Response $response, $args)
    {
        $id_materia = $args["id"];
        $profesor_id = $args["profesor"];
        if (empty($id) || empty($profesor_id) ) {
            throw new \Exception("Campos vacios",400);
        }

        if(!Usuario::validateTipoProfesor($profesor_id))
                    throw new \Exception("id_profesor no es profesor",400);

        if(!MateriaController::validateMateriaExists($id_materia))
            throw new \Exception("id_materia no es materia Valida o no existe",400);

        $materia = Materia::find($id_materia);
        $materia->profesor_id= $profesor_id;

        $rta = Helper::formatResponse($materia->save(), "Profesor Asignado a Materia guardado en DB");
        $response->getBody()->write($rta);
        return $response;
    }


    static public function validateMateriaExists($input)
    {
        return Materia::where('id', $input)->exists();

    }

}