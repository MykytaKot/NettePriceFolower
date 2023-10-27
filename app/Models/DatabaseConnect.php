<?php
namespace App\Models;
require __DIR__ . '/../../vendor/autoload.php';

use Nette\Neon\Neon;
use Nette;
use Nette\Database\Explorer;
use Nette\Database\Structure;


final class DatabaseConnect
{
    

    private $database;
    private $config;
    private $explorer;
    public function __construct()
    {   
        $this->config = Neon::decodeFile(__DIR__ .'/../../config/local.neon');
        $this->connect();
    }

    private function connect(){
        $host = $this->config['database']['dsn'];
        $username = $this->config['database']['user'];
        $password = $this->config['database']['password'];
        $this->database = new Nette\Database\Connection($host, $username, $password);
    }
    public function getAll($limit=100){
        $down = $limit - 30;
        $result = $this->database->query("SELECT product_ean, MIN(price) AS cheapest_price FROM productprices GROUP BY product_ean LIMIT $down, $limit");
        $products = $result->fetchAll();
        $return = [];
        
        foreach($products as $product){
            $price = $product['cheapest_price'];
            $ean = $product['product_ean'];
            $temp = $this->database->query("SELECT p.name , p.description , p.url , p.ean ,s.name AS shop , pp.price  FROM  productprices as pp JOIN products as p ON p.ean = pp.product_ean JOIN stores as s ON s.id = pp.store_id  WHERE pp.product_ean = '$ean' AND  ABS(pp.price- $price ) < 0.00001 LIMIT 1")->fetchAll();
            $return[] = $temp[0];
        }
        
        return $return;
    }

    public function getProductCount(){
        $result = $this->database->query("SELECT COUNT(id) as number FROM `products`");
        return $result->fetch();
    }

    public function addProduct($data){
    
        $name = $data['name'];
        $description = $data['description'];
        $url = $data['url'];
        $ean = $data['ean'];
        $query = "INSERT INTO `products` (`id`, `name`, `description`, `url`, `ean`) VALUES (NULL,?, ?, ?, ?)";
        $this->database->query($query, $name, $description, $url, $ean);
        
        
    }
    public function getProduct($ean){
        $result = $this->database->query("SELECT * FROM `products` WHERE `products`.`ean` = '$ean' ");
        return $result->fetch();
    }

    public function getPrices($ean){
        $result = $this->database->query("SELECT pp.price, s.name FROM `productprices` AS pp JOIN stores as s ON s.id = pp.store_id WHERE `product_ean` = '$ean';");
        return $result->fetchAll();
    }

    public function getPrice($ean,$shopname){
        $result = $this->database->query("SELECT pp.price FROM productprices AS pp JOIN stores AS s ON pp.store_id = s.id WHERE pp.product_ean = '$ean' AND s.name = '$shopname'; ");
        return $result->fetch();
    }

    public function insertPrice($ean,$shopname,$price){
        $storeid= $this->database->query("SELECT id FROM `stores` WHERE name = '$shopname';")->fetch()['id'];
        $this->database->query("INSERT INTO `productprices` (`id`, `product_ean`, `store_id`, `price`, `old_price`) VALUES (NULL,?, ?, ?, ?)",$ean,$storeid,$price,$price);
    }

    public function updatePrice($ean, $shopname, $price, $oldprice){
        $storeid= $this->database->query("SELECT id FROM `stores` WHERE name = '$shopname';")->fetch()['id'];
        $this->database->query("UPDATE `productprices` SET `price` = ?, `old_price` = ? WHERE `product_ean` = ? AND `store_id` = ?", $price, $oldprice, $ean, $storeid);

    }


    public function updateProduct($ean ,$data){
        
        $name = $data['name'];
        $description = $data['description'];
        $url = $data['url'];
        $ean = $data['ean'];
        
        $query = "UPDATE `products` SET `name` = ?, `description` = ?, `url` = ? WHERE `ean` = ?";
        $this->database->query($query, $name, $description, $url, $ean);
    
    }

    public function getStoreNames(){
        $result = $this->database->query("SELECT `name` FROM `stores`");
        return $result->fetchAll();
    }

    public function addFollower($email,$change,$ean){
        $insertQuery = "
        INSERT INTO pricechange (product_ean, user_id, percentage)
        SELECT 
            ?,
            u.id,
            ?
        FROM users u
        WHERE u.email = ?";
        $this->database->query($insertQuery, $ean, $change, $email);
    }

    public function getFollower($email,$ean){
        $result = $this->database->query("SELECT * FROM `pricechange` WHERE `pricechange`.`product_ean` = '$ean' AND `pricechange`.`user_id` = (SELECT `users`.`id` FROM `users` WHERE `users`.`email` = '$email' )");
        return $result->fetch();
    }

    public function getStoreByPriceAndEan($ean, $price){
        $result = $this->database->query("SELECT s.name FROM `productprices` AS pp JOIN stores AS s ON pp.store_id = s.id WHERE pp.product_ean = '$ean' AND ABS(pp.price- $price ) < 0.00001 LIMIT 1");
        return $result->fetch();
    }

    public function updateFollower($email,$change,$ean){
        $updateQuery = "
        UPDATE pricechange
        SET percentage = ?
        WHERE user_id = (
            SELECT id
            FROM users
            WHERE email = ?
        ) AND product_ean = ?";
        $this->database->query($updateQuery, $change, $email, $ean);
    }
    
    public function addUser($email){
        $this->database->query("INSERT INTO `users` (`id`, `email`) VALUES (NULL, ?)",$email);
    }

    public function getUser($email){
        $result = $this->database->query("SELECT * FROM `users` WHERE `users`.`email` = '$email' ");
        return $result->fetch();
    }

    public function GetMinPrices($ean){
        $result = $this->database->query("SELECT MIN(price) as min_price , MIN(old_price) as min_old_price FROM `productprices` WHERE product_ean = '$ean' GROUP BY product_ean");
        return $result->fetch();
    }

    public function GetSubcribedProducts(){
        $result = $this->database->query("SELECT DISTINCT `product_ean` FROM `pricechange`;");
        return $result->fetchAll();
    }
    public function GetSubscribersPercent($ean,$percent){
        $result = $this->database->query("SELECT pc.product_ean, u.email , pc.percentage FROM `pricechange` as pc JOIN users as u on pc.user_id = u.id   WHERE `product_ean` = '$ean' AND percentage <= $percent;");
        return $result->fetchAll();
    }
    public function DeleteFollowing($id){
        $this->database->query("DELETE FROM `pricechange` WHERE `pricechange`.`id` = ?",$id);

    }
    public function AddShop($name){
        $this->database->query("INSERT INTO `stores` (`id`, `name`) VALUES (NULL, ?)",$name);
    }
    public function DeleteShop($name){
        $this->database->query("DELETE FROM `stores` WHERE `stores`.`name` = ?",$name);
    }
    
}   

