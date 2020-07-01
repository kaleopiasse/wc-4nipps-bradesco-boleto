<?php

class WC_4nipps_Bradesco_Boleto extends WC_Payment_Gateway {

    /**
     * Constructor for the gateway.
     */
    public function __construct() {

        $this->id                 = 'wc_4nipps_bradesco_boleto';
        $this->icon               = apply_filters('wc_4nipps_bradesco_boleto_icon', '');
        $this->has_fields         = false;
        $this->method_title       = __( 'Boleto Bradesco', 'wc-4nipps-bradesco-boleto' );
        $this->method_description = __( 'Allows Bradesco bank slip payments. Orders are marked as "on-hold" when received.', 'wc-4nipps-bradesco-boleto' );

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->title        = $this->get_option( 'title' );
        $this->description  = $this->get_option( 'description' );
        $this->instructions = $this->get_option( 'instructions', $this->description );

        // Actions
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

        // Customer Emails
        add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
    }


    /**
     * Initialize Gateway Settings Form Fields
     */
    public function init_form_fields() {

        $this->form_fields = apply_filters( 'wc_4nipps_bradesco_boleto_form_fields', array(

            'enabled' => array(
                'title'   => __( 'Enable/Disable', 'wc-4nipps-bradesco-boleto' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable Bradesco Boleto', 'wc-4nipps-bradesco-boleto' ),
                'default' => 'yes'
            ),

            'title' => array(
                'title'       => __( 'Title', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'text',
                'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => __( 'Bradesco Boleto', 'wc-4nipps-bradesco-boleto' ),
                'desc_tip'    => true,
            ),

            'description' => array(
                'title'       => __( 'Description', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'textarea',
                'description' => __( 'Payment method description that the customer will see on your checkout.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => __( 'Pay with Bradesco bank slip', 'wc-4nipps-bradesco-boleto' ),
                'desc_tip'    => true,
            ),

            'instructions' => array(
                'title'       => __( 'Instructions', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'textarea',
                'description' => __( 'Instructions that will be added to the thank you page and emails.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
        ) );
    }


    /**
     * Output for the order received page.
     */
    public function thankyou_page() {
        if ( $this->instructions ) {
            echo wpautop( wptexturize( $this->instructions ) );
        }
    }


    /**
     * Add content to the WC emails.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

        if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
            echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
        }
    }


    /**
     * Process the payment and return the result
     *
     * @param int $order_id
     * @return array
     */
    public function process_payment( $order_id ) {

        $order = wc_get_order( $order_id );

        // Mark as on-hold (we're awaiting the payment)
        $order->update_status( 'on-hold', __( 'Awaiting offline payment', 'wc-gateway-offline' ) );

        $this->generateTicket();

        // Reduce stock levels
        $order->reduce_order_stock();

        // Remove cart
        WC()->cart->empty_cart();

        // Return thankyou redirect
        return array(
            'result' 	=> 'success',
            'redirect'	=> $this->get_return_url( $order )
        );
    }

    public function generateTicket() {
        echo '<h1>Chamar boleto aqui</h1>';
    }
}