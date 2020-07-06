<?php

/**
 * Retrive the bank slip url for a order id.
 * @return string bank slip url
 * @since 1.0.0
 *
 * @param int $order_id Order id that want to retrive it bank slip url
 */
function get_bradesco_boleto_url($order_id) {
	global $wpdb;
	$results = $wpdb->get_results('SELECT * FROM  ' . $wpdb->prefix . 'wc_bradesco_boleto WHERE order_id ='.$order_id);
	if (sizeof($results) > 0) {
		return $results[sizeof($results) - 1]->bank_slip;
	}
	return false;
}

/**
 * Set the bank slip url for a order id.
 * @since 1.0.0
 *
 * @param int $order_id Order id that want to set it bank slip url
 * @param string $bank_slip_url bank slip url
 */
function set_bradesco_boleto_url($order_id, $bank_slip_url) {
	global $wpdb;
	$wpdb->insert(
		$wpdb->prefix . 'wc_bradesco_boleto',
		array(
			'order_id'     => intval($order_id),
			'bank_slip'    => $bank_slip_url,
		)
	);
}

/**
 * Adiciona uma rota para confirmar a emissão de boleto usada pela api de geração de boleto
 * do bradesco
 */
add_action( 'rest_api_init', function ( $server ) {
    $server->register_route( 'wc_4nipps_bradesco_boleto', '/boleto-bradesco-check', array(
        'methods'  => 'GET',
        'callback' => function ($data) {
			$order_id = $data->get_param('numero_pedido');
			if ($order_id) {
				$order = wc_get_order(intval($order_id));
				if ($order->data['payment_method'] == 'wc_4nipps_bradesco_boleto') {
					return array('message' => 'success');
				}
			}
			return new WP_Error( 'payment_method_is_not_bank_slip', __('The payment method is not bank slip', 'wc-4nipps-bradesco-boleto'), array( 'status' => 404 ) );
        },
    ));
});

add_action( 'woocommerce_thankyou', function($order_id) {
    $order = wc_get_order(intval($order_id));
	if ($order->get_payment_method() == 'wc_4nipps_bradesco_boleto') {
		$url = get_bradesco_boleto_url($order_id);
		if ($url)
            echo '
                <style>
                    .a-as-button {
                        font: bold 16px Arial;
                        text-decoration: none;
                        background-color: #EEEEEE;
                        color: #333333;
                        border-top: 1px solid #CCCCCC;
                        border-right: 1px solid #333333;
                        border-bottom: 1px solid #333333;
                        border-left: 1px solid #CCCCCC;
                    }
                </style>
                <div class="row pl-3">
                    <a class="bank-slip-btn a-as-button py-3 px-4 mb-5" href="' . $url . '" target="_blank"><i class="fa fa-barcode mr-2" aria-hidden="true"></i>baixar boleto</a>
                </div>
            ';
	}
});