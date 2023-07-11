<?php

class DetallePedido
{
    public $id;
    public $pedidoId;
    public $productoId;
    public $cantidad;
    public $estado;
    public $horaInicio;
    public $tiempoEstimado;


    public function guardar()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if($this->id == null){
            $consulta = $objAccesoDatos->prepararConsulta(
                "INSERT INTO detalle_pedido (pedidoId, productoId, cantidad, estado, horaInicio, tiempoEstimado) 
                VALUES (:pedidoId, :productoId, :cantidad, :estado, :horaInicio, :tiempoEstimado)");
            $consulta->bindValue(':pedidoId', $this->pedidoId, PDO::PARAM_INT);
        } else {
            $consulta = $objAccesoDatos->prepararConsulta(
                "UPDATE detalle_pedido SET 
                    estado = :estado,
                    productoId = :productoId,
                    cantidad = :cantidad, 
                    tiempoEstimado = :tiempoEstimado,
                    horaInicio = :horaInicio
                WHERE id = :id");
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        }
        $consulta->bindValue(':productoId', $this->productoId, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':horaInicio', $this->horaInicio, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM detalle_pedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'DetallePedido');
    }

    public static function obtener($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();            
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM detalle_pedido WHERE id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();

        return $consulta->fetchObject('DetallePedido');
    }

    public static function obtenerPendientes($codPedido)
    {
        $todosPedidos = $codPedido == null ? 'TODOS' : null;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT dp.* FROM detalle_pedido as dp
            JOIN pedido as p ON dp.pedidoId = p.id 
            JOIN producto as prod ON prod.id = dp.productoId
            WHERE (:todosPedidos = 'TODOS' OR p.codPedido = :codPedido)
            AND p.estado = :estado
            ORDER BY p.horaInicio DESC";
            
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':codPedido', $codPedido);
        $consulta->bindValue(':todosPedidos', $todosPedidos);
        $consulta->bindValue(':estado', Pedido::ESTADO_PENDIENTE, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'DetallePedido');
    }

    public static function obtenerListaPorCodPedido($codPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT dp.* FROM detalle_pedido as dp
            JOIN pedido as p ON dp.pedidoId = p.id 
            JOIN producto as prod ON prod.id = dp.productoId
            WHERE p.codPedido = :codPedido
            ORDER BY p.horaInicio DESC";
            
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':codPedido', $codPedido);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'DetallePedido');

    }

}