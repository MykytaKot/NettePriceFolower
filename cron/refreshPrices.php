<?php
namespace Cron;
require __DIR__ . '/../vendor/autoload.php';

use App\Models\DatabaseConnect;
use App\Models\ShopLoader;

$loader = new ShopLoader();
$database = new DatabaseConnect();


foreach ($loader->get_all() as $product){
    $ean = $product['ean'];
    $shopname = $product['shop'];
    $data = $database->getProduct($ean);
    if($data){
        $database->updateProduct($ean ,$product);
        $price = $database->getPrice($ean, $shopname);
        if($price){
            $database->updatePrice($ean,$shopname,$product['price'],$price['price']);
        }else{
            $database->insertPrice($ean,$shopname,$product['price']);
        }
    }else{
        $database->addProduct($product);
        $database->insertPrice($ean,$shopname,$product['price']);
    }
}





