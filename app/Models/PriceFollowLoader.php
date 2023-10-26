<?php
namespace App\Models;

use Nette;

use App\Models\DatabaseConnect;

final class PriceFollowLoader
{
    private $database;
    
    public function __construct()
    {
        $this->database = new DatabaseConnect();
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
            return $message;
    }

}