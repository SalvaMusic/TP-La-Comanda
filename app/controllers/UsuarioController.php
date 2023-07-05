<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $email = $parametros['email'];
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $clave = $parametros['clave'];
        $role = $parametros['role'];
        $sector = $parametros['sector'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->email = $email;
        $usr->role = getRole($role);
        $usr->sector = getSector($sector);
        $usr->clave = password_hash($clave, PASSWORD_DEFAULT);
        $usr->guardar();

        $payload = json_encode(array("mensaje" => "Usuario " . $nombre . " " . $apellido . " creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    private function getRole($role){
      return strcasecmp(ROLL_ADMIN, $role) ? ROLL_ADMIN : ROLL_EMPLEADO;
    }

    private function getSector($sector){
      if(strcasecmp(SECTOR_COCINA, $sector)){
        return SECTOR_COCINA;
      } else if(strcasecmp(SECTOR_CERVECERIA, $sector)){
        return SECTOR_CERVECERIA;
      } else if(strcasecmp(SECTOR_BAR, $sector)){
        return SECTOR_BAR;
      } else if(strcasecmp(SECTOR_MOZO, $sector)){
        return SECTOR_MOZO;
      }

      return null;
    }
}
