<?php
namespace App\Models;

use Nette;

use App\Models\DatabaseConnect;
use App\Models\Mailer;

final class PriceFollowLoader
{
    private $database;
    private $mailer;
    public function __construct()
    {
        
        $this->database = new DatabaseConnect();
        $this->mailer = new Mailer();
    }




    public function addFollower($data){
            $email = $data['email'];
            $change = $data['change'];
            $ean = $data['ean'];
            $user = $this->database->getUser($email);
            $message= 'You have successfully started following this item.';
            if($user){
                $follow = $this->database->getFollower($email,$ean);
                if($follow){
                    $message = 'You have successfully changed your following settings.';
                    $this->database->updateFollower($email,$change,$ean);
                }else{
                    
                    $this->database->addFollower($email,$change,$ean);
                }
                
            }else{
                $this->database->addUser($email);
                $this->database->addFollower($email,$change,$ean);
            }
            $item = $this->database->getProduct($ean);
            $html = $this->mailer->HtmlBodyStandart($item['name'], $message);
            $this->mailer->Send([
                'to' => $email,'subject'=>'Price Follow' , 'body'=>$html]);
            return $message;
    }


    public function CheckPriceChangeForProduct($ean){
        $priceChange = $this->database->GetMinPrices($ean);
        $current  = $priceChange['min_price'];
        $old = $priceChange['min_old_price'];
        if($old <= $current){
            $return = ['price' => $current , 'change' =>0];
            return $return;
        }
        $percentageChange = round((($old - $current ) / $old) * 100);
        $return = ['price' => $current , 'change' =>$percentageChange];
        return $return;

    }

}