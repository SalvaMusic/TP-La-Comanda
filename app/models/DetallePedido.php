<?php

class DetallePedido
{
    public $id;
    public $codPedido;
    public $productoId;
    public $cantidad;
    public $estado;

    public function crearDetallePedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO detalle_pedido (codPedido, productoId, cantidad, estado) VALUES (:codPedido, :productoId, :cantidad, :estado)");
        $consulta->bindValue(':codPedido', $this->codPedido, PDO::PARAM_INT);
        $consulta->bindValue(':productoId', $this->productoId, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function guardarEstado()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE detalle_pedido SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM detalle_pedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'DetallePedido');
    }

    public static function obtenerPendientes($codPedido, $sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT dp.* FROM detalle_pedido as dp
            JOIN pedido as p ON dp.codPedido = p.codPedido 
            JOIN producto as prod ON prod.id = dp.productoId
            WHERE (:codPedido IS NULL OR p.codPedido = :codPedido)
            AND (:sector IS NULL OR prod.sector = :sector)
            AND p.estado = :estado
            ORDER BY p.horaInicio desc ";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':codPedido', $codPedido);
        $consulta->bindValue(':sector', $sector);
        $consulta->bindValue(':estado', Pedido::ESTADO_PENDIENTE, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'DetallePedido');

    }

}