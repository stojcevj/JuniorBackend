<?php

namespace Api\Model;

use Api\Database\DB;
use PDO;

class FurnitureProduct extends Product
{
    private int $width;
    private int $height;
    private int $length;
    public function __construct($post)
    {
        parent::__construct($post['elements']['sku'], $post['elements']['name'], $post['elements']['price']);
        $this->width = $post['elements']['width'];
        $this->height = $post['elements']['height'];
        $this->length = $post['elements']['length'];
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    public function insert(DB $db)
    {
        $checkStmt = $db->prepare("SELECT * FROM products WHERE sku=:sku");
        $checkStmt->execute([
            'sku' => $this->getSku()
        ]);
        $checkStmt = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($checkStmt)){
            $object = json_encode([
                "width" => $this->getWidth(),
                "height" => $this->getHeight(),
                "length" => $this->getLength()
            ]);
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