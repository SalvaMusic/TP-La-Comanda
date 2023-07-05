<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once './models/Usuario.php';

class JWTController
{
    private static $claveSecreta = "TP-Comanda";
    private static $tipoEncriptacion = "HS256";

    public static function crearToken($id, $email, $tipo){
        $time = time();
        $payload = array(
         
            "iat" => $time,
            "exp" => $time + (60*60*4),
            "data" => [
                "id" => $id,
                "email" => $email,
                "tipo" => $tipo
            ]
        );
        return JWT::encode($payload, self::$claveSecreta, self::$tipoEncriptacion);
    }

    public static function ObtenerData($token)
    {
        return JWT::decode($token, new Key(self::$claveSecreta, self::$tipoEncriptacion))->data;
    }

    public static function validarToken($token, $tipo){
        $valido = false;
        try {
            $data = JWTController::ObtenerData($token);
            if($tipo == null || $data->tipo == $tipo){
                var_dump($data->id);
                $valido =  $data->id;                
            }
        } catch (Exception $e) {   }
        return $valido;
    }
}

?>