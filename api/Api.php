<?php 

require_once PATH . '/class/credits.php';

class Api {
    function __construct() {
        add_action('rest_api_init', array( $this, 'define_create_user_route'));
        add_action('rest_api_init', array( $this, 'define_update_user_route'));
        add_action('rest_api_init', array( $this, 'define_list_user_route'));
    }

    public function define_create_user_route() {
        register_rest_route('api', '/create/user', array(
            'methods' => 'POST',
            'callback' => array( $this, 'create_user_route'),
        ));
    }

    public function create_user_route($request) {
        $credits = new Credits();
        $infoUser = $request[0];
        $credits->set_user_data($infoUser);
        $credits->create_user($infoUser);
        $status = $credits->update_total($infoUser->creditsInWallet, $infoUser);
        return rest_ensure_response($status);
    }

    public function define_update_user_route() {
        register_rest_route('api', '/update/user', array(
            'methods' => 'PUT',
            'callback' => array( $this, 'update_user_route'),
        ));
    }

    public function update_user_route($request) {
        $credits = new Credits();
        $infoUser = $request[0];
        $credits->set_user_data($infoUser);
        $status = $credits->update_total($infoUser->creditsInWallet, $infoUser);
        return rest_ensure_response($status);
    }


    public function define_list_user_route() {
        register_rest_route('api', '/list/user', array(
            'methods' => 'GET',
            'callback' => array( $this, 'list_user_route'),
        ));
    }

    public function list_user_route($request) {
        $infoUser = get_user_meta(3);
        return rest_ensure_response($infoUser);
    }
}