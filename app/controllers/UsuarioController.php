<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once 'JWTController.php';

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
        $usr->role_ = $this->getRole($role);
        $usr->sector = $this->getSector($sector);
        $usr->clave = password_hash($clave, PASSWORD_DEFAULT);
        $usr->guardar();

        $payload = json_encode(array("mensaje" => "Usuario " . $nombre . " " . $apellido . " creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function login($request, $response, array $args)
    {
        $parametros = $request->getParsedBody();

        $email = $parametros['email'];
        $clave = $parametros['clave'];

        $usr = Usuario::obtenerUsuarioPorEmail($email);

        if ($usr != null) {
            if (password_verify($clave, $usr->clave)) {
                $token = JWTController::crearToken($usr->id, $email, $usr->tipo);
                $mensaje = "Usuario " . $email ." Logeado correctamente.  Sector: " . $usr->tipo;
                $retorno = json_encode(array("mensaje" => $mensaje));
                $response = $response->withHeader('Authorization', $token);
            } else {
                $retorno = json_encode(array("mensaje" => "Contraseña incorrecta"));
            }
        } else {
            $retorno = json_encode(array("mensaje" => "Usuario no encontrado"));
        }
        $response->getBody()->write($retorno);
        return $response;
    }
    
    public function TraerUno($request, $response, $args)
    {
       
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

    function getRole($role){
      return strcasecmp(ROLL_ADMIN, $role) ? ROLL_ADMIN : ROLL_EMPLEADO;
    }

    public function getSector($sector){
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
