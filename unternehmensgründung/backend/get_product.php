<?php

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
//echo "Connected successfully";

$product_id = $_POST['product_id'];
$product = get_product($product_id, $conn);

$json = json_encode($product);

echo $json;


function get_product($id, $conn) {
    $sql = "SELECT name, price FROM products WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            //return $row["price"];
            return $row;
        }
    } else {
        die("Database error");
    }
}

?>