<?php

class Database2 {

    protected $pdo;

    //Los construcutores retornan automaticamente la intancia de la clase
    public function __construct() {
        try {
            $this->pdo= new PDO(
                'mysql:host=127.0.0.1;dbname=sistema_venta',
                'root',
                'Cosmos211100'
            );


        } catch (\PDOException $e) {
            die('Could not connect');
        }
    }

    public function indexCategories (): ? array {

        $query = $this->pdo->prepare('SELECT *FROM categories'); //Preparo la tabla
        
        $query->execute(); //Ejecuto la consulta

        $categories = $query->fetchAll(PDO::FETCH_ASSOC); //FETCH_ASSOC convierte el contenido de la BD a un array asociativo 


        return $categories;
    }

    public function store (array $data): void {
        $query = $this->pdo->prepare('INSERT INTO categories (category) VALUES(:category)');

        //Asiganamos los valores
        $query->execute (
            [
                //El primer valor corresponde al placeholder 
                'category' => $data['category'],
            ] 
        );
    }

    public function find (string $id) {
        $query = $this->pdo->prepare ('SELECT *FROM categories WHERE id = :id');

        $query->execute(
            ['id' => $id]
        );

        $category = $query->fetch(PDO::FETCH_ASSOC);

        return $category;
    }

    public function findProduct (string $id) {

        $query = $this->pdo->prepare('SELECT P.id AS product_id, P.product_name, C.category, C.id AS category_id  FROM products AS P JOIN categories AS C ON  P.category_id = C.id WHERE P.id = :id');

        $query->execute(
            ['id' => $id]
        );

        $product = $query->fetch(PDO::FETCH_ASSOC);

        return $product;
    }

    public function edit (int $id , array $data): void {
        $query = $this->pdo->prepare
        (
        'UPDATE categories SET category = :category WHERE id = :id'
        );

        $query->execute(
            [
                'id' => $id,
                'category' => $data['category'],
            ]
        );
    }

    public function indexProducts (): ? array {

        $query = $this->pdo->prepare('SELECT P.id, P.product_name, C.category FROM products AS P
        JOIN categories AS C ON P.category_id = C.id'); //Preparo la tabla
        
        $query->execute(); //Ejecuto la consulta

        $products = $query->fetchAll(PDO::FETCH_ASSOC); //FETCH_ASSOC convierte el contenido de la BD a un array asociativo 
        return $products;
    }

    public function storeProducts (array $data): void {
        $query = $this->pdo->prepare('INSERT INTO products (product_name, category_id) VALUES(:product_name, :category_id)');

        //Asiganamos los valores
        $query->execute (
            [
                //El primer valor corresponde al placeholder 
                'product_name' => $data['product'],
                'category_id' => (int) $data['category'],
            ] 
        );
    }

    public function editProduct (int $id , array $data): void {
        $query = $this->pdo->prepare
        (
        'UPDATE products SET product_name = :product_name, category_id = :category_id WHERE id = :id'
        );

        $query->execute(
            [
                'id' => $id,
                'product_name' => $data['product'],
                'category_id' =>(int) $data['category'],
            ]
        );
    }

    public function indexBranches (): ? array {

        $query = $this->pdo->prepare('SELECT *FROM branches'); //Preparo la tabla
        
        $query->execute(); //Ejecuto la consulta

        $branches= $query->fetchAll(PDO::FETCH_ASSOC); //FETCH_ASSOC convierte el contenido de la BD a un array asociativo 


        return $branches;
    }

    public function storeBranches (array $data): void {
        $query = $this->pdo->prepare('INSERT INTO branches (state, city, address, phone) VALUES(:state, :city, :address, :phone)');

        //Asiganamos los valores
        $query->execute (
            [
                //El primer valor corresponde al placeholder 
                'state' => $data['state'],
                'city' => $data['city'],
                'address' => $data['address'],
                'phone' => $data['phone'],
            ] 
        );
    }

    public function findBranch (string $id) {
        $query = $this->pdo->prepare ('SELECT *FROM branches WHERE id = :id');

        $query->execute(
            ['id' => $id]
        );

        $branch = $query->fetch(PDO::FETCH_ASSOC);

        return $branch;
    }

    
    public function editBranch (int $id , array $data): void {
        $query = $this->pdo->prepare
        (
        'UPDATE branches SET state = :state, city = :city, address = :address, phone = :phone WHERE id = :id'
        );


        $query->execute(
            [
                'id' => $id,
                'state' => $data['state'],
                'city' => $data['city'],
                'address' => $data['address'],
                'phone' => $data['phone'],
            ]
        );
    }

}
