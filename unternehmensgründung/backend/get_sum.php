<?php


function get_sum() {
    $cart_items = get_cookie("cart_items");
    //echo $cart_items;
    if ($cart_items != "") {

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

        $cart_items_array = explode(",",$cart_items);

        $price = 0;

        foreach ($cart_items_array as $element) {
            if (!is_numeric($element)) {
                $components = explode("?",$element);

                //var_dump($components);

                $cpu = $components[0];
                $cpu_price = get_component_price($cpu, $conn);
                
                $gpu = $components[1];
                $gpu_price = get_component_price($gpu, $conn);
                
                $mem = $components[2];
                $mem_price = get_component_price($mem, $conn);
                
                $storage = $components[3];
                $storage_price = get_component_price($storage, $conn);
                
                $product_price = $cpu_price + $gpu_price + $mem_price + $storage_price;
                $price += $product_price;
            }
            else {
    
                $product_price=get_product_price($element, $conn);
                $price += $product_price;
            }
    
        }

        $_SESSION["price"] = strval($price);
        $_SESSION["md5"] = md5($cart_items);
        #echo $_SESSION["md5"];
        return $price;
    }
    else {
        header("Location: /");
    }

}

function get_product_price($id, $conn) {
    $sql = "SELECT price FROM products WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            return $row["price"];
        }
    } else {
        die("Database error");
    }
}

function get_component_price($id, $conn) {
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