<?php

add_action( 'plugins_loaded', 'initialize_gateway_class' );
function initialize_gateway_class() {
    class We_Get_2_You extends WC_Payment_Gateway {
        public function __construct() {
            $this->credits = new Credits();
            $this->id = 'wg2u'; // payment gateway ID
            $this->icon = ''; // payment gateway icon
            $this->has_fields = true; // for custom credit card form
            $this->title = __( 'WeGet2U Gateway', 'text-domain' ); // vertical tab title
            $this->method_title = __( 'WeGet2U Gateway', 'text-domain' ); // payment method name
            $this->method_description = __( 'Custom WeGet2U payment gateway', 'text-domain' ); // payment method description
        
            $this->supports = array( 'default_credit_card_form' );
        
            // load backend options fields
            $this->init_form_fields();
        
            // load the settings.
            $this->init_settings();
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option( 'description' );
            $this->enabled = $this->get_option( 'enabled' );
            $this->test_mode = 'yes' === $this->get_option( 'test_mode' );
            $this->private_key = $this->test_mode ? $this->get_option( 'test_private_key' ) : $this->get_option( 'private_key' );
            $this->publish_key = $this->test_mode ? $this->get_option( 'test_publish_key' ) : $this->get_option( 'publish_key' );
        
            // Action hook to saves the settings
            if(is_admin()) {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_gateway_scripts' ) );         
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'       => __( 'Enable/Disable', 'text-domain' ),
                    'label'       => __( 'Enable WeGet2U Gateway', 'text-domain' ),
                    'type'        => 'checkbox',
                    'description' => __( 'This enable the WeGet2U gateway which allow to accept payment through creadit card.', 'text-domain' ),
                    'default'     => 'no',
                    'desc_tip'    => true
                ),
                'title' => array(
                    'title'       => __( 'Title', 'text-domain'),
                    'type'        => 'text',
                    'description' => __( 'This controls the title which the user sees during checkout.', 'text-domain' ),
                    'default'     => __( 'Credit Card', 'text-domain' ),
                    'desc_tip'    => true,
                ),
                'description' => array(
                    'title'       => __( 'Description', 'text-domain' ),
                    'type'        => 'textarea',
                    'description' => __( 'This controls the description which the user sees during checkout.', 'text-domain' ),
                    'default'     => __( '', 'text-domain' ),
                ),
                'test_mode' => array(
                    'title'       => __( 'Test mode', 'text-domain' ),
                    'label'       => __( 'Enable Test Mode', 'text-domain' ),
                    'type'        => 'checkbox',
                    'description' => __( 'Place the payment gateway in test mode using test API keys.', 'text-domain' ),
                    'default'     => 'yes',
                    'desc_tip'    => true,
                ),
                'test_publish_key' => array(
                    'title'       => __( 'Test Publish Key', 'text-domain' ),
                    'type'        => 'text'
                ),
                'test_private_key' => array(
                    'title'       => __( 'Test Private Key', 'text-domain' ),
                    'type'        => 'password',
                ),
                'publish_key' => array(
                    'title'       => __( 'Live Publish Key', 'text-domain' ),
                    'type'        => 'text'
                ),
                'private_key' => array(
                    'title'       => __( 'Live Private Key', 'text-domain' ),
                    'type'        => 'password'
                )
            );
        }

        public function payment_fields() {
            ?>
                <fieldset id='wc-<?php echo esc_attr( $this->id ); ?>-cc-form' class='wc-credit-card-form wc-payment-form' style='background:transparent;'>
        
                    <?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>

                    <div class='form-row form-row-wide'>
                        <label>Seus Creditos na Plataforma WeGet2U:</label>
                        <label>R$ 
                            <?php
                                if ($this->credits->get_total()) {
                                    echo esc_html($this->credits->get_total());
                                } else {
                                    echo 0;
                                }
                            ?>
                        </label>
                    </div>
                    <div class='clear'></div>

                    <?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>

                    <div class='clear'></div>

                </fieldset>
            <?php
        }

        public function process_payment( $order_id ) {

            global $woocommerce;
        
            // get order detailes
            $order = wc_get_order( $order_id );

            $total = WC()->cart->cart_contents_total;

            if ($total > $this->credits->get_total()) {
                wc_add_notice(  'insufficient credits', 'error' );
                return;
            } else if ($total <= $this->credits->get_total()) {
                wc_add_notice(  'compra concluida', 'success' );
                return;
            } else {
                wc_add_notice(  'Erro inesperado', 'error' );
                return;
            }
        
            // Array with arguments for API interaction
            $body = wp_json_encode( $body = ['total' => $total]);
            
            $response = wp_remote_post( 'http://localhost:3333/list', $body );
        }
    }
}

add_filter( 'woocommerce_payment_gateways', 'add_custom_gateway_class' );
function add_custom_gateway_class( $gateways ) {
    $gateways[] = 'We_Get_2_You'; // payment gateway class name
    return $gateways;
}