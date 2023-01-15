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

$cpu = $_POST['cpu'];
$cpu_price = get_price($cpu, $conn);

$gpu = $_POST['gpu'];
$gpu_price = get_price($gpu, $conn);

$mem = $_POST['mem'];
$mem_price = get_price($mem, $conn);

$storage = $_POST['storage'];
$storage_price = get_price($storage, $conn);

$price = $cpu_price + $gpu_price + $mem_price + $storage_price;
echo $price;

function get_price($id, $conn) {
    $sql = "SELECT price FROM components WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            return $row["price"];
        }
    } else {
        die("Database error");
    }
}

?>