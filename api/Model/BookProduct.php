<?php

namespace Api\Model;

use Api\Database\DB;
use PDO;

class BookProduct extends Product
{
    private int $weight;
    public function __construct($post)
    {
        parent::__construct($post['elements']['sku'], $post['elements']['name'], $post['elements']['price']);
        $this->weight = $post['elements']['weight'];
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    public function insert(DB $db)
    {
        $checkStmt = $db->prepare("SELECT * FROM products WHERE sku=:sku");
        $checkStmt->execute([
            'sku' => $this->getSku()
        ]);
        $checkStmt = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($checkStmt)){
            $object = json_encode(["weight" => $this->getWeight()]);
            $stmt = $db->prepare("INSERT INTO products(sku, name, price, object) VALUES (:sku, :name, :price, :object)");
            $stmt->execute([
                'sku' => $this->getSku(),
                'name' => $this->getName(),
                'price' => $this->getPrice(),
                'object' => $object
            ]);
        }
    }
}