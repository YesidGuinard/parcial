<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;
class Mascota extends \Illuminate\Database\Eloquent\Model
{
     protected $table = 'tipo_mascota';
     protected $primaryKey = 'id';
    // use SoftDeletes;
    public $timestamps = false;

}