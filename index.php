<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__.'/lib/Database2.php';

require_once __DIR__.'/Controllers/HomeController.php';
require_once __DIR__.'/Controllers/ProductsController.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use League\Container\Container;
use Controllers\HomeController;
use Controllers\ProductsController;



//ID CONTAINER

//¿COMO ES QUE LA INSTANCIA DEL CONTAINER SE ESTA USANDO EN HomeController?
$container = new Container();
$container->add('db2', Database2::class); //Usando el metodo conectar para instanciar solo una vez la BD

AppFactory::setContainer($container);
$app = AppFactory::create();

$twig = Twig::create('views', ['cache' => false]);


$app->add(TwigMiddleware::create($app, $twig));
$app->add(new MethodOverrideMiddleware());


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




$app->run();