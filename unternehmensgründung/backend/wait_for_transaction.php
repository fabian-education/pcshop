<?php

session_start();

$address = $_SESSION["address"];
#$address = "mkHS9ne12qx9pS9VojpwU5xtRd4T7X7ZUt";
#$address = "myM9nFcAEj9nKdpK2PLus5XfSGwuc4afWQ";
$amount = $_SESSION["btc_amount"];
$id = $_SESSION["payment_id"];



$url = "https://mempool.space/testnet/api/address/${address}/txs";

$json = file_get_contents($url);
$array = json_decode($json, TRUE);

if (empty($array)) {
    $response = array("status"=>1, "progress"=>1);
    echo json_encode($response);
}
else {
    $transaction = $array[0]["txid"];
    if ($array[0]["vout"][0]["scriptpubkey_address"] == $address) {
        $transaction_amount = $array[0]["vout"][0]["value"];
    }
    else if ($array[0]["vout"][1]["scriptpubkey_address"] == $address) {
        $transaction_amount = $array[0]["vout"][1]["value"];
    }
    else {
        $response = array("status"=>1, "progress"=>1);
        echo json_encode($response);
    }

    if ($amount*100000000 <= $transaction_amount) {
        $response = array("status"=>0, "tx"=>$transaction, "progress"=>2);
        update_payment($id, $transaction);
        $_SESSION["tx"] = $transaction;
        echo json_encode($response);
    }
    else {
        $response = array("status"=>1, "progress"=>2, "tx_amount"=>$transaction_amount, "tx"=>$transaction);
        echo json_encode($response);
    }

}


function update_payment($id, $tx) {

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

    $sql = "UPDATE `payments` SET `status`=1, `tx_hash`='${tx}' WHERE `id`=${id}";
    $result = $conn->query($sql);

}


?>