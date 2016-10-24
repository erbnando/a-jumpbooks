<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$catimgid = get_field('book_list_thumbnail_image');
$catimg = wp_get_attachment_image_src($catimgid, 'full');
?>

<figure>
<?php if (get_field('book_list_thumbnail_image')) { ?>
<a class="nodec" href="<?php echo get_permalink($product->ID); ?>"><img src="<?php echo $catimg[0]; ?>"></a>
<?php } else { ?>
<a class="nodec" href="<?php echo get_permalink($product->ID); ?>"><?php echo get_the_post_thumbnail($product->ID, 'full'); ?></a>
<?php } ?>
</figure>
<a href="<?php echo get_permalink($product->ID); ?>"><h5><?php echo get_the_title($product->ID); ?></h5></a>
<p><?php echo get_field('book_subheading', $product->ID); ?></p>
