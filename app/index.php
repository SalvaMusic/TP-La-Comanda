<?php
// Error Handling

// php -S localhost:666 -t app
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/DetallePedidoController.php';
require_once './controllers/MesaController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . ':login');
  $group->get('/arma/{nombreArma}', \UsuarioController::class . ':TraerUno')
    //->add(new AutenticacionMiddleware("Admin"))
  ;

});

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->post('/cargar', \PedidoController::class . ':CargarUno');
  $group->get('/tiempoRestante/{codPedido}', \PedidoController::class . ':TiempoRestante');
  $group->get('/traerTodos', \DetallePedidoController::class . ':TraerTodos');
  $group->get('/traerPendientes', \DetallePedidoController::class . ':TraerPendientes');
  $group->put('/prepararPedido', \DetallePedidoController::class . ':PrepararPedido');
  $group->put('/finalizarPedido', \DetallePedidoController::class . ':FinalizarPedido');
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->put('/cambiarEstado/{id}', \MesaController::class . ':CambiarEstado');
  $group->put('/cerrar/{id}', \MesaController::class . ':CerrarMesa');
});

$app->run();
