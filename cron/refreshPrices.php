<?php
namespace Cron;
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Mailer;
use App\Models\PriceFollowLoader;
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
echo('prices were updated /n');
$priceFollow = new PriceFollowLoader();

$mailer = new Mailer();

foreach ($database->GetSubcribedProducts() as $product){
    $ean = $product['product_ean'];
    $priceChange = $priceFollow->CheckPriceChangeForProduct($ean);
    
    $users = $database->GetSubscribersPercent($ean, $priceChange['change']);
    if($users){
        $product = $database->getProduct($ean);
        $store = $database->getStoreByPriceAndEan($ean,$priceChange['price']);
      
        foreach($users as $user){
            $body = $mailer->HtmlBodyStandart($product['name'],"Price for this product changed by more that {$user['percentage']}%. <br> Now price is {$priceChange['price']} EUR on {$store['name']}");
            $mailer->Send(['to'=>$user['email'],'subject'=>'Price Follow' , 'body'=>$body]);
        }
    }
    
}
echo('emails were sent');




