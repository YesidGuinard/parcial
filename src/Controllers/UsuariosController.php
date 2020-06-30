<?php

namespace App\Controllers;


use App\Models\TipoMascota;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use App\Utils\AuthJwt;
use App\Utils\Helper;
use Slim\Exception\HttpUnauthorizedException;

class UsuariosController
{

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = Helper::formatResponse(true, Usuario::all());
        $response->getBody()->write($rta);
        return $response;
    }

    public function getByID(Request $request, Response $response, $args)
    {
        $id = $args["id"];
        if (!empty($id)) {
            $user = Usuario::find($id);
            if (!empty($user)) {
                $rta = Helper::formatResponse(true, $user);
                $response->getBody()->write($rta);
                return $response;
            } else {
                throw new \Exception("usuario no encontrado", 404);
            }
        } else {
            throw new \Exception("Id no puede ser inferior a 1", 400);
        }

    }

    public function add(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = new Usuario();

        $usuario->email = $parametros["email"];
        $usuario->nombre = $parametros["nombre"];
        $usuario->clave = password_hash($parametros["clave"], PASSWORD_DEFAULT);
        $usuario->tipo_id = $parametros["tipo"];
        $usuario->legajo = $parametros["legajo"];

        $rta = Helper::formatResponse($usuario->save(), "Usuario guardado en DB");
        $response->getBody()->write($rta);
        return $response;
    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = $args["id"] ?? NULL;
        $usuario = Usuario::findOrFail($id);
        $rta = json_encode(array("success" => $usuario->delete(),
            "message" => "Soft deleted, Done!"));
        $response->getBody()->write($rta);
        return $response;
    }

    public function login(Request $request, Response $response, $args)
    {
        $parametros = $request->getParsedBody();
        $rta = "";
        $email = $parametros["email"];
        $password = $parametros["clave"];

        $userExists = $this->validateUserExists($email, 0);

        if ($userExists && $this->validateUserPasswordDB($password, $email)) {
            $token = AuthJwt::generarJWT($this->getUserByEmail($email));
            $rta = Helper::formatResponse(true, $token);
        } else {
            throw new HttpUnauthorizedException($request);
        }
        $response->getBody()->write($rta);
        return $response;
    }

    static public function validateUserExists($email, $legajo)
    {
        $existe = Usuario::where('email', $email)->exists() || Usuario::where('legajo', $legajo)->exists();
        return $existe;
    }

    public function validateUserPasswordDB($password, $email): bool
    {
        $hashed_password = Usuario::where('email', $email)->value('clave');
        if (isset($hashed_password)) {
            return (password_verify($password, $hashed_password));
        } else {
            return false;
        }
    }

    public function getUserByEmail($email)
    {
        return Usuario::where('email', $email)->first();
    }

    static public function validateTipoExists($input)
    {
        return Usuario::where('tipo', $input)->exists();

    }

    static public function validateTipoProfesor($idProfe)
    {
        $usuario = Usuario::where('id', $idProfe)->first();
        if (isset($usuario)) {
            $tipo = $usuario->tipo_id;
            if ($tipo === 2)
                return true;
        }
        return false;

    }

}