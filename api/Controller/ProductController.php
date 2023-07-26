<?php

namespace Api\Controller;

use Api\Main;
use PDO;

class ProductController
{
    public function index(): void
    {
         $stmt = Main::getDbInstance()->prepare("SELECT * FROM products ORDER BY sku");
         $stmt->execute();
         echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function massDelete(string $queryString, $post) : void
    {
        if(empty($post)){
            http_response_code(404);
        }

        foreach(json_decode($post['body']) as $element){
            echo $element;
            $stmt = Main::getDbInstance()->prepare("DELETE FROM products WHERE sku=:sku");
            $stmt->execute([
                'sku' => $element
            ]);
        }
    }

    public function addProduct(string $queryString, $post) : void
    {
        if(empty($post)){
            http_response_code(404);
        }

        if(empty($queryString)){
            http_response_code(404);
        }

        $queryString = explode('=', $queryString);

        if($queryString[0] == 'type'){
            $className = $queryString[1];
            if(class_exists('\\Api\\Model\\' . $className)){
                $productType = '\\Api\\Model\\' . $className;
                $tmpProduct = new $productType($post);
                $tmpProduct->insert(Main::getDbInstance());
            }else{
                http_response_code(404);
            }
        }else{
            http_response_code(404);
        }
    }
}