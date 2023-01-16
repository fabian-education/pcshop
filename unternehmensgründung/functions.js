
function cart() {
    window.location.replace('cart');
    //window.location.replace('about_us.html');
}

function home() {
    window.location.replace('../pcshop');
    //var element = document.body;
    //element.classList.toggle("dark-mode");

}

function pc_builder() {
    window.location.replace('pc_builder');
}

function about_us() {
    window.location.replace('about_us');
    //window.location.replace('about_us.html');
}

function gaming() {
    document.getElementById('gaming').style.display = "block";
    document.getElementById('server').style.display = "none";
    document.getElementById('work').style.display = "none";

    window.scrollTo({ left: 0, top: document.body.scrollHeight, behavior: "smooth" });

}

function work() {
    document.getElementById('work').style.display = "block";
    document.getElementById('gaming').style.display = "none";
    document.getElementById('server').style.display = "none";

    window.scrollTo({ left: 0, top: document.body.scrollHeight, behavior: "smooth" });

}

function server() {
    document.getElementById('server').style.display = "block";
    document.getElementById('gaming').style.display = "none";
    document.getElementById('work').style.display = "none";

    window.scrollTo({ left: 0, top: document.body.scrollHeight, behavior: "smooth" });

}

function buy(id) {
    let cart_items = getCookie("cart_items");
    if (cart_items != "") {
        var new_cart_items = cart_items + "," + id;
    }
    else {
        var new_cart_items = id;
    }


    setCookie("cart_items", new_cart_items, 7)

    //console.log(getCookie("cart_items"))

    let button = document.getElementById(id)
    let old_text = button.textContent
    button.textContent = "Added!";

    setTimeout(function () {
        button.textContent = old_text;
    }, 2000)


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

function get_host() {
    var host = window.location.host;
    //console.log(host);

    if (host != "pcshop.fubs.ohaa.xyz") {
        document.getElementById("reminder").style.display = "block"
    }
}


function init() {

    const queryString = window.location.search
    const urlParams = new URLSearchParams(queryString);

    if (urlParams.has('order')) {
        if (urlParams.get('order') == "success") {
            let box = document.getElementById("init_message");
            box.style.backgroundColor = "#f54842";
            box.textContent = "Thank you for purchasing! See Mail for details";
            box.style.color = "#fada4b";
            box.style.display = "block";

        }
    }

    if (urlParams.has('err')) {
        let box = document.getElementById("init_message");
        box.style.backgroundColor = "#990c0c";
        box.textContent = "An Error occured during the process, error code: "+urlParams.get('err');
        box.style.color = "#d1cfcf";
        box.style.display = "block";
    }


}