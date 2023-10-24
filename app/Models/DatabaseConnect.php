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
    public function getAll(){
        $result = $this->database->query("SELECT * FROM `products`");
        return $result->fetchAll();
    }

    public function addProduct($data){
        
        $price = $data['price'];
        $name = $data['name'];
        $description = $data['description'];
        $url = $data['url'];
        $ean = $data['ean'];
        $shopname = $data['shop'];
        $query = "INSERT INTO `products` (`id`, `price`, `name`, `description`, `url`, `ean`, `shop_name`) VALUES (NULL, ?, ?, ?, ?, ?, ?)";
        $this->database->query($query, $price, $name, $description, $url, $ean, $shopname);
        
        
    }
    public function getProduct($ean , $shopname){
        $result = $this->database->query("SELECT * FROM `products` WHERE `products`.`ean` = '$ean' AND `products`.`shop_name` = '$shopname'");
        return $result->fetch();
    }

    public function getProductFromAllShops($ean){
        $result = $this->database->query("SELECT * FROM `products` WHERE `products`.`ean` = '$ean'");
        return $result->fetchAll();
    }

    public function updateProduct($ean ,$shopname ,$data){
        $price = $data['price'];
        $name = $data['name'];
        $description = $data['description'];
        $url = $data['url'];
        $ean = $data['ean'];
        $shopname = $data['shop'];
        $query = "UPDATE `products` SET `price` = ?, `name` = ?, `description` = ?, `url` = ? WHERE `ean` = ? AND `shop_name` = ?";
        $this->database->query($query, $price, $name, $description, $url, $ean, $shopname);
    
    }

}

