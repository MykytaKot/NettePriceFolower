<?php
namespace App\Models;

use Nette;



final class ShopLoader
{
    private $AllProducts;
    
    public function __construct()
    {
        $this->AllProducts = $this->load_all();
    }

    private function load_all(){
        $shops = ['Allo','Citrus','Rozetka'];
        $shopsData = [];
        foreach ($shops as $shop) {
            $shopModel = 'App\Models\Shops\\'.$shop.'Model';
            $shopModel = new $shopModel();
            $shoptemp = $shopModel->getAll();
            foreach ($shoptemp as $product) {
                $product['shop'] = $shop;
                $shopsData[] = $product;
            }
        }
        return $shopsData;
    }

    public function get_all(){
        return $this->AllProducts;
    }

    public function get_all_lowest_price(){
        
        $uniqueProducts = [];

        foreach ($this->AllProducts as $product) {
            $ean = $product['ean'];
            $price = $product['price'];
    
            if (!isset($uniqueProducts[$ean]) || $price < $uniqueProducts[$ean]['price']) {
                $uniqueProducts[$ean] = $product;
            }
        }
    
        return array_values($uniqueProducts);
    }

    public function get_product($ean){
        $products = [];
        foreach ($this->AllProducts as $product) {
            if($product['ean'] == $ean){
                $products[] = $product;
            }
        }
        $return = $products[0];
        $return['shops'] = $products;
        return $return;
    }

    
}