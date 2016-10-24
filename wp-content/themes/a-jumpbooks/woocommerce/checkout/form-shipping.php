<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="woocommerce-shipping-fields">

	<h3><?php _e( 'Shipping', 'woocommerce' ); ?></h3>

	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

		<h5 id="ship-to-different-address">
			<label for="ship-to-different-address-checkbox" class="checkbox"><?php _e( 'Ship to a different address?', 'woocommerce' ); ?></label>
			<input id="ship-to-different-address-checkbox" class="input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
		</h5>

		<div class="shipping_address">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<?php 
			// order the keys for your custom ordering or delete the ones you don't need
			$myshippingfields = array(
			    "shipping_first_name",
			    "shipping_last_name",
			    "shipping_address_1",
			    "shipping_address_2",
			    "shipping_city",
			    "shipping_state",
			    "shipping_postcode",
			    "shipping_country",
			);
			foreach ($myshippingfields as $key) : ?>
			<?php woocommerce_form_field( $key, $checkout->checkout_fields['shipping'][$key], $checkout->get_value( $key ) ); ?>
			<?php endforeach; ?>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>

<a class="button-a shipping-back checkout-back">Back to Billing</a>
<a class="button-a shipping-continue checkout-continue">Continue to Payment</a>