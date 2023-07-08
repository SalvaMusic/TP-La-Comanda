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

    public static function obtenerDetallePedido($codPedido, $sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT dp.* FROM detalle_pedido as dp
            JOIN producto as prod ON prod.id = dp.productoId
            WHERE codPedido = :codPedido
            AND prod.sector = :sector";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('DetallePedido');
    }

}