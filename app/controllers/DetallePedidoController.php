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
        $pedido = isset($args['pedido']) ? $args['pedido'] : null;
        $lista = Pedido::obtenerPendientesDetalles($pedido);
        $payload = json_encode(array("lista Pedidos" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function PrepararPedido($request, $response, $args)
    {
        $data = $request->getParsedBody();
        $id = $args['detallePedidoId'];
        $duracion = $data['duracion'];

        $this->ModificarPedido($id, $duracion, Pedido::ESTADO_EN_PREPARACION);

        $retorno = array(
          "mensaje" => "Pedido tomado con exito",
          "Detalle" => $detalle);
        $payload = json_encode($retorno);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function FinalizarPedido($request, $response, $args)
    {
        $data = $request->getParsedBody();
        $id = $args['detallePedidoId'];
        $duracion = $data['duracion'];

        $this->ModificarPedido($id, $duracion, Pedido::ESTADO_LISTO_PARA_SERVIR);

        $retorno = array(
          "mensaje" => "Pedido finalizado con exito",
          "Detalle" => $detalle);
        $payload = json_encode($retorno);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarPedido($id, $duracion, $estado)
    {
        $detalle = DetallePedido::obtener($id);
        $detalle->tiempoEstimado = $duracion;
        $detalle->estado = $estado;
        $detalle->guardar();

        $pedido = Pedido::obtenerPorDeralleId($id);
        if (Pedido::ESTADO_EN_PREPARACION === $estado && $pedido->estado !== $estado){
          $pedido->estado = $estado;
          $pedido->horaInicio = date('H:i:s');
          $pedido->guardar();
        } else if (Pedido::ESTADO_LISTO_PARA_SERVIR === $estado){
          $detalles = Pedido::obtenerDetalleEnPreparacion($pedido->id);
          $listoParaServir = empty($detalles);
          if ($listoParaServir) {
            $pedido->estado = $estado;
            $pedido->horaFin = date('H:i:s');
            $pedido->guardar();
          }
        } 
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
