<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

use DateTime;

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {

        $data = $request->getParsedBody();

        // Acceder a las variables recibidas por POST
        $cliente = $data['cliente'];
        $detallePedidosData = isset($data['detallePedidos']) ? $data['detallePedidos'] : array();
        $fecha = isset($data['fecha']) ? $data['fecha'] : date('d/m/Y');
        $horaInicio = isset($data['horaInicio']) ? $data['horaInicio'] : date('H:i:s');        
        $usuarioId = $data['usuarioId'];
        $cantidad = $data['cantidad'];

        $p = new Pedido();
        $p->cliente = $cliente;
        $p->estado = Pedido::ESTADO_PENDIENTE;
        $p->generarCodigoPedido();
        $p->horaInicio = $horaInicio;

        $p->usuarioId = intval($usuarioId);
        $p->cantidad = intval($cantidad);
        
        $mensaje = null;

        $fechaObj = DateTime::createFromFormat("d/m/Y", $fecha);
        $p->fecha = $fechaObj !== false ? $fechaObj->format("Y-m-d") : date("Y-m-d");

        
            $mesaId = intval($mesaId);
            $mesa = Mesa::obtener($mesaId);
            if($mesa != null){
                $p->mesaId = $mesaId;
                $p->guardar();
                $mensaje = "Pedido realizado correctamente. En estado: " . $p->estado;
            } else {
                $mensaje = "Mesa Cerrada.";
            }
        
            $detallePedidos = array();
            foreach ($detallePedidosData as $detalleData) {
                $detalle = new DetallePedido();
                $productoId = intval($detalleData['productoId']);
                $cantidad = intval($detalleData['cantidad']);
                $detalle->productoId = Producto::obtenerProducto($productoId) != null ? $productoId : null;
                $detalle->cantidad = $cantidad;
                $detalle->estado = Pedido::ESTADO_PENDIENTE;
        
                $detallePedidos[] = $detalle;
            }
        
            // Asignar el array de detalles de pedido a $p->detallePedidos
            $p->detallePedidos = $detallePedidos;

        $payload = json_encode(array("mensaje" => $mensaje));

        return $$response->withJson($payload);
    }

    public function TraerUno($request, $response, $args)
    {
       /* $id = $args['id'];
        $arma = Venta::obtener($id);
        $data = $arma ? $arma : "Arma Inexistente";
        $payload = json_encode($data);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');*/
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Venta::obtenerTodos();
        $payload = json_encode(array("lista Armas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function FiltrarNacionalidadFecha($request, $response, $args)
    {
        $pais = 'EEUU';
        $fechaInicio = '2022-11-13';
        $fechaFin = '2022-11-16';
        $lista = Venta::obtenerTodosPaisFecha($pais, $fechaInicio, $fechaFin);
        $payload = json_encode(array("lista Ventas Armas EEUU y fecha" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        //Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        //Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
}
