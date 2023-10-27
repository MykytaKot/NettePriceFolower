<?php
namespace App\Models;

use Nette;
use App\Models\DatabaseConnect;




final class ShopLoader
{
    private $AllProducts;
    private $dbConnection;
    
    public function __construct()
    {
        $this->dbConnection = new  DatabaseConnect();
        $this->AllProducts = $this->load_all();

    }

    private function load_all(){
        $shops = $this->dbConnection->getStoreNames();
      
        $shopsData = [];
        foreach ($shops as $shop) {
            $shop = $shop['name'];
            $shopModel = 'App\Models\Shops\\'.$shop.'Model';
            try{
            $shopModel = new $shopModel();
            $shoptemp = $shopModel->getAll();
            foreach ($shoptemp as $product) {
                $product['shop'] = $shop;
                $shopsData[] = $product;
            }
            }catch(Exception $e) {
                echo "Error loading shop ".$shop;
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