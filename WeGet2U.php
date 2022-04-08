<?php

/*
Plugin Name: dajsdasdaskdhdsahdhsdhjadajsddsahkdkjdsadhsdjd
Version: 1.0.0
Description: dajsdasdaskdhdsahdhsdhjadajsddsahkdkjdsadhsdjd
Author: dajsdasdaskdhdsahdhsdhjadajsddsahkdkjdsadhsdjd
*/

function define_constants() {
    define('PATH', plugin_dir_path(__FILE__));
    define('URL', plugin_dir_url(__FILE__));
}


if (function_exists('define_constants')) {
    define_constants();

    require_once PATH . '/api/Api.php';
    $Api = new Api();

    require_once PATH . '/class/credits.php';
    $credits = new Credits();

    require_once PATH . '/class/payments.php';
    add_action( 'plugins_loaded', 'initialize_gateway_class' );
}