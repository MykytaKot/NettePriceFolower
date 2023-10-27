<?php
namespace App\Models;
require __DIR__ . '/../../vendor/autoload.php';
use Nette;
use Nette\Neon\Neon;

final class Mailer
{
    

    private $mailer;
    private $config;

    public function __construct()
    {   
        $this->config = Neon::decodeFile(__DIR__ .'/../../config/local.neon')['mail'];
        $this->connect();
    }

    private function connect(){
        $this->mailer = new Nette\Mail\SmtpMailer(
            host: $this->config['host'],
            username: $this->config['username'],
            password: $this->config['password'],
            port: $this->config['port']
        );
    }

    public function Send($data){
        $mail = new Nette\Mail\Message;
        $mail->setFrom($this->config['clientHost'])
            ->addTo($data['to'])
            ->setSubject($data['subject'])
            ->setHtmlBody($data['body']);
        $this->mailer->send($mail);
    }

    public function HtmlBodyStandart($title ,$message){
      
        $html ='
            <!DOCTYPE html>
            <html lang="en" data-bs-theme="dark">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
                <title>Email</title>
            </head>
            <body>
                <div class="container my-5">
                <div class="p-5 text-center bg-body-tertiary rounded-3">
                <h1 class="text-body-emphasis">'.$title.'</h1>
                <p class="lead">
                    '.$message.'
                </p>
                </div>
               
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
            </body>
            </html>
        ';
        return $html;
    }
}