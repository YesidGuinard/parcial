<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;
class Turnos extends \Illuminate\Database\Eloquent\Model
{
     protected $table = 'turnos';
     protected $primaryKey = 'id';
    // use SoftDeletes;
    public $timestamps = false;

}