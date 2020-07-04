<?php
/**
 * Plugin Name: WC 4nipps Bradesco Boleto
 * Plugin URI: https://github.com/marcosvolpato/wc-4nipps-bradesco-boleto
 * Description: A woocommerce payment gateway using Bradesco bank slip.
 * Author: 4nipps
 * Author URI: http://4nipps.com.br/
 * Version: 1.0.0
 * Text Domain: wc-4nipps-bradesco-boleto
 * Domain Path: /languages/
 *
 * Copyright: (c) 2020 4nipps and WooCommerce
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-4nipps-Bradesco-Boleto
 * @author    4nipps
 * @category  Admin
 * @copyright Copyright: (c) 2020 4nipps and WooCommerce
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

// Make sure WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}

if ( ! class_exists( 'WC_4nipps_Bradesco_Boleto' ) ) :
	/**
	 * Add the gateway to WC Available Gateways
	 *
	 * @since 1.0.0
	 * @param array $gateways all available WC gateways
	 * @return array $gateways all WC gateways + offline gateway
	 */
	function wc_4nipps_bradesco_boleto_add_to_gateways( $gateways ) {
		$gateways[] = 'WC_4nipps_Bradesco_Boleto';
		return $gateways;
	}
	add_filter( 'woocommerce_payment_gateways', 'wc_4nipps_bradesco_boleto_add_to_gateways' );



	/**
	 * Adds plugin page links
	 *
	 * @since 1.0.0
	 * @param array $links all plugin links
	 * @return array $links all plugin links + our custom links (i.e., "Settings")
	 */
	function wc_4nipps_bradesco_boleto_gateway_plugin_links( $links ) {

		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=WC_4nipps_Bradesco_Boleto' ) . '">' . __( 'Configure', 'wc-4nipps-bradesco-boleto' ) . '</a>'
		);

		return array_merge( $plugin_links, $links );
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_4nipps_bradesco_boleto_gateway_plugin_links' );

	add_action( 'plugins_loaded', 'wc_4nipps_bradesco_boleto_init', 11 );
	function wc_4nipps_bradesco_boleto_init() {
		require_once 'includes/wc_4nipps_bradesco_boleto_gateway.php';
	}
endif;

require_once 'plugin-init.php';
require_once 'includes/functions.php';


