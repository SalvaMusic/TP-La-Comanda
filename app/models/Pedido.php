<?php

class Pedido
{
    public $id;
    public $usuarioId;
    public $cliente;
    public $estado;
    public $codPedido;
    public $mesaId;
    public $detallePedidos;
    public $fecha;
    public $horaInicio;
    public $horaFin;
    public $foto;

    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_EN_PREPARACION = 'En Preparación';
    const ESTADO_LISTO_PARA_SERVIR = 'Listo Para Servir';

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido 
            (usuarioId, cliente, estado, codPedido, mesaId, fecha, horaInicio, horaFin, foto) VALUES
            (:usuarioId, :cliente, :estado, :codPedido, :mesaId, :fecha, :horaInicio, :horaFin, :foto)");
        $consulta->bindValue(':usuarioId', $this->usuarioId, PDO::PARAM_INT);
        $consulta->bindValue(':cliente', $this->usuarioId, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codPedido', $this->codPedido, PDO::PARAM_STR);
        $consulta->bindValue(':mesaId', $this->mesaId, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha,);
        $consulta->bindValue(':horaInicio', $this->horaInicio, PDO::PARAM_STR);
        $consulta->bindValue(':horaFin', $this->horaFin, PDO::PARAM_STR);
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

    public function generarCodigoPedido()
    {        
        do {
            $codigo = $this->generarCodigoAleatorio();
        } while ($this->existeCodigoPedido($codigo));

        $this->codPedido = $codigo;
    }

    private function generarCodigoAleatorio()
    {
        $codigo = substr(uniqid(), -5);
        return strtoupper($codigo);
    }

    private function existeCodigoPedido($codigo)
    {        
        return (Pedido::obtenerPedido($codigo) != null);
    }
}