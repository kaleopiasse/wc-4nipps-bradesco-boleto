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

            'development' => array(
                'title'   => __( 'Development mode', 'wc-4nipps-bradesco-boleto' ),
                'type'    => 'checkbox',
                'label'   => __( 'Development mode uses homolog endpoints', 'wc-4nipps-bradesco-boleto' ),
                'default' => 'no'
            ),

            'beneficiary' => array(
                'title' => __( 'Beneficiary', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'text',
                'description' => __( 'The beneficiary complete name.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => '',
                'desc_tip'    => true,
            ),

            'wallet' => array(
                'title' => __( 'Wallet', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'text',
                'description' => __( 'Wallet code.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => '',
                'desc_tip'    => true,
            ),

            'header_message' => array(
                'title' => __( 'Header message', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'text',
                'description' => __( 'The message that will be displayed at the top of the bank slip.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => '',
                'desc_tip'    => true,
            ),

            'logo_url' => array(
                'title' => __( 'Logo url', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'text',
                'description' => __( 'Logo url that will be displayed in bank slip.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => '',
                'desc_tip'    => true,
            ),

            'days_to_expire' => array(
                'title' => __( 'Days to expire', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'text',
                'description' => __( 'Days that the bank slip will be valid.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => '',
                'desc_tip'    => true,
            ),

            'merchant_id' => array(
                'title' => __( 'Merchant id', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'text',
                'description' => __( 'Identifier property provided by Bradesco.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => '',
                'desc_tip'    => true,
            ),

            'authorization_key' => array(
                'title' => __( 'Authorization key', 'wc-4nipps-bradesco-boleto' ),
                'type'        => 'text',
                'description' => __( 'The access credentials.', 'wc-4nipps-bradesco-boleto' ),
                'default'     => '',
                'desc_tip'    => true,
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
        $order->update_status( 'on-hold', __( 'Awaiting banking slip payment', 'wc-4nipps-bradesco-boleto' ) );

        // If bank slip generation fails set order to failed, raise a error and return
        $bank_slip_generated = $this->generate_bank_slip($order);
        if (!$bank_slip_generated) {
            $order->update_status( 'on-hold', __( 'Failed to generate bank slip', 'wc-4nipps-bradesco-boleto' ) );
            wc_add_notice( __('Payment error:', 'woothemes') . ' ' . __( 'Failed to generate bank slip.', 'wc-4nipps-bradesco-boleto' ), 'error' );
            return;
        }

        // Reduce stock levels
        $order->reduce_order_stock();

        // Remove cart
        WC()->cart->empty_cart();

        // Return thankyou redirect
        return array(
            'result' 	=> 'success',
            'redirect'	=> $this->get_return_url( $order ),
        );
    }

    // define the woocommerce_thankyou callback
    public function generate_bank_slip( $order ) {

        // Sets the endpoint url, prod or dev if is setted
        $url = get_option( 'bradesco_boleto_url_prod' );
        if ($this->get_option('development') == 'yes') {
            $url = get_option( 'bradesco_boleto_url_dev' );
        }

        $bank_slip_itens_desc = '';
        foreach ( $order->get_items() as $item_id => $item ) {
            $bank_slip_itens_desc .= $item->get_quantity();
            $bank_slip_itens_desc .= ' ';
            $bank_slip_itens_desc .= $item->get_name();
            $bank_slip_itens_desc .= '\n';
        }

        $expire_date = new DateTime();
        date_add($expire_date, date_interval_create_from_date_string($this->get_option('days_to_expire') . ' days'));

        $new_data = array(
            'merchant_id' => $this->get_option('merchant_id'),
            'meio_pagamento' => '300',
            'pedido' => array(
                'numero' => strval($order->get_id()),
                'valor' => intval(preg_replace( '/[^0-9]/', '', strval($order->get_total()))),
                "descricao" => "bank_slip_itens_desc",
            ),
            'comprador' => array(
                'nome' => $order->get_formatted_billing_full_name(),
                'documento' => preg_replace( '/[^0-9]/', '', $order->get_meta( '_billing_cpf' )),
                'endereco' => array(
                    'cep' => preg_replace( '/[^0-9]/', '', $order->get_billing_postcode()),
                    'logradouro' => $order->get_billing_address_1(),
                    'numero' => $order->get_meta( '_billing_number' ),
                    'bairro' => $order->get_meta( '_billing_neighborhood' ),
                    // 'complemento' => 'vetvfgef', // tentar tirar
                    'cidade' => $order->get_billing_city(),
                    'uf' => $order->get_billing_state(),
                ),
            ),
            'boleto' => array(
                'beneficiario' => $this->get_option('beneficiary'),
                'carteira' => $this->get_option('wallet'), // numero
                'nosso_numero' => substr((new DateTime())->format('ymdHisu'), 0, 11),
                'data_emissao' => (new DateTime())->format('Y\-m\-d'),
                'data_vencimento' => $expire_date->format('Y\-m\-d'), // numero
                'valor_titulo' => intval(preg_replace( '/[^0-9]/', '', strval($order->get_total()))),
                'url_logotipo' => $this->get_option('logo_url'),
                'mensagem_cabecalho' => $this->get_option('header_message'),
                'tipo_renderizacao' => '2',
                // 'instrucoes' => new ArrayObject(), // tentar tirar
                // 'registro' => new ArrayObject(), // tentar tirar
            ),
            // 'token_request_confirmacao_pagamento' => 'vtrshbtuyjvvsryhnbt', // parece interessante completar a ordem quando receber  // tentar tirar
        );

        $payload = json_encode($new_data);

        //open connection
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $encoded_auth_key = base64_encode($this->get_option('merchant_id') . ':' . $this->get_option('authorization_key'));

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Accept:application/json',
            'Authorization: Basic ' . $encoded_auth_key
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result_decoded = json_decode($result);
        set_bradesco_boleto_url($order->get_id(), $result_decoded->boleto->url_acesso);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }

        // close connection
        curl_close($ch);

        // rise a error
        if (isset($error_msg) || intval(curl_getinfo($ch)['http_code']) != 201 || intval($result_decoded->status->codigo) < 0) {
            return;
        }

        return $result_decoded;
    }
}