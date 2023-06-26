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


    public function __construct($usuarioId, $estado, $codPedido, $fechaInicio = null, $foto = null)
    {
        $this->usuarioId = $usuarioId;
        $this->estado = $estado;
        $this->codPedido = $codPedido;
        $this->fechaInicio = $fechaInicio != null ? $fechaInicio : new DateTime(date("d-m-Y"));        
        $this->foto = $foto;
    }
    
    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (usuario_id, estado, cod_pedido, mesaId, fechaInicio, foto) VALUES (:usuarioId, :estado, :codPedido, :mesaId, :fechaInicio, :foto)");
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario_id, estado, cod_pedido, mesaId, fechaInicio, foto FROM pedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($codPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario_id, estado, cod_pedido, mesaId, fechaInicio, foto FROM pedido WHERE cod_pedido = :codPedido");
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function siguienteEstado($codPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT estado FROM pedido WHERE cod_pedido = :codPedido");
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();

        $estado = $consulta->fetchColumn();

        $consulta = $objAccesoDatos->prepararConsulta("UPDATE usuario SET estado = :estado WHERE cod_pedido = :cod_pedido");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();
    }
}