<?php
/**
 * Settings for WC 4nipps Bradesco Boleto.
 *
 */

defined( 'ABSPATH' ) || exit;

return apply_filters( 'wc_4nipps_bradesco_boleto_form_fields', array(

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
        'type'        => 'select',
        'description' => __( 'Wallet code.', 'wc-4nipps-bradesco-boleto' ),
        'default'     => '25',
        'desc_tip'    => true,
        'options'     => array(
			'25' => '25',
			'26' => '26',
		),
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
));