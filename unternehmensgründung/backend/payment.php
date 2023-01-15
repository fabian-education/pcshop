<?php
session_start();

$skip_payment = $_POST['skip_payment'];

$price = $_SESSION["price"];
$md5 = $_SESSION["md5"];
$products = $_POST["products"];
$_SESSION["products"] = $products;

if ($md5 == md5($products)) {

    if ($skip_payment != "1") {
        process_payment($price);

        exit;
    }
    else {
        $_SESSION["skip_payment"]=1;

        $response = array("status"=>0,"progress"=>2);
        echo json_encode($response);

    }

}
else {
    $response = array("status"=>1);
    echo json_encode($response);
    exit;
}



function process_payment($price) {

    require_once("wallet/HD.php");

    $json_object = file_get_contents("wallet/public_config.json");
    $keys = json_decode($json_object, true);
    $xpub = $keys["pub_key"];


    $url = "https://bitpay.com/api/rates";

    $json = file_get_contents($url);
    $data = json_decode($json, TRUE);

    $rate = $data[2]["rate"];
    $amount = $price / ($rate * 2000); # to reduce required testnet coins

    $index = new_payment($amount);
    $path = strval($index);
    
    $hd = new HD();
    $hd->set_xpub($xpub);
    $address = $hd->address_from_master_pub($path);
    
    #echo $address;
    $amount = number_format($amount,6,'.','');

    $_SESSION["payment_id"] = $index;
    $_SESSION["address"] = $address;
    $_SESSION["btc_amount"] = $amount;

    $response = array("status"=>0, "address"=>$address, "amount"=>$amount, "progress"=>0);
    echo json_encode($response);

    
}

 

function new_payment($amount) {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "unternehmensgruendung";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $time = time();
    $sql = "INSERT INTO `payments`(`status`, `amount`, `tx_hash`, `date`) VALUES (0, ${amount}, 'none', ${time})";
    $result = $conn->query($sql);
    $last_id = $conn->insert_id;
    #echo $last_id;
    return $last_id;
}



?>