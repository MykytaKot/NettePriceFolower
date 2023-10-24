<?php
namespace App\Models;

use Nette;

use App\Models\DatabaseConnect;

final class ProductLoader
{
    private $database;
    
    public function __construct()
    {
        $this->database = new DatabaseConnect();
    }


    public function get_all(){
       
        return $this->database->getAll();
    }

    public function get_all_lowest_price(){
       
        $products =  $this->database->getAll();
        $uniqueProducts = [];

        foreach ($products as $product) {
            $ean = $product['ean'];
            $price = $product['price'];
    
            if (!isset($uniqueProducts[$ean]) || $price < $uniqueProducts[$ean]['price']) {
                $uniqueProducts[$ean] = $product;
            }
        }
    
        return array_values($uniqueProducts);
    }

    public function get_product($ean){
        $products = $this->database->getProductFromAllShops($ean);
        $return = $products[0];
        $return['shops'] = $products;
        return $return;
    }

}