<?php

class Credits {
    public $creditsKey = 'ensure_user_credits';
    public $user = null;
    public $activeUser = false;
    public $activeUserCredits = 100;
    protected static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        //$this->set_user_data();
        do_action('user_credits_loaded');
    }
    
    public function set_user_data($infoUser) {
        $this->$user = $infoUser;

        $this->activeUser = true;

        $this->activeUserCredits = get_user_meta($this->user->id, $this->creditsKey, true);

        return $this->activeUserCredits;
    }

    public function get_total() {
        return $this->activeUserCredits;
    }

    public function create_user($infoUser) {
        $status = update_user_meta($infoUser->id, 'nickname', $infoUser->nickname);
        return $status;
    }

    public function update_total($newValue, $infoUser) {
        if (!$this->activeUser) {
            return false;
        }

        $infoUserWP = get_user_meta($infoUser->id);

        $this->activeUserCredits = $newValue;

        $status = update_user_meta($infoUserWP->ID, $this->creditsKey, $this->activeUserCredits);
        return $status;
    }
}