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
    public $horaOrden;    
    public $foto;

    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_EN_PREPARACION = 'En Preparación';
    const ESTADO_LISTO_PARA_SERVIR = 'Listo Para Servir';

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido 
            (usuarioId, cliente, estado, codPedido, mesaId, fecha, horaInicio, horaFin, horaOrden, foto) VALUES
            (:usuarioId, :cliente, :estado, :codPedido, :mesaId, :fecha, :horaInicio, :horaFin, :horaOrden, :foto)");
        $consulta->bindValue(':usuarioId', $this->usuarioId, PDO::PARAM_INT);
        $consulta->bindValue(':cliente', $this->usuarioId, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codPedido', $this->codPedido, PDO::PARAM_STR);
        $consulta->bindValue(':mesaId', $this->mesaId, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha,);
        $consulta->bindValue(':horaInicio', $this->horaInicio, PDO::PARAM_STR);
        $consulta->bindValue(':horaFin', $this->horaFin, PDO::PARAM_STR);
        $consulta->bindValue(':horaOrden', $this->horaOrden, PDO::PARAM_STR);
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

    public function obtenerTiempoRestante()
    {
        $tiempoRestantePedido = 0;
        foreach ($this->detallePedidos as $detallePedido) {
            if ($detallePedido->estado == DetallePedido::ESTADO_EN_PREPARACION) {
                $horaInicio = strtotime($detallePedido->horaInicio);
                $tiempoEstimado = strtotime($detallePedido->tiempoEstimado);

                $tiempoRestante = $horaInicio + $tiempoEstimado - time();
                if ($tiempoRestante > $tiempoRestantePedido) {
                    $tiempoRestantePedido = $tiempoRestante;
                }
            }
        }
        return $tiempoRestantePedido;
    }

    public static function obtenerTodosDetalles()
    {
        $pedidos = Pedido::obtenerTodos();
        $detallePedidos = DetallePedido::obtenerTodos();

        foreach ($pedidos as $pedido) {
            $pedido->detallePedidos = []; // Inicializar el array detallePedidos para cada Pedido
        
            foreach ($detallePedidos as $detallePedido) {
                if ($detallePedido->pedidoId == $pedido->id) {
                    $pedido->detallePedidos[] = $detallePedido; // Agregar el DetallePedido al array detallePedidos del Pedido actual
                }
            }
        }

        return $pedidos;
    }

    public static function obtenerPedido($codPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedido WHERE codPedido = :codPedido");
        $consulta->bindValue(':codPedido', $codPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPorDeralleId($detalleId)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();            
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT p.* FROM detalle_pedido as dp 
            JOIN pedido p ON dp.pedidoId = p.id
            WHERE dp.id = :detalleId");
        $consulta->bindValue(':detalleId', $detalleId);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerDetalleEnPreparacion($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();            
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT dp.* FROM detalle_pedido as dp 
            JOIN pedido p ON dp.pedidoId = p.id
            WHERE dp.estado = :estado
            AND p.id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->bindValue(':estado', Pedido::ESTADO_EN_PREPARACION);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'DetallePedido');
    }

    public static function obtenerPendientes($codPedido)
    {
        $todosPedidos = $codPedido == null ? 'TODOS' : null;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = "SELECT p.* FROM pedido as p
            WHERE (:todosPedidos = 'TODOS' OR p.codPedido = :codPedido)
            AND p.estado = :estado
            ORDER BY p.horaInicio DESC";
            
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->bindValue(':codPedido', $codPedido);
        $consulta->bindValue(':todosPedidos', $todosPedidos);
        $consulta->bindValue(':estado', Pedido::ESTADO_PENDIENTE, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');

    }

    public static function obtenerPendientesDetalles($codPedido)
    {
        $pedidos = Pedido::obtenerPendientes($codPedido);
        $detallePedidos = DetallePedido::obtenerPendientes($codPedido);

        foreach ($pedidos as $pedido) {
            $pedido->detallePedidos = []; // Inicializar el array detallePedidos para cada Pedido
        
            foreach ($detallePedidos as $detallePedido) {
                if ($detallePedido->pedidoId == $pedido->id) {
                    $pedido->detallePedidos[] = $detallePedido; // Agregar el DetallePedido al array detallePedidos del Pedido actual
                }
            }
        }

        return $pedidos;
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