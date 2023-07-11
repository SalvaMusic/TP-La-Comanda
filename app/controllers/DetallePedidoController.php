<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './models/DetallePedido.php';
require_once './interfaces/IApiUsable.php';

class DetallePedidoController extends DetallePedido implements IApiUsable
{
    public function CargarUno($request, $response, $args) {  }
    
    public function TraerUno($request, $response, $args) {  } 

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodosDetalles();
        $payload = json_encode(array("lista Pedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientes($request, $response, $args)
    {
        $pedido = $args['codPedido'] == 'TODOS' ? null : $args['codPedido'];
        $lista = Pedido::obtenerPendientesDetalles($pedido);
        $payload = json_encode(array("lista Pendientes" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function PrepararPedido($request, $response, $args)
    {
        $data = $request->getParsedBody();
        $id = $args['detallePedidoId'];
        $duracion = $data['duracion'];
        $detalle = DetallePedido::obtener($id);

        if($detalle != null){
          $this->ModificarPedido($detalle, $duracion, Pedido::ESTADO_EN_PREPARACION);
          $mensaje = array(
            "mensaje" => "Pedido tomado con exito",
            "Detalle" => $detalle);
        } else {
          $mensaje = "Detalle Pedido " . $id . " Inexistente";
        }

        $payload = json_encode($mensaje);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function FinalizarPedido($request, $response, $args)
    {
        $data = $request->getParsedBody();
        $id = $args['detallePedidoId'];
        $detalle = DetallePedido::obtener($id);
        if($detalle != null){
          $this->ModificarPedido($detalle, null, Pedido::ESTADO_LISTO_PARA_SERVIR);
          $mensaje = array(
            "mensaje" => "Pedido finalizado con exito",
            "Detalle" => $detalle);
        } else {
          $mensaje = "Detalle Pedido " . $id . " Inexistente";
        }

        $payload = json_encode($mensaje);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarPedido($detalle, $duracion, $estado)
    {
      date_default_timezone_set('America/Argentina/Buenos_Aires');
      $horaActual = date('H:i:s');

      $pedido = Pedido::obtenerPorDetalleId($detalle->id);
      if (Pedido::ESTADO_EN_PREPARACION == $estado){
          $detalle->tiempoEstimado = $duracion;
          $detalle->horaInicio = $horaActual;
          if($pedido->estado != $estado){
            $pedido->estado = $estado;
            $pedido->horaInicio = $horaActual;
            $pedido->guardar();
          }
        } else if (Pedido::ESTADO_LISTO_PARA_SERVIR == $estado){
          $detalles = Pedido::obtenerDetalleEnPreparacion($pedido->id);
          $listoParaServir = empty($detalles);
          if ($listoParaServir) {
            $pedido->estado = $estado;
            $pedido->horaFin = $horaActual;
            $pedido->calcularImporte();
            $pedido->guardar();
          }
        }
        $detalle->estado = $estado;
        $detalle->guardar();
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
