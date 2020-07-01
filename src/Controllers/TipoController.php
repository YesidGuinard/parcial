<?php

namespace App\Controllers;


use App\Models\Tipo;


class TipoController
{

    static public function validateTipoExists($input)
    {
        return Tipo::where('tipo', $input)->exists();
    }
}