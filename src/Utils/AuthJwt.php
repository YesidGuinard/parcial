<?php

namespace App\Utils;
use \Firebase\JWT\JWT;


class AuthJwt
{
    private static $key = "pro3-parcial";
    private static $payload;

    public static function generarJWT($user){

        AuthJwt::$payload=array(
            'email'=>$user['email'],
            'nombre'=>$user['nombre'],
            'tipo'=>$user['tipo_id']
        );

        return JWT::encode(self::$payload,self::$key);
    }

    public static function validarJWT($token){

        $decoded = null;

        try {
            $decoded = JWT::decode($token, self::$key, array('HS256'));
        } catch (\Exception $e) {
            throw new  \Exception("Token Invalido",403);
        }

        return $decoded;
    }
}