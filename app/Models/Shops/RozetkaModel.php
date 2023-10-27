<?php
namespace App\Models\Shops;

use App\Models\ShopInterface;
use Nette;

final class RozetkaModel implements  ShopInterface
{
    private $producData;
	public function __construct(
		
	) {
        $this->LoadProducts();
	}

    public function LoadProducts(){
        $xmlstring = file_get_contents(__DIR__ ."\..\..\..\storesfiles\shop3.xml");
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $this->producData = json_decode($json,true)['product'];
       
        
    }

    public function getAll(){
        return $this->producData;
    }
}

