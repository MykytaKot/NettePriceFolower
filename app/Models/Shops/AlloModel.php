<?php
namespace App\Models\Shops;

use App\Models\ShopInterface;
use Nette;

final class AlloModel implements  ShopInterface
{
    private $producData;
	public function __construct(
		
	) {
        $this->LoadProducts();
	}

    public function LoadProducts(){
        $this->producData = json_decode(file_get_contents(__DIR__ ."\..\..\..\storesfiles\shop2.json"),true)['products'];
        
    }

    public function getAll(){
        return $this->producData;
    }
}

