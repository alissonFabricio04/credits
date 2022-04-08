<?php

add_action('woocommerce_login_form_start', 'loginInWeGet');    

function loginInWeGet() {
    $url = "http://localhost:3001/";
    header("Location: ". $url);
}