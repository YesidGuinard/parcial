<?php

namespace App\Controllers;


use App\Models\Tipo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\Helper;


class TipoController
{

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = Helper::formatResponse(true, Tipo::all());
        $response->getBody()->write($rta);
        return $response;
    }

    static public function validateTipoExists($input)
    {
        return Tipo::where('tipo', $input)->exists();
    }
}