<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $product;

the_title( '<h2 class="book-title">', '</h2>' ); ?>
<h5><?php the_field('book_subheading'); ?></h5>
<div class="book-info"><?php the_field('book_details'); ?>
<?php if (get_field('special_edition_version')) { ?>
	<?php 
	$special = get_field('special_edition_version');
	?>
	<a href="<?php echo get_permalink($special->ID); ?>" class="special-text">Click here for special edition</a>
<?php } ?>
</div>
<?php if (get_field('featured_book_type', $post->ID) == 'comingsoon') { ?>
	<a class="add-to-cart button-a" href="?add-to-cart=<?php echo $post->ID; ?>">Preorder</a>
<?php } else { ?>
	<?php if ( $product->is_in_stock() ) { ?>
		<a class="add-to-cart" href="?add-to-cart=<?php echo $post->ID; ?>"><button class="button-a">Add to Cart</button></a>
	<?php } else { ?>
		<h5 class="out-of-print">OUT OF PRINT</h5>
	<?php }	?>
<?php } ?>
<p class="product-added"><?php the_title(); ?> was successfully added to your cart.</p>
<?php
