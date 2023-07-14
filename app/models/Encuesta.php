<?php

class Encuesta
{
    public $id;
    public $pedidoId;
    public $puntMesa;
    public $puntRestaurant;
    public $puntMozo;
    public $puntCocinero;
    public $detalle;

    public function guardar()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuesta (pedidoId, puntMesa, puntRestaurant, puntMozo, puntCocinero, detalle) VALUES (:pedidoId, :puntMesa, :puntRestaurant, :puntMozo, :puntCocinero, :detalle)");
        $consulta->bindValue(':pedidoId', $this->pedidoId, PDO::PARAM_INT);
        $consulta->bindValue(':puntMesa', $this->puntMesa);
        $consulta->bindValue(':puntRestaurant', $this->puntRestaurant);
        $consulta->bindValue(':puntMozo', $this->puntMozo);
        $consulta->bindValue(':puntCocinero', $this->puntCocinero);
        $consulta->bindValue(':detalle', $this->detalle, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuesta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerEncuesta($codPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = 
            "SELECT e.* FROM encuesta e
            JOIN pedido p ON e.pedidoId = p.id
            WHERE p.codPedido = :codPedido";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

}