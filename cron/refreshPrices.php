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
    $data = $database->getProduct($ean, $shopname);
    if($data){
        $database->updateProduct($ean ,$shopname ,$product);
    }else{
        $database->addProduct($product);
    }
}





