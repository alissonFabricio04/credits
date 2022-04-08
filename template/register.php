<?php

add_action('woocommerce_register_form_start', 'registerInWeGet');    

function registerInWeGet() {
    $url = "http://localhost:3001/";
    header("Location: ". $url);
}