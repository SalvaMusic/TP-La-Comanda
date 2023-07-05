<?php

class Pedido
{
    public $id;
    public $usuarioId;
    public $estado;
    public $codPedido;
    public $mesa;
    public $detallePedidos;
    public $fechaInicio;
    public $fechaFin;
    public $foto;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (usuarioId, estado, codPedido, mesaId, fechaInicio, foto) VALUES (:usuarioId, :estado, :codPedido, :mesaId, :fechaInicio, :foto)");
        $consulta->bindValue(':usuarioId', $this->usuarioId, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codPedido', $this->codPedido, PDO::PARAM_STR);
        $consulta->bindValue(':mesaId', $this->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':fechaInicio', $this->fechaInicio, PDO::PARAM_INT);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($codPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE codPedido = :codPedido");
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function guardarEstado($codPedido)
    {
        // Ver bien como es el flujo de guardar estado
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT estado FROM pedido WHERE codPedido = :codPedido");
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();

        $estado = $consulta->fetchColumn();

        $consulta = $objAccesoDatos->prepararConsulta("UPDATE usuario SET estado = :estado WHERE codPedido = :codPedido");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();
    }
}