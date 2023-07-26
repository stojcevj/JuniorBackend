<?php

namespace Api\Model;

use Api\Database\DB;
use PDO;

class DvdProduct extends Product
{
    private int $size;
    public function __construct($post)
    {
        parent::__construct($post['elements']['sku'], $post['elements']['name'], $post['elements']['price']);
        $this->size = $post['elements']['size'];
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    public function insert(DB $db)
    {
        $checkStmt = $db->prepare("SELECT * FROM products WHERE sku=:sku");
        $checkStmt->execute([
            'sku' => $this->getSku()
        ]);
        $checkStmt = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($checkStmt)){
            $object = json_encode(["size" => $this->getSize()]);
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