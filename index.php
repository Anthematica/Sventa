<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__.'/lib/Database2.php';

require_once __DIR__.'/Controllers/HomeController.php';
require_once __DIR__.'/Controllers/ProductsController.php';
require_once __DIR__.'/Controllers/BranchesController.php';
require_once __DIR__.'/Controllers/SellersController.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use League\Container\Container;
use Controllers\HomeController;
use Controllers\ProductsController;
use Controllers\BranchesController;
use Controllers\SellersController;




//ID CONTAINER

//¿COMO ES QUE LA INSTANCIA DEL CONTAINER SE ESTA USANDO EN HomeController?
$container = new Container();
$container->add('db2', Database2::class); //Usando el metodo conectar para instanciar solo una vez la BD

AppFactory::setContainer($container);
$app = AppFactory::create();

$twig = Twig::create('views', ['cache' => false]);


$app->add(TwigMiddleware::create($app, $twig));
$app->add(new MethodOverrideMiddleware());


//Index
$app->get('/', function (Request $request, Response $response, $args){
    $view = Twig::fromRequest($request);

    return $view->render($response, 'index.html');
});

//Categories routes 
$app->get('/categories', [HomeController::class, 'index']);
$app->post('/categories', [HomeController::class, 'store']);
$app->get('/categories/{id}/edit', [HomeController::class, 'edit']);
$app->patch('/categories/{id}/edit', [HomeController::class, 'update']);

//Products routes 
$app->get('/products', [ProductsController::class, 'index']);
$app->get('/products/create', [ProductsController::class, 'create']);
$app->post('/products/create', [ProductsController::class, 'store']);
$app->get('/products/{id}/edit', [ProductsController::class, 'edit']);
$app->patch('/products/{id}/edit', [ProductsController::class, 'update']);

//Branches routes 
$app->get('/branches', [BranchesController::class, 'index']);
$app->get('/branches/create', [BranchesController::class, 'create']);
$app->post('/branches/create', [BranchesController::class, 'store']);
$app->get('/branches/{id}/edit', [BranchesController::class, 'edit']);
$app->patch('/branches/{id}/edit', [BranchesController::class, 'update']);

//Sellers routes
$app->get('/sellers', [SellersController::class, 'index']);
$app->get('/sellers/create', [SellersController::class, 'create']);
$app->post('/sellers/create', [SellersController::class, 'store']);
$app->get('/sellers/{id}/edit', [SellersController::class, 'edit']);
$app->patch('/sellers/{id}/edit', [SellersController::class, 'update']);



$app->run();