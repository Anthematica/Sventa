<?php
namespace Controllers; //Usamos namespace para poder usar luego sin problema alguno la clase en otro sitio


//Dependencias necesarias 
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class BranchesController
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

        $listBranches = $db->indexBranches();

        return $view->render($response, '/branches/index.html',
            ['branches' => $listBranches],
        );
    }

    public function create(Request $request, Response $response, $args): Response
    {   
        $view = Twig::fromRequest($request);

        $db =  $this->container->get('db2');

        return $view->render($response, '/branches/create.html');
    }

    public function store (Request $request, Response $response, $args): Response {
        $view = Twig::fromRequest($request); //Twing es un intermediario entre el response y el render, se utiliza cuando se va a renderizar
    
        $params = (array) $request->getParsedBody();

        $state = $params['state'] ?? null;
        $city = $params['city'] ?? null;
        $address = $params['address'] ?? null;
        $phone = $params['phone'] ?? null;
    

        // var_dump($params);
        // die;
    
        $db = $this->container->get('db2');

        $db->storeBranches([
            'state' => $state,
            'city' => $city,
            'address' => $address,
            'phone' => $phone,
        ]);
    
        return $response->withHeader('Location', '/branches')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $view = Twig::fromRequest($request);

    
        $db = $this->container->get('db2');

        $branch = $db->findBranch($id);

        
        return $view->render($response, '/branches/edit.html', 
            [
                'id' => $id,
                'branch' => $branch,
            ]
        );
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $params = (array) $request->getParsedBody();


        $state = $params['state'] ?? null;
        $city = $params['city'] ?? null;
        $address = $params['address'] ?? null;
        $phone = $params['phone'] ?? null;

        $db = $this->container->get('db2');

        $db->editBranch(
            $id, 
            [
                'state' =>  $state,
                'city' => $city,
                'address' => $address,
                'phone' => $phone,
            ]
        );

        return $response->withHeader('Location', '/branches')->withStatus(302);
    }
}