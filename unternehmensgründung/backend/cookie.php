<?php

function get_cookie($cookie_name) {
    if(!isset($_COOKIE[$cookie_name])) {
        return "";
    } else {
        return $_COOKIE[$cookie_name];
    }
}




?>