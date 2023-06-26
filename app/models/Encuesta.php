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

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuesta (pedido_id, punt_mesa, punt_restaurant, punt_mozo, punt_cocinero, detalle) VALUES (:pedidoId, :punt_mesa, :puntRestaurant, :puntMozo, :puntCocinero, :detalle)");
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, pedidoId, punt_mesa, punt_restaurant, punt_mozo, punt_cocinero, detalle FROM encuesta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerEncuesta($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, pedido_Id, punt_mesa, punt_restaurant, punt_mozo, punt_cocinero, detalle FROM encuesta WHERE pedido_id = :pedido");
        $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

}