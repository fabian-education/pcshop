function cart() {
    window.location.replace('../cart');
}

function home() {
    window.location.replace('../');
}

function pc_builder() {
    window.location.replace('../pc_builder');
}

function about_us() {
    window.location.replace('../about_us');
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function buy() {
    window.location.replace('../buy');
}

function onoff() {
    let button = document.getElementById("testorder");
    let text = document.getElementById("testorder_text");

    if (button.value == 0) {
        button.value = 1;
        setCookie("test_order", "1", 7);
        //console.log(getCookie("test_order"));
        button.style.backgroundColor = "white";
        text.textContent = "Disable Test Order";
        
    }
    else if (button.value == 1) {
        button.value = 0;
        setCookie("test_order", "0", 7);
        //console.log(getCookie("test_order"));
        button.style.backgroundColor = "black";
        text.textContent = "Enable Test Order";
        
    }
    
}

function next_page(page) {

    if (page == 1) {
        if (getCookie("test_order") != "1") {
            let page1 = document.getElementById("page1");
            page1.style.display = "none";
    
            let page2 = document.getElementById("page2");
            page2.style.display = "inline";
            
        }
        payment_page();

    }

}

var timer = 0;
var interval
var lock_refresh = false

function payment_page() {
    $.ajax({
        method: "POST",
        url: "../backend/payment.php",
        data: { "skip_payment": getCookie("test_order"), "products": getCookie("cart_items")}
    })
        .done(function(response) {
            //console.log(response);
            
            let json_object = JSON.parse(response);
            //console.log(json_object["status"]);
            if (json_object["status"] == 0) {

                if (json_object["progress"] == 0) {

                    let address = json_object["address"];
                    let price = json_object["amount"];

                    new QRCode(document.getElementById("qrcode"), "bitcoin:"+address+"?amount="+price);

                    let info_label = document.getElementById("info_label");
                    info_label.textContent = "Address: "+address+" | Amount: "+price;


                    interval = setInterval(function() {
                        refresh_transactions()
                    }, 15000)

                    


                }
                else if (json_object["progress"] == 2) {

                    document.getElementById("purchase_form").submit();



                }


                //console.log("Payment Sucessfull");
            }
            else {
                //console.log("Error code: "+toString(json_object["status"]))
                location.reload();
            }

        })
        .catch(function (err) {
            console.log(err)
            //location.reload();
        })

}

function wait_for_transaction() {

    return new Promise(function(resolve, reject) {
        $.ajax({
            method: "GET",
            url: "../backend/wait_for_transaction.php",
            success: function(data) {
                resolve(data)
            },
            error: function(err) {
                reject(err)
            }
        })


    });
}


function refresh_transactions() {

    if (lock_refresh == false) {
        lock_refresh = true;
        //console.log("new check ...");
        wait_for_transaction().then(function(data) {
            let json_object = JSON.parse(data);
            //console.log(json_object)
            //console.log(json_object["status"]);
            if (json_object["status"] == 0) {
    
                if (json_object["progress"] == 2) {
    
                    //console.log(json_object);
                    clearInterval(interval);
                    let transaction_tx = json_object["tx"];
                    let status_label = document.getElementById("status_label");
    
                    status_label.textContent = "Status: Found Transaction with correct Amount\nTX: "+transaction_tx;
    
                    document.getElementById("refresh_button").style.display = "none";
    
                    let link = "https://live.blockcypher.com/btc-testnet/tx/"+transaction_tx;
                    
                    status_label.style.cursor = "pointer";
    
                    status_label.onclick = function () {
                        window.open(link, '_blank').focus();
                    }
    
                    setTimeout(function() {
                        document.getElementById("purchase_form").submit();
                        lock_refresh = false;
                    }, 5000)
                    
                }
                else {
                    lock_refresh = false;
                }
            }
            else {
    
                if (json_object["progress"] == 2) {
                    let transaction_tx = json_object["tx"];
                    let status_label = document.getElementById("status_label");
    
                    status_label.textContent = "Status: Transaction has wrong Amount\nTX: "+transaction_tx;
                    let link = "https://live.blockcypher.com/btc-testnet/tx/"+transaction_tx;
                    
                    status_label.style.cursor = "pointer";
    
                    status_label.onclick = function () {
                        window.open(link, '_blank').focus();
                    }
                    
                }
    
    
                if (timer >= 900) {
                    //clearInterval(interval);
                    document.reload()
                    
                }
                else {
                    timer += 15;
                }
                lock_refresh = false;
            }
    
        }).catch(function(err) {
            console.log(err);
            //document.reload()
        })
    }
    else {
        console.log("refresh denied");
    }


}