<?php

namespace Api\Model;

use Api\Database\DB;

abstract class Product
{
    protected function __construct(private string $sku, private string $name, private float $price){
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    public abstract function insert(DB $db);
}