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

function update_table(row, name, price, element) {
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);

    cell1.classList.add("cart_items")
    cell2.classList.add("cart_items")
    cell3.classList.add("cart_items")
    cell3.classList.add("remove_item")
    
    //cell3.style.color = "red";
    var image = document.createElement("img")
    image.src = '../images/red_x.png';
    image.classList.add("remove_image")

    cell3.appendChild(image)
    cell3.addEventListener("click", function() {remove_item(element)}, false);

    cell1.innerHTML = name;
    cell2.innerHTML = price;
    //cell3.innerHTML = "X";

}

function prepare_table_row(row, table) {
    var row = table.insertRow(row);
    return row;
}

function show_cart_items() {
    var table = document.getElementById("cart_table");

    var cart_items = getCookie("cart_items")

    if (cart_items != "") {


        cart_items = cart_items.split(",")

        cart_items.forEach(function (element, i) {
            //console.log(element)

            let row = prepare_table_row(i + 1, table);

            if (isNaN(element)) {

                let components = element.split("?");
                //console.log(components);


                $.ajax({
                    method: "POST",
                    url: "../backend/get_custom_price.php",
                    data: { "cpu": components[0], "gpu": components[1], "mem": components[2], "storage": components[3] }
                })
                    .done(function (response) {
                        //console.log(response)
                        if (!isNaN(response)) {
                            update_table(row, "Custom PC", "$" + response, element)
                        }
                        else {
                            console.log(response)
                        }

                    })
                    .catch(function (err) {
                        console.log(err)
                    })


            }
            else {

                $.ajax({
                    method: "POST",
                    url: "../backend/get_product.php",
                    data: { "product_id": element }
                })
                    .done(function (response) {
                        //console.log(response)
                        let product = JSON.parse(response)
                        update_table(row, product["name"], "$" + product["price"], element)



                    })
                    .catch(function (err) {
                        console.log(err)
                    })


            }


        });


    }
    else {
        let row = table.insertRow(1);
        let cell1 = row.insertCell(0);
        let cell2 = row.insertCell(1);

        cell1.classList.add("cart_items")
        cell2.classList.add("cart_items")

        cell1.innerHTML = "No Products in Cart";
        document.getElementById("buy").style.display = "none";

    }


    //cell2.classlist.add("cart_items")
}


function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
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

function buy() {
    window.location.replace('../buy');
}

function remove_item(element) {

    var cart_items = getCookie("cart_items")

    cart_items = cart_items.split(",")

    let new_cart_items = [];

    let found = false;

    cart_items.forEach(function (item, i) {
        if (element != item) {
            new_cart_items.push(item);

        }
        else {

            if (found == false) {
                found = true;
            }
            else {
                new_cart_items.push(item);
            }
            
        }


    })

    setCookie("cart_items", new_cart_items, 7);
    location.reload()
}