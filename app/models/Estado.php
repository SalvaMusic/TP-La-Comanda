<?php
define('DATE_FORMAT', 'd-m-Y');

class Estado {
    private static $instance;
    private static $mesa = array(
        'Con cliente esperando pedido',
        'Con cliente comiendo',
        'Con cliente pagando',
        'Cerrada'
    );

    private static $pedido = array(
        'Pendiente',
        'En preparación',
        'Listo Para Servir'
    );

    private function __construct() {
        // Constructor privado para evitar instanciación externa
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Estado();
        }

        return self::$instance;
    }

    public function siguienteEstadoMesa($estadoActual) {
        if ($estadoActual === null) {
            // Si el estado actual es null, devuelve el primer estado
            return self::$mesa[0];
        }

        $posicionActual = array_search($estadoActual, self::$mesa);

        if ($posicionActual === false) {
            // Estado inválido
            return null;
        }

        $posicionSiguiente = $posicionActual + 1;

        if ($posicionSiguiente >= count(self::$mesa)) {
            // Último estado, devuelve el último estado
            return self::$mesa[count(self::$mesa) - 1];
        }

        return self::$mesa[$posicionSiguiente];
    }

    public function siguienteEstadoPedido($estadoActual) {
        if ($estadoActual === null) {
            // Si el estado actual es null, devuelve el primer estado
            return self::$pedido[0];
        }

        $posicionActual = array_search($estadoActual, self::$pedido);

        if ($posicionActual === false) {
            // Estado inválido
            return null;
        }

        $posicionSiguiente = $posicionActual + 1;

        if ($posicionSiguiente >= count(self::$pedido)) {
            // Último estado, devuelve el último estado
            return self::$pedido[count(self::$pedido) - 1];
        }

        return self::$pedido[$posicionSiguiente];
    }
}
