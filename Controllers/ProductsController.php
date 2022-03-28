<?php
namespace Controllers; //Usamos namespace para poder usar luego sin problema alguno la clase en otro sitio


//Dependencias necesarias 
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ProductsController
{
    private $container;

    //Al paracer estamos instanciando desde el constructor el container
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function index(Request $request, Response $response, $args): Response
    {   
        $view = Twig::fromRequest($request);

        $db = $this->container->get('db2');

        $listProducts = $db->indexProducts();
        
        // var_dump($listProducts);
        // die;
        return $view->render($response, '/products/index.html',
            ['products' => $listProducts],
        );
    }

    public function create(Request $request, Response $response, $args): Response
    {   
        $view = Twig::fromRequest($request);

        $db =  $this->container->get('db2');
        $listCategories = $db->indexCategories();

        return $view->render($response, '/products/create.html',
            ['categories' => $listCategories]
        );
    }

    public function store (Request $request, Response $response, $args): Response {
        $view = Twig::fromRequest($request); //Twing es un intermediario entre el response y el render, se utiliza cuando se va a renderizar
    
        $params = (array) $request->getParsedBody();

        $product = $params['product'] ?? null;
        $categoryId = $params['category'] ?? null;

        // var_dump($params);
        // die;
    
        $db = $this->container->get('db2');

        $db->storeProducts([
            'product' => $product,
            'category' => $categoryId,
        ]);
    
        return $response->withHeader('Location', '/products')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $view = Twig::fromRequest($request);

    
        $db = $this->container->get('db2');

        $product = $db->findProduct($id);
        $category = $db->indexCategories();
    
        // $params = (array) $request->getQueryParams();

        return $view->render($response, '/products/edit.html', 
            [
                'id'=> $id,
                'product' => $product,
                'categories' => $category
            ]
        );
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $params = (array) $request->getParsedBody();


        $product = $params['product'] ?? null;
        $category_id = $params['category'] ?? null;

        $db = $this->container->get('db2');

        $db->editProduct(
            $id, 
            [
                'product' => $product,
                'category' => $category_id,
            ]
        );

        return $response->withHeader('Location', '/products')->withStatus(302);
    }
}