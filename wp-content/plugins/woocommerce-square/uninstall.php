<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'wc_square_endpoint_set' );
delete_option( 'woocommerce_squareconnect_settings' );
delete_transient( 'wc_square_locations' );
delete_option( 'woocommerce_square_merchant_access_token' );
