<?php

class Credits {
    public $creditsKey = 'ensure_user_credits';
    public $user = null;
    public $activeUser = false;
    public $activeUserCredits = 0;
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

        $this->activeUserCredits = 0;
    }

    public function get_total() {
        return $this->activeUserCredits;
    }

    public function update_total($newValue, $infoUser) {
        if (!$this->activeUser) {
            return false;
        }

        if ($infoUser->email == $this->user->billing_email) {
            $infoUserWP = get_user_meta($infoUser->id);

            $this->activeUserCredits = $newValue;

            $status = update_user_meta($infoUserWP->ID, $this->creditsKey, $this->activeUserCredits);
            return $status;
        }
    }
}