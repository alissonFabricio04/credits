<!-- Não esta sendo usando -->

<fieldset id='wc-<?php echo esc_attr( $this->id ); ?>-cc-form' class='wc-credit-card-form wc-payment-form' style='background:transparent;'>
        
    <?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>

    <div class='form-row form-row-wide'>
        <label>Seus Creditos na Plataforma WeGet2U:</label>
        <label>R$ <?php echo esc_attr(wp_get_current_user()->ID) ?></label>
    </div>
    <div class='clear'></div>

    <?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>

    <div class='clear'></div>

</fieldset>