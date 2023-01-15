
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

function GetElementInsideContainer(containerID, childID) {
    var elm = document.getElementById(childID);
    var parent = elm ? elm.parentNode : {};
    return (parent.id && parent.id === containerID) ? elm : {};
}

var cpu = 1
var gpu = 5
var mem = 9
var storage = 13

function run(value, id, buy) {
    //console.log(value);

    if (id == "cpu") {
        cpu = value;
    }
    else if (id == "gpu") {
        gpu = value;
    }
    else if (id == "mem") {
        mem = value;
    }
    else if (id == "storage") {
        storage = value;
    }

    let price_text = document.getElementById("custom_price");

    $.ajax({
        method: "POST",
        url: "../backend/get_custom_price.php",
        data: { "cpu": cpu, "gpu": gpu, "mem": mem, "storage": storage }
    })
        .done(function (response) {
            //console.log(response)
            if (!isNaN(response)) {
                price_text.textContent = "Price: $" + response;
            }
            else {
                price_text.textContent = "Price: error";
            }

            if (buy == 1) {

                let new_item = cpu + "?" + gpu + "?" + mem + "?" + storage;

                let cart_items = getCookie("cart_items");
                if (cart_items != "") {
                    var new_cart_items = cart_items + "," + new_item;
                }
                else {
                    var new_cart_items = new_item;
                }

                setCookie("cart_items", new_cart_items, 7)
                //console.log(getCookie("cart_items"))
            
                let button = document.getElementById("custom_price");
                let current_text = button.textContent;
                button.textContent = "Added!";
                setTimeout(function() {
                    button.textContent = current_text;
                }, 3000)

            }

        })
        .catch(function (err) {
            console.log(err)
            price_text.textContent = "Price: error";
        })


}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
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