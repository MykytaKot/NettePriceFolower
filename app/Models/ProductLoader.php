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

    public function get_pagination_data(){
            $count = $this->database->getProductCount()['number'];
            $pages = ceil($count/30);
            $data['pages'] = $pages;
            $data['count'] = $count;
            return $data;
    }

    public function get_all_lowest_price($page){
       
        return $this->database->getAll($page*30);
    }

    public function get_all(){
       
        return $this->database->getAll();
    }

    public function get_product($ean){
        $return['product'] = $this->database->getProduct($ean);
        $return['shops'] = $this->database->getPrices($ean);
        
      
        
        return $return;
    }

}