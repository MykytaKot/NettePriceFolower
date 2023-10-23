<?php
namespace App\Models\Shops;

use App\Models\ShopInterface;
use Nette;

final class CitrusModel implements  ShopInterface
{
    private $producData;
	public function __construct(
		
	) {
        $this->LoadProducts();
	}

    public function LoadProducts(){
        $xmlstring = file_get_contents("https://raw.githubusercontent.com/MykytaKot/Price-folower-jsons/main/shop1.xml");
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $this->producData = json_decode($json,true)['product'];
       
        
    }

    public function getAll(){
        return $this->producData;
    }
}

