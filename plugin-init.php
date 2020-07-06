<?php

/**
 * Used in plugin installation
 * @since 1.0.0
 */
function wc_boleto_bradesco_install() {
	global $wpdb;

	// add the nedded options
	add_option( 'bradesco_boleto_url_dev', 'https://homolog.meiosdepagamentobradesco.com.br/apiboleto/transacao' );
	add_option( 'bradesco_boleto_url_prod', 'https://meiosdepagamentobradesco.com.br/apiboleto/transacao' );

	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'wc_bradesco_boleto';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		order_id int NOT NULL,
		bank_slip varchar(200) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_activation_hook( __FILE__, 'wc_boleto_bradesco_install' );