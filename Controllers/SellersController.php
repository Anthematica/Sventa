<?php
namespace Controllers; //Usamos namespace para poder usar luego sin problema alguno la clase en otro sitio


//Dependencias necesarias 
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class SellersController
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

        $listSellers = $db->indexSellers();

        return $view->render($response, '/sellers/index.html',
            ['sellers' => $listSellers],
        );
    }

    public function create(Request $request, Response $response, $args): Response
    {   
        $view = Twig::fromRequest($request);

        return $view->render($response, '/sellers/create.html');
    }

    public function store (Request $request, Response $response, $args): Response {
        $view = Twig::fromRequest($request); //Twing es un intermediario entre el response y el render, se utiliza cuando se va a renderizar
    
        $params = (array) $request->getParsedBody();

        $first_name = $params['first_name'] ?? null;
        $last_name = $params['last_name'] ?? null;
        $cedula = $params['cedula'] ?? null;
        $birthday= $params['birthday'] ?? null;
        $company_start_date = $params['company_start_date'] ?? null;
        $DNI = $params['DNI'] ?? null;
        $phone = $params['phone'] ?? null;
    

        // var_dump($params);
        // die;
    
        $db = $this->container->get('db2');

        $db->storeSeller([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'cedula' => $cedula,
            'birthday' => $birthday,
            'company_start_date' => $company_start_date,
            'DNI' => $DNI,
            'phone' => $phone,
        ]);
    
        return $response->withHeader('Location', '/sellers')->withStatus(302);
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