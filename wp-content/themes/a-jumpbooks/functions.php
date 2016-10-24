<?php
/**
 * Author: Ole Fredrik Lie
 * URL: http://olefredrik.com
 *
 * FoundationPress functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

/** Various clean up functions */
require_once( 'library/cleanup.php' );

/** Required for Foundation to work properly */
require_once( 'library/foundation.php' );

/** Register all navigation menus */
require_once( 'library/navigation.php' );

/** Add menu walkers for top-bar and off-canvas */
require_once( 'library/menu-walkers.php' );

/** Create widget areas in sidebar and footer */
require_once( 'library/widget-areas.php' );

/** Return entry meta information for posts */
require_once( 'library/entry-meta.php' );

/** Enqueue scripts */
require_once( 'library/enqueue-scripts.php' );

/** Add theme support */
require_once( 'library/theme-support.php' );

/** Add Nav Options to Customer */
require_once( 'library/custom-nav.php' );

/** Change WP's sticky post class */
require_once( 'library/sticky-posts.php' );

/** Configure responsive image sizes */
require_once( 'library/responsive-images.php' );

/** If your site requires protocol relative url's for theme assets, uncomment the line below */
// require_once( 'library/protocol-relative-theme-assets.php' );

show_admin_bar(false);

add_action( 'add_meta_boxes', 'remove_my_meta_boxes', 40);
function remove_my_meta_boxes()
{
    remove_meta_box( 'postexcerpt', 'product', 'normal');
    remove_meta_box( 'commentsdiv', 'product', 'normal');
	remove_meta_box( 'product_catdiv', 'product', 'side'); 
	remove_meta_box( 'tagsdiv-product_tag', 'product', 'side');
}

add_filter( 'wp_nav_menu_items', 'add_search_to_nav', 10, 2 );
function add_search_to_nav( $items, $args )
{
	global $woocommerce;
	$count = $woocommerce->cart->cart_contents_count;
	if ($count > 0) {
    	$items .= '<li class="menu-item menu-item-cart"><a href="/cart">Cart (<span style="color: #a32f38;">' . $count . '</span>)</a></li>';
	} else {
    	$items .= '<li class="menu-item menu-item-cart"><a href="/cart">Cart</a></li>';
	}
    return $items;
}

add_image_size('a-jump', 1300);
add_image_size('catalog', 450);
add_image_size('checkout', 400);
add_image_size('home-slider', 800, 550, true);

function be_archive_query( $query ){
    if( ! is_admin()
        && $query->is_post_type_archive( 'news' )
        && $query->is_main_query() ){
            $query->set( 'posts_per_page', 25 );
    }
}
add_action( 'pre_get_posts', 'be_archive_query' );

add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');
function custom_woocommerce_billing_fields( $fields ) {
    $fields['billing_address_1']['class'] = array( 'form-row-last' );
    $fields['billing_city']['class'] = array( 'form-row-first' );
    $fields['billing_state']['class'] = array( 'form-row-last' );
    $fields['billing_postcode']['class'] = array( 'form-row-first' );
    $fields['billing_country']['class'] = array( 'form-row-last' );
    $fields['billing_postcode']['clear'] = '';
    $fields['billing_address_2']['label'] = 'Address 2';
    $fields['billing_first_name']['placeholder'] = 'First Name';
    $fields['billing_last_name']['placeholder'] = 'Last Name';
    $fields['billing_email']['placeholder'] = 'Email Address';
    $fields['billing_phone']['placeholder'] = 'Phone Number';
    $fields['billing_address_1']['placeholder'] = 'Address';
    $fields['billing_city']['placeholder'] = 'Town / City';
    $fields['billing_state']['placeholder'] = 'State';
    $fields['billing_postcode']['placeholder'] = 'Zip';
    $fields['billing_country']['placeholder'] = 'Country';
    return $fields;
}

add_filter('woocommerce_shipping_fields', 'custom_woocommerce_shipping_fields');
function custom_woocommerce_shipping_fields( $fields ) {
    $fields['shipping_address_1']['class'] = array( 'form-row-first' );
    $fields['shipping_address_2']['class'] = array( 'form-row-last' );
    $fields['shipping_city']['class'] = array( 'form-row-first' );
    $fields['shipping_state']['class'] = array( 'form-row-last' );
    $fields['shipping_postcode']['class'] = array( 'form-row-first' );
    $fields['shipping_country']['class'] = array( 'form-row-last' );
    $fields['shipping_postcode']['clear'] = '';
    $fields['shipping_address_2']['label'] = 'Address 2';
    $fields['shipping_first_name']['placeholder'] = 'First Name';
    $fields['shipping_last_name']['placeholder'] = 'Last Name';
    $fields['shipping_email']['placeholder'] = 'Email Address';
    $fields['shipping_address_1']['placeholder'] = 'Address';
    $fields['shipping_city']['placeholder'] = 'Town / City';
    $fields['shipping_state']['placeholder'] = 'State';
    $fields['shipping_postcode']['placeholder'] = 'Zip';
    $fields['shipping_country']['placeholder'] = 'Country';
    return $fields;
}

add_action( 'wp_enqueue_scripts', 'mgt_dequeue_stylesandscripts', 100 );
function mgt_dequeue_stylesandscripts() {
    if ( class_exists( 'woocommerce' ) ) {
        wp_dequeue_style( 'select2' );
        wp_deregister_style( 'select2' );
        wp_dequeue_script( 'select2');
        wp_deregister_script('select2');
    } 
} 

add_action( 'admin_print_styles-post.php', 'product_admin_styles', 11 );
function product_admin_styles() {
    global $post_type;
    if( 'product' == $post_type )
        wp_enqueue_style( 'product-admin-styles', get_stylesheet_directory_uri() . '/assets/stylesheets/book-admin.css' );
}

add_filter( 'default_checkout_country', 'change_default_checkout_country' );

function change_default_checkout_country() {
  return 'US';
}

function custom_checkbox_checker () {
    if (is_checkout()) {
        wp_enqueue_script('jquery'); ?>
        <script>
        jQuery(document).ready( function (e) {
            var $ = jQuery;
            if ( typeof wc_checkout_params === 'undefined' )
                return false;
            var updateTimer, dirtyInput = false, xhr;
            function update_shipping() {
                if (xhr) xhr.abort();
                var data = {
                    action: 'woocommerce_update_order_review',
                    security: wc_checkout_params.update_order_review_nonce,
                    post_data: $('form.checkout').serialize()
                };
                xhr = $.ajax({
                    type: 'POST',
                    url: wc_checkout_params.ajax_url,
                    data: data,
                    success: function(response) {
                        $('body').trigger('update_checkout');
                        setTimeout(function (){ 
                            amount = $("#order_review table tfoot tr.shipping td span").html();
                            total = $("#order_review table tfoot tr.order-total td span").html();
                            $('.cart-review div:nth-child(2) h3.cart-total-val').html(amount);
                            $('.cart-review div:nth-child(3) h3.cart-total-val').html(total);
                            console.log(amount);
                            if ($("#billing_country").val() == "US" || $("#billing_country").val() == "CA") {
                                $(".payment_method_paypal").addClass("hide");
                                $(".payment_method_square").removeClass("hide");
                                $("#payment_method_square").prop("checked", true);
                                $("#payment_method_paypal").prop("checked", false);
                                $("#place_order").attr("value", "Place order");
                            } else {
                                $(".payment_method_paypal").removeClass("hide");
                                $(".payment_method_square").addClass("hide");
                                $("#payment_method_paypal").prop("checked", true);
                                $("#payment_method_square").prop("checked", false);
                                $("#place_order").attr("value", "Place order").attr("data-value", "Place order");
                                $("#place_order").attr("value", "Proceed to Paypal");
                            }
                        }, 1000);
                    },
                    error: function(code) {
                        console.log('ERROR');
                    }
                });
            }
            jQuery('#shipping_country').change(function(e, params){
                update_shipping();
            });
        });
        </script>
<?php 
    }

}

add_action( 'wp_footer', 'custom_checkbox_checker', 50 );

add_filter( 'woocommerce_checkout_fields', 'remove_checkout_validation', 100 ); 
function remove_checkout_validation( $fields ) {
    unset($fields['billing']['billing_first_name']['validate']);
    unset($fields['billing']['billing_last_name']['validate']);
    unset($fields['billing']['billing_phone']['validate']);
    unset($fields['billing']['billing_address_1']['validate']);
    unset($fields['billing']['billing_address_2']['validate']);
    unset($fields['billing']['billing_city']['validate']);
    unset($fields['billing']['billing_state']['validate']);
    unset($fields['billing']['billing_postcode']['validate']);
    unset($fields['billing']['billing_country']['validate']);
    unset($fields['shipping']['shipping_first_name']['validate']);
    unset($fields['shipping']['shipping_last_name']['validate']);
    unset($fields['shipping']['shipping_address_1']['validate']);
    unset($fields['shipping']['shipping_address_2']['validate']);
    unset($fields['shipping']['shipping_city']['validate']);
    unset($fields['shipping']['shipping_state']['validate']);
    unset($fields['shipping']['shipping_postcode']['validate']);
    unset($fields['shipping']['shipping_country']['validate']);
    return $fields;
}

/*
add_filter( 'cron_schedules', 'add_minutely_schedule' );
function add_minutely_schedule( $schedules ) {
    $schedules['minutely'] = array(
        'interval' => 60, // 60 seconds
        'display'  => __( 'Every Minute' ),
    );
    return $schedules;
}
*/

// Add function to register event to WordPress init
add_action( 'init', 'wc_system_report');

// Function which will register the event
function wc_system_report() {
    // Make sure this event hasn't been scheduled
    if( !wp_next_scheduled( 'daily_wc_system_report' ) ) {
        // Schedule the event
        wp_schedule_event( time(), 'daily', 'daily_wc_system_report' );
    }
}

add_action( 'daily_wc_system_report', 'send_daily_wc_system_report' );

// This function will run once the 'delete_post_revisions' is called
function send_daily_wc_system_report() {

    foreach (glob("wp-content/uploads/wc-logs/*.log") as $filename) {
        if (strpos($filename, 'woocommerce-gateway-square') !== false) {
            echo $filename;
            $attachment = $filename;
        }
    }

    $to = 'erbnando@gmail.com';
    $subject = 'A-Jump Books Woocommerce System Report for ' . current_time('Y-m-d');
    $body = 'Daily System Report -- See attachment';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $attachment = array($attachment);
    
    wp_mail( $to, $subject, $body, $headers, $attachment );
}

/*
$timestamp = wp_next_scheduled( 'daily_wc_system_report' );
wp_unschedule_event( $timestamp, 'daily_wc_system_report' );
*/

/*
echo '<pre>'; print_r( _get_cron_array() ); echo '</pre>';
*/

?>