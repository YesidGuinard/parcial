<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\SoftDeletes;
class Usuario extends \Illuminate\Database\Eloquent\Model
{
     protected $table = 'users';
     protected $primaryKey = 'id';
     //use SoftDeletes;
     public $timestamps = false;

}