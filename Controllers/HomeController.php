<?php
namespace Controllers; //Usamos namespace para poder usar luego sin problema alguno la clase en otro sitio


//Dependencias necesarias 
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeController
{
    private $container;

    //Al paracer estamos instanciando desde el constructor el container
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
   
    public function index(Request $request, Response $response, $args): Response
    {   
        // $errors = (array) $request->getQueryParams();

        $view = Twig::fromRequest($request);

        $db = $this->container->get('db2');

        $listCategories = $db->indexCategories();

        return $view->render($response, '/categories/index.html',
            ['categories' => $listCategories], 
            // ['errors' => $errors['errors'] ?? []] 
        );
    }

    public function store (Request $request, Response $response, $args): Response {
        $view = Twig::fromRequest($request); //Twing es un intermediario entre el response y el render, se utiliza cuando se va a renderizar
    
        $params = (array) $request->getParsedBody();
    
        $category = $params['category'] ?? null;
    
    
        $errors = [];
    
        if (! $category) {
            $errors['first_name'] = 'Category is required.';
    
        }
        
        if (count($errors) > 0) {
            $url = "/?".http_build_query(['errors' => $errors]);
            return $response->withHeader('Location', $url)->withStatus(302);
        }
    
        $db = $this->container->get('db2');

        $db->store([
            'category' => $category,
        ]);
    
        return $response->withHeader('Location', '/categories')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $view = Twig::fromRequest($request);
    
        $db = $this->container->get('db2');

        $category = $db->find($id);
    
        $params = (array) $request->getQueryParams();

    
        return $view->render($response, '/categories/edit.html', 
        ['id' => $id,
         'category' => $category,
        // 'errors' => $params['errors'] ?? [],
        ]);
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $params = (array) $request->getParsedBody();


        $category = $params['category'] ?? null;

        $errors = [];

        if (! $category) {
            $errors['first_name'] = 'First name is required.';
        }
        
        if (count($errors) > 0) {
            $url = "/category/$id/edit?".http_build_query(['errors' => $errors]);

            return $response->withHeader('Location', $url)->withStatus(302);
        }
        
        $db = $this->container->get('db2');

        $db->edit(
            $id, 
            [
                'category' => $category,
            ]
        );

        return $response->withHeader('Location', '/categories')->withStatus(302);
    }
}