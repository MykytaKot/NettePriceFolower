Nette Web Project

Used Nette 8

PHP version 8.1

MySQL version 8


Guide how to add Shop

/app/Models/Shops

add php file with name YourShopNameModel.php

namespace App\Models\Shops;

use App\Models\ShopInterface;

use Nette;

final class YourShopNameModel implements ShopInterface
{
    // ...
}

important function is getAll() that return an php array of products from that store in format

"id" => 1,

"name" => "name",

"description" => "description",

"price" => price in float,

"currency" => "EUR",

"url" => "url to website",

"ean"=>"eancode"

example array
$products = [
    [
        "id" => 1,
        "name" => "Sony WH-1000XM4 Headphones",
        "description" => "Wireless noise-canceling headphones with exceptional sound quality.",
        "price" => 320,
        "currency" => "EUR",
        "url" => "https://www.sony.com/wh-1000xm4",
        "ean" => "2724292819966",
    ],
];

Then on a nav panel on website go to shops and add your shop name without Model for YourShopNameModel
It would be "YourShopName"

cron script to refresh prices and send email if price changes on certain percent is in folder cron/refreshPrices.php

Quick explanation of Models files

DatabaseConnect.php uses login info from local.neon and does all requests to database

Mailer.php uses mailserver info from local.neon and sends emails

PriceFollowLoader.php using DatabaseConnect.php and Mailer.php adds new followers and checks for price change

ProductLoader.php using DatabaseConnect.php controls what data will be shown on product pages

ShopInteface.php standart interface for shops

ShopLoader.php using DatabaseConnect.php gets shops and then load all data from shops using shop classes