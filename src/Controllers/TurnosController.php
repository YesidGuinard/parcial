<?php

namespace App\Controllers;

use DateTime;
use App\Models\Turnos;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\Helper;


class TurnosController
{

    public function getAll(Request $request, Response $response, $args)
    {
        $tipo = 1;
        $turnos = NULL;
        switch ($tipo) {
            case 1:
                $idVet=1;
                $turnos = $this->getAllTurnosByIdVeterinario($idVet);
                break;
            case 2:
                $idVet=5;
                $turnos = $this->getAllTurnosByIdVeterinario($idVet);
                break;

            case 3:
                $idVet=6;
                $turnos = $this-> getAllTurnosByIdVeterinario($idVet);
                break;
        }
        $rta = Helper::formatResponse(true, $turnos);
        $response->getBody()->write($rta);
        return $response;
    }

    public function getByID(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        if (!empty($id)) {
            $user = Turnos::find($id);
            if (!empty($user)) {
                $rta = Helper::formatResponse(true, $user);
                $response->getBody()->write($rta);
                return $response;
            } else {
                throw new \Exception("Turno no encontrado", 404);
            }
        } else {
            throw new \Exception("Id no puede ser inferior a 1", 400);
        }

    }

    public function add(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $turnos = new Turnos();
        $turnos->tipo = $parametros["tipo"];
        $rta = Helper::formatResponse($turnos->save(), "Turno almacenado guardado en DB");
        $response->getBody()->write($rta);
        return $response;
    }

    static public function validateTurnoExists($input)
    {
        return Turnos::where('tipo', $input)->exists();

    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = $args["id"] ?? NULL;
        $usuario = Turnos::findOrFail($id);
        $rta = Helper::formatResponse($usuario->delete(), "Soft deleted, Done!");
        $response->getBody()->write($rta);
        return $response;
    }

    public function getAllTurnosByIdVeterinario($idVet)
    {
/*        $turnos = Turnos::select('fecha', 'mascotas.nombre', 'mascotas.cliente_id', 'mascotas.fecha_nacimiento')
            ->where('veterinario_id', $idVet)
            ->join('mascotas', 'mascotas.id', 'turnos.mascota_id')->get();
        return $turnos; ok reg */

     /*   $turnos = Turnos::where('veterinario_id', $idVet)
            ->join('mascotas', 'mascotas.id', 'turnos.mascota_id')
            ->join('usuarios', 'usuarios.id', 'mascotas.cliente_id');*/
            $turnos = Turnos::join('mascotas', 'mascotas.id', 'turnos.mascota_id')
                ->where('veterinario_id', $idVet)
                ->select('mascotas.cliente_id', 'mascotas.nombre', 'turnos.fecha', 'mascotas.fecha_nacimiento')
            ->get();
        return $turnos;
    }



}