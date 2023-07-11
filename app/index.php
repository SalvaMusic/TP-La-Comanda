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
require_once './middlewares/AutenticacionMiddleware.php';

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
  ;

});

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->post('/cargar', \PedidoController::class . ':CargarUno')
    ->add(new AutenticacionMiddleware(array("Admin", "Mozo")));

  $group->get('/tiempoRestante/{codPedido}', \PedidoController::class . ':TiempoRestante');

  $group->get('/traerTodos', \DetallePedidoController::class . ':TraerTodos')
    ->add(new AutenticacionMiddleware(array()));

  $group->get('/traerPendientes/{codPedido}', \DetallePedidoController::class . ':TraerPendientes')
    ->add(new AutenticacionMiddleware(array()));

  $group->put('/prepararPedido/{detallePedidoId}', \DetallePedidoController::class . ':PrepararPedido')
    ->add(new AutenticacionMiddleware(array("Cocina", "Cervecería", "Barra")));

  $group->put('/finalizarPedido/{detallePedidoId}', \DetallePedidoController::class . ':FinalizarPedido')
    ->add(new AutenticacionMiddleware(array("Cocina", "Cervecería", "Barra")));

});

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->put('/cambiarEstado/{id}', \MesaController::class . ':CambiarEstado')
    ->add(new AutenticacionMiddleware(array("Mozo", "Cocina", "Cervecería", "Barra")));

  $group->put('/cerrar/{id}', \MesaController::class . ':CerrarMesa')
    ->add(new AutenticacionMiddleware(array("Admin")));

});

$app->run();
