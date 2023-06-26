<?php

class Estado
{
    private static $instancia;
    private static $mesa;
    private static $pedido;

    private function __construct()
    {
        self::$mesa = array('Con cliente esperando pedido', 'Con cliente comiendo', 'Con cliente pagando', 'Cerrada');
        self::$pedido = array('Pendiente', 'En preparación', 'Listo Para Servir');
    }

    public static function obtenerInstancia()
    {
        if (!isset(self::$instancia)) {
            self::$instancia = new Estado();
        }
        return self::$instancia;
    }

    public static function siguienteEstadoMesa($estado){
        $siguienteEstado = null;
        $count = count(self::$mesa);
        if($estado == self::$mesa[$count]){
            $siguienteEstado = $estado;
        }  else {
            for ($i = 0; $i < count(self::$mesa) ; $i++) {
                if (self::$mesa[$i] == $estado){
                    $siguienteEstado = self::$mesa[$i];
                }
            }
        }

        return $siguienteEstado != null ? $siguienteEstado : self::$mesa[0];
    }

    public static function siguienteEstadoPedido($estado){
        $siguienteEstado = null;
        $count = count(self::$pedido);
        if($estado == self::$pedido[$count]){
            $siguienteEstado = $estado;
        }  else {
            for ($i = 0; $i < count(self::$pedido) ; $i++) {
                if (self::$pedido[$i] == $estado){
                    $siguienteEstado = self::$pedido[$i];
                }
            }
        }

        return $siguienteEstado != null ? $siguienteEstado : self::$pedido[0];
    }

}