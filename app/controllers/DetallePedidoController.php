<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './models/DetallePedido.php';
require_once './interfaces/IApiUsable.php';

class DetallePedidoController extends DetallePedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      
        return $response
          ->withHeader('Content-Type', 'application/json');
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
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("lista Pedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientes($request, $response, $args)
    {
        $sector = isset($args['sector']) ? $args['sector'] : 'TODOS';
        $pedido = isset($args['pedido']) ? $args['pedido'] : 'TODOS';
        $lista = DetallePedido::obtenerPendientes($pedido, $sector);
        $payload = json_encode(array("lista Pedidos" => $lista));

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
