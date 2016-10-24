<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// wc_print_notices();

// do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set">
			<div class="checkout-steps-cont row">
				<div class="checkout-steps">
					<a class="checkout-step checkout-step-active checkout-step-billing"><h3>1. Billing</h3></a>
					<a class="checkout-step checkout-step-shipping"><h3>2. Shipping</h3></a>
					<a class="checkout-step checkout-step-payment"><h3>3. Payment</h3></a>
				</div>
				<div class="columns large-8 checkout-forms">
					<div class="col-1 billing">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>
					<hr class="checkout-hr ch-hr-1">
					<div class="col-2 shipping hide">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
					<hr class="checkout-hr ch-hr-2 hide">
				</div>
				<div class="columns large-4 cart-review-cont">
					<div class="cart-items">
						<?php
					    global $woocommerce;
					    $items = $woocommerce->cart->get_cart();
					        foreach($items as $item => $values) { 
					            echo '<div class="checkout-item">';
					            $_product = $values['data']->post;
					            $getProductDetail = wc_get_product( $values['product_id'] );
					            echo '<div>';
					            echo $getProductDetail->get_image('checkout');
					            echo '</div>';
					            echo '<div class="checkout-item-details">';
					            echo "<p><b>".$_product->post_title.'</b></p>';
					            $price = get_post_meta($values['product_id'] , '_price', true);
					            echo "<p>$".$price;
					            echo ' x '.$values['quantity'].'</p>';
					            echo '</div>';
					            echo '</div>';
					        }
						?>
					</div>
					<hr>
					<div class="cart-review">
						<div>
							<h3>Order Subtotal:</h3>
							<h3 class="cart-total-val"><?php wc_cart_totals_subtotal_html(); ?></h3>
						</div>
						<div>
							<h3>Shipping:</h3>
							<h3 class="cart-total-val"></h3>
						</div>
						<div>
							<h3>Order Total:</h3>
							<h3 class="cart-total-val"><?php wc_cart_totals_order_total_html(); ?></h3>
						</div>
						<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="button-a checkout-back-to-cart">Back to Cart</a>
					</div>
					<div class="bill-ship-info">
						<div class="bill-info hide">
							<h3>Billing Address</h3>
						</div>
						<div class="ship-info hide">
							<h3>Shipping Address</h3>
						</div>							
					</div>
				</div>
				<div class="columns large-8 checkout-forms">
					<hr class="checkout-hr ch-hr-3 hide">
					<div class="col-3 payment hide">
						<h3>Payment</h3>
						<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
						<div id="order_review" class="woocommerce-checkout-review-order">
							<?php do_action( 'woocommerce_checkout_order_review' ); ?>
							<a class="button-a payment-back checkout-back">Back to Shipping</a>
						</div>
						<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
					</div>
				</div>

			</div>
		</div>
	<?php endif; ?>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
