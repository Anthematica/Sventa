<?php
namespace Controllers; //Usamos namespace para poder usar luego sin problema alguno la clase en otro sitio


//Dependencias necesarias 
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class SalesController
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

        $listSales = $db->indexSales();

        return $view->render($response, '/sales/index.html',
            ['sales' => $listSales],
        );
    }

    public function create(Request $request, Response $response, $args): Response
    {   
        $view = Twig::fromRequest($request);

        $db =  $this->container->get('db2');
        $listSales = $db->indexSales();
        $listProducts = $db->indexProducts2();
        $listSellers = $db->indexSellers(); 
        $listBranches = $db->indexBranches();

        return $view->render($response, '/sales/create.html',
            [
                'sales' => $listSales,
                'products' =>$listProducts,
                'sellers' => $listSellers,
                'branches' => $listBranches,
            ]
        );
    }

    public function store (Request $request, Response $response, $args): Response {
        $view = Twig::fromRequest($request); //Twing es un intermediario entre el response y el render, se utiliza cuando se va a renderizar
    
        $params = (array) $request->getParsedBody();

        $seller_id = $params['sellers'] ?? null;
        $product_id = $params['products'] ?? null;
        $branch_id = $params['branches'] ?? null;
        $amount= $params['amount'] ?? null;
        $price = $params['price'] ?? null;
        $sale_date = $params['sale_date'] ?? null;
    
    
        $db = $this->container->get('db2');

        $db->storeSales([
            'sellers' => $seller_id,
            'product_id' => $product_id,
            'branch_id' => $branch_id,
            'amount' => $amount,
            'price' => $price,
            'sale_date' => $sale_date,
        ]);
    
        return $response->withHeader('Location', '/sales')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $view = Twig::fromRequest($request);

    
        $db = $this->container->get('db2');

        $seller = $db->findSeller($id);

        
        return $view->render($response, '/sellers/edit.html', 
            [
                'id' => $id,
                'seller' => $seller,
            ]
        );
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $params = (array) $request->getParsedBody();

        $first_name = $params['first_name'] ?? null;
        $last_name = $params['last_name'] ?? null;
        $birthday = $params['birthday'] ?? null;
        $cedula = $params['cedula'] ?? null;
        $company_start_date = $params['company_start_date'] ?? null;
        $DNI = $params['DNI'] ?? null;
        $phone = $params['phone'] ?? null;

        $db = $this->container->get('db2');

        $db->editSeller(
            $id, 
            [
                'first_name' =>  $first_name,
                'last_name' => $last_name,
                'birthday' => $birthday,
                'cedula' => $cedula,
                'company_start_date' => $company_start_date,
                'DNI' => $DNI,
                'phone' => $phone,
            ]
        );

        return $response->withHeader('Location', '/sellers')->withStatus(302);
    }
}