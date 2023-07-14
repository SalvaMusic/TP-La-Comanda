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
    $usr->sector = $this->getSector($sector);
    $usr->clave = password_hash($clave, PASSWORD_DEFAULT);
    $usr->guardar();

    $payload = json_encode(array("mensaje" => "Usuario " . $nombre . " " . $apellido . " creado con exito"));

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
  }

  public static function login($request, $response, array $args)
  {
    $parametros = $request->getParsedBody();

    $email = $parametros['email'];
    $clave = $parametros['clave'];
    $usr = Usuario::obtenerUsuarioPorEmail($email);
    if ($usr == null) {
      $retorno = json_encode(array("mensaje" => "Usuario no encontrado"));

    } else if ($usr->estado != Usuario::ESTADO_ACTIVO) {
      $retorno = json_encode(array("mensaje" => "Usuario " . $usr->estado));

    } else if (!password_verify($clave, $usr->clave)) {
      $retorno = json_encode(array("mensaje" => "Contraseña incorrecta"));

    } else {
      $token = JWTController::crearToken($usr->id, $email, $usr->sector);
      $mensaje = "Usuario " . $email . " Logeado correctamente.  Sector: " . $usr->sector;
      $retorno = json_encode(array("mensaje" => $mensaje));
      $response = $response->withHeader('Authorization', $token);
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

  public function ModificarUno($request, $response, $args) {  }

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

  public function getSector($sector)
  {
    if (strcasecmp(Usuario::SECTOR_COCINA, $sector)) {
      return Usuario::SECTOR_COCINA;
    } else if (strcasecmp(Usuario::SECTOR_CERVECERIA, $sector)) {
      return Usuario::SECTOR_CERVECERIA;
    } else if (strcasecmp(Usuario::SECTOR_BAR, $sector)) {
      return Usuario::SECTOR_BAR;
    } else if (strcasecmp(Usuario::SECTOR_MOZO, $sector)) {
      return Usuario::SECTOR_MOZO;
    } else if (strcasecmp(Usuario::SECTOR_ADMIN, $sector)) {
      return Usuario::SECTOR_ADMIN;
    }

    return null;
  }
}
