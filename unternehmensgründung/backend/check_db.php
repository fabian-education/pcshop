
<!DOCTYPE html>
<html>
<body>
<p>Test db connection</p>
<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unternehmensgruendung";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "error";
    #die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
<body>
</html>