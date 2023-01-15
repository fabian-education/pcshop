<?php
session_start();



if (isset($_SESSION["tx"]) && isset($_SESSION["payment_id"]) && isset($_SESSION["products"]) && isset($_POST["name"])) {
    $id = $_SESSION["payment_id"];
    $products = $_SESSION["products"];

    $name = $_POST["name"];
    $mail = $_POST["mail"];
    $address = $_POST["address"];

    order($id, $products, $name, $mail, $address);
}
else if ($_SESSION["skip_payment"] == 1) {

    $products = $_SESSION["products"];

    $name = $_POST["name"];
    $mail = $_POST["mail"];
    $address = $_POST["address"];

    order(-1, $products, $name, $mail, $address);

}
else {
    session_destroy();
    $errcode = "001";
    header("Location: $referer?err=$errcode");
}

function order($id, $products, $name, $mail, $address) {
    $referer = "../";

    $errcode = validate_info($name, $mail, $address);
    if ($errcode == 0) {

        create_order($id, 1, $products, $name, $mail, $address);

        send_mail($mail, $name, $address);
    
    
        setcookie("cart_items", "", time() - 1000, "/");
        session_destroy();
    
        header("Location: $referer?order=success");

    }
    else {
        create_order($id, 0, $products, $name, $mail, $address);

        setcookie("cart_items", "", time() - 1000, "/");
        session_destroy();

        header("Location: $referer?err=$errcode");
    }
}


function create_order($id, $status, $products, $name, $mail, $address) {

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

    $date = time();

    $sql = "INSERT INTO `orders`(`status`, `buyer`, `mail`, `address`, `order_list`, `payment_id`, `date`) VALUES (${status}, '${name}','${mail}','${address}','${products}',${id},${date})";
    $result = $conn->query($sql);

}

function send_mail($mail, $name, $address) {

    $subject = "Thank you for purchasing!";
    $msg = "Thanks you for purchasing in PC Shop!

Dear ${name}, we successfully created a new order. 
Your product will be delivered within 5 days to '{$address}'
Please don't reply to this mail.
Kind regards,
PC Shop";

    $headers = "From:noreply@193.111.199.170";
    // send email
    mail($mail, $subject, $msg, $headers);

}

function validate_info($name, $mail, $address) {
    if ($name == "") {
        return "002";
    }

    if (strpos($mail, "@") === false || $mail == "") {
        return "003";
    }

    if ($address == "") {
        return "004";
    }

    return 0;

}

?>