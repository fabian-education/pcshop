<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function new_mail($mail_address, $name, $address) {
    $mail = new PHPMailer();

    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';
    
    $mail->Host       = "host";
    $mail->SMTPDebug  = 2;
    $mail->SMTPAuth   = true;
    $mail->Port       = 587;
    $mail->Username   = "your mail";
    $mail->Password   = "smtp key";
    
    $mail->isHTML(true);
    $mail->Subject = 'Thank you for purchasing!';
    
    #$name = "test";
    #$address = "test2";
    $mail->Body    = "Thanks you for purchasing in PC Shop!<br>
    <br>
    Dear ${name}, we successfully created a new order. <br>
    Your product will be delivered within 5 days to '{$address}'. <br>
    Please don't reply to this mail. <br>
    Kind regards, <br>
    PC Shop"; 
    
    $mail->setFrom('noreply@pcshop.fubs.ohaa.xyz', 'PC Shop');
    $mail->addAddress("$mail_address", "$name");
    
    $mail->send();
}


?>
