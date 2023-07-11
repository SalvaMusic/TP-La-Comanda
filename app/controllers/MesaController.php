<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './models/DetallePedido.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CambiarEstado($request, $response, $args)
    {
        $listaErrores = array();
        $mesaId = $args['id'];
        $data = $request->getParsedBody();
        $estado = $data['estado'];
        $mesa = Mesa::obtener($mesaId);

        if($mesa !== null){
            switch ($estado) {
                case "1":
                    $mesa->estado = Mesa::ESTADO_CLI_ESPERANDO;
                    break;
                case "2":
                    $mesa->estado = Mesa::ESTADO_CLI_COMIENDO;
                    break;
                case "3":
                    $mesa->estado = Mesa::ESTADO_CLI_PAGANDO;
                    break;
                case "4":
                    $mesa->estado = Mesa::ESTADO_CERRADA; 
                    break;
                default:
                    $listaErrores[] = "Estado " . $estado . "Inexistente.";

            }
        } else {
            $listaErrores[] = "Mesa " . $mesaId . "Inexistente.";
        }
        
        if (empty($listaErrores)) {
            $mensaje = "Mesa: " . $mesa->id . " | " . $mesa->estado;
            $payload = json_encode(array("mensaje" => $mensaje));
        } else {
            $payload = json_encode(array("Errores" => $listaErrores));
        }


        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CerrarMesa($request, $response, $args)
    {
        $listaErrores = array();
        $mesaId = $args['id'];
        $mesa = Mesa::obtener($mesaId);

        if($mesa !== null){
            $mesa->estado = Mesa::ESTADO_CERRADA; 
        } else {
            $listaErrores[] = "Mesa " . $mesaId . "Inexistente.";
        }
        
        if (empty($listaErrores)) {
            $mensaje = "Mesa: " . $mesa->id . " | " . $mesa->estado;
            $payload = json_encode(array("mensaje" => $mensaje));
        } else {
            $payload = json_encode(array("Errores" => $listaErrores));
        }


        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("lista Mesas" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
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
