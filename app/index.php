<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './utils/AutentificadorJWT.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/PedidoProductoController.php';
require_once './controllers/PDFController.php';


require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/LoggerMiddleware.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

$app->setBasePath("/LaComanda-Prog3/app");
// Routes

//usuarios

$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/GuardarCSV', \UsuarioController::class . ':GuardarCSV');
  $group->get('/{id}', \UsuarioController::class . ':TraerUno');
  $group->post('/CargarCSV', \UsuarioController::class . ':CargarCSV');
  $group->post('[/]', \UsuarioController::class . ':CargarUno')->add(\LoggerMiddleware::class . ':VerificarRolAdmin');
  $group->put('[/]', \UsuarioController::class . ':ModificarUno');
  $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
});

//productos

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/GuardarCSV', \ProductoController::class . ':GuardarCSV');
  $group->get('/{nombre}', \ProductoController::class . ':TraerUno');
  $group->post('/CargarCSV', \ProductoController::class . ':CargarCSV');
  $group->post('[/]', \ProductoController::class . ':CargarUno')->add(\LoggerMiddleware::class . ':VerificarRolAdmin');
  $group->put('[/]', \ProductoController::class . ':ModificarUno');
  $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
});

//mesas

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos')->add(\LoggerMiddleware::class . ':VerificarRolAdmin');
  $group->get('/GuardarCSV', \MesaController::class . ':GuardarCSV');
  $group->get('/{id}', \MesaController::class . ':TraerUno');
  $group->post('/CargarCSV', \MesaController::class . ':CargarCSV');
  $group->post('[/]', \MesaController::class . ':CargarUno')->add(\LoggerMiddleware::class . ':VerificarRolAdmin');
  $group->post('/cobrarMesa', \MesaController::class . ':CobrarMesa')->add(\LoggerMiddleware::class . ':VerificarRolAdminOMozo');
  $group->post('/cerrarMesa', \MesaController::class . ':CerrarMesa')->add(\LoggerMiddleware::class . ':VerificarRolAdmin');
  $group->put('[/]', \MesaController::class . ':ModificarUno');
  $group->delete('/{id}', \MesaController::class . ':BorrarUno');
});

//pedidos

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('/Demorados', \PedidoController::class . ':obtenerDemorados')->add(\LoggerMiddleware::class . ':VerificarRolAdmin');
  $group->get('/GuardarCSV', \PedidoController::class . ':GuardarCSV');
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{id}', \PedidoController::class . ':TraerUno');
  $group->post('/CargarCSV', \PedidoController::class . ':CargarCSV');
  $group->post('/AdjuntarFotoMesa', \PedidoController::class . ':guardarFoto')->add(\LoggerMiddleware::class . ':VerificarRolAdminOMozo');
  $group->post('/VincularPedido', \PedidoController::class . ':CargarUno')->add(\LoggerMiddleware::class . ':VerificarRolAdminOMozo');
  $group->put('[/]', \PedidoController::class . ':ModificarUno');
  $group->delete('/{id}', \PedidoController::class . ':BorrarUno');
});

//pedidos-productos

$app->group('/pedidosProductos', function (RouteCollectorProxy $group) {
  $group->post('/tomarPedido', \PedidoProductoController::class . ':TomarPedidos')->add(\LoggerMiddleware::class . ':VerificarRolEmpleadosCocina');
  $group->post('/terminarPreparacionProducto', \PedidoProductoController::class . ':TomarPedidoListo')->add(\LoggerMiddleware::class . ':VerificarRolEmpleadosCocina');
  $group->post('/CargarProductosAlPedido', \PedidoProductoController::class . ':CargarUno')->add(\LoggerMiddleware::class . ':VerificarRolMozo');
  $group->get('/listaPedidosPendientes', \PedidoProductoController::class . ':TraerPorTipoEmpleadoPendiente')->add(\LoggerMiddleware::class . ':VerificarRolEmpleadosCocina');
  $group->get('/listaPedidosEnPreparacion', \PedidoProductoController::class . ':TraerPorTipoEmpleadoPreparacion')->add(\LoggerMiddleware::class . ':VerificarRolEmpleadosCocina');
  $group->get('/pedidosListos', \PedidoProductoController::class . ':PedidosListos')->add(\LoggerMiddleware::class . ':VerificarRolAdminOMozo');
  $group->get('/{codigoPedido}', \PedidoProductoController::class . ':TraerPorCodigoPedido');
});

//socios

$app->group('/socios', function (RouteCollectorProxy $group) {
  $group->post('/generarTiempoPedido', \PedidoProductoController::class . ':GenerarTiempoPedido');
  $group->get('/encuestas', \EncuestaController::class . ':TraerTodos');
  $group->get('/encuestas/ListarMejoresComentarios', \EncuestaController::class . ':ListarMejoresComentarios');
  $group->get('/mesaMasUsada', \PedidoProductoController::class . ':ListarMesaMasUsada');
})->add(\LoggerMiddleware::class . ':VerificarRolAdmin');

//clientes

$app->group('/clientes', function (RouteCollectorProxy $group) {
  $group->post('/ConsultarTiempoPedido', \PedidoProductoController::class . ':VerTiempoPedido');
  $group->post('/cargaEncuesta', \EncuestaController::class . ':CargarUno');
});

//login

$app->post('/login', \UsuarioController::class . ':LoginUsuario'); 

//pdf

$app->get('/pdf', \PDFController::class . ':GeneratePdf');

$app->run();