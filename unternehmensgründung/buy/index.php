<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            PC Shop
        </title>

        <link rel="stylesheet" href="design.css">
        <script src="../jquery/jquery.js"></script>
        <script src="../qrcode/qrcode.js"></script>
        <script src="functions.js"></script>
        <script> 
            setCookie("test_order", "0", 7);
        </script>

        <?php 
            include("../backend/cookie.php");
            include("../backend/get_sum.php");
            #include("../backend/payment.php");
        ?>

        <link rel="icon" href="../images/favicon-16x16.png" sizes="16x16">
    </head>
    <body>
        <img src="../images/logo.png" onclick="home()">
        
        <ul class="list_right">
            <li id="shopping_cart" type="button" onclick="cart()"><img src="../images/shopping_cart.png" alt="shopping cart" width="29" height="24"></li>
            <li id="headbar" type="button" onclick="home()">Home</li>
            <li id="headbar" type="button" onclick="pc_builder()">PC Builder</li>
            <li id="headbar" type="button" onclick="about_us()">About us</li>
        </ul>


        

        <form id="purchase_form" action="../backend/new_order.php" method="post">

            <div id="page1">

                <label class="text_labels" for="name">Name:</label><br>
                <input type="text" id="name" name="name" class="form_items"><br>

                <label class="text_labels" for="address">Address:</label><br>
                <input type="text" id="address" name="address" class="form_items"><br>

                <label class="text_labels" for="mail">Email:</label><br>
                <input type="text" id="mail" name="mail" class="form_items"><br>

                <label class="text_labels" id="price">Amount: $<?php echo get_sum()?></label><br>

                <button id="testorder" onclick="onoff()" value="0" type="button"></button>
                
                <label class="text_labels" id="testorder_text">Enable Test Order</label><br>

                <input type="button" class="buy" value="Continue" onclick="next_page(1)">

            </div>

            <div id="page2">

                <h1 class="label">Pay via Bitcoin Testnet</h1>
                <div id="qrcode"></div>
                <h3 id="info_label" class="label">Address: ... | Amount: ...</h3>
                <h2 id="status_label" class="label" style="white-space: pre-line">Status: Waiting for Transaction</h2>
                <input type="button" value="Refresh" class="buy" id="refresh_button" onclick="refresh_transactions()">

            </div>

        </form>

        

    </body>
</html>