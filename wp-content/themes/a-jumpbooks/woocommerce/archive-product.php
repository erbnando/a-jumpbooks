<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

	<?php $args = array(
		'numberposts' => 1,
		'post_type' => 'product',
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key' => 'featured',
				'value' => 'list',
				'compare' => 'LIKE'
			)
		)
	);

	$featured = get_posts($args);
  if (isset($_POST['sort']) && !empty($_POST['sort'])) {
    if ($_POST['sort'] == 'outofstock') {
      $args2 = array( 
        'post_type' => 'product',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
          array(
            'key' => '_stock_status',
            'value' => 'outofstock',
            'compare' => '='
          ),
          array(
            'key' => '_visibility',
            'value' => array( 'catalog', 'visible' ),
            'compare' => 'IN'
          )
        )
      );
    } elseif ($_POST['sort'] == 'instock') {
      $args2 = array( 
        'post_type' => 'product',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
          array(
            'key' => '_stock_status',
            'value' => 'instock',
            'compare' => '='
          ),
          array(
            'key' => '_visibility',
            'value' => array( 'catalog', 'visible' ),
            'compare' => 'IN'
          )
        )
      );
    } elseif ($_POST['sort'] == 'viewall') {
      $args2 = array( 
        'post_type' => 'product',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
          array(
            'key' => '_visibility',
            'value' => array( 'catalog', 'visible' ),
            'compare' => 'IN'
          )
        )
      );
    }
  } else {
    $args2 = array( 
      'post_type' => 'product',
      'posts_per_page' => -1,
      'orderby' => 'date',
      'order' => 'DESC',
      'meta_query' => array(
        array(
          'key' => '_visibility',
          'value' => array( 'catalog', 'visible' ),
          'compare' => 'IN'
        )
      )
    );
  }
  
  $loop = new WP_Query($args2);

/*	?><pre><?php print_r($featured); ?></pre><?php
*/	?>

    <div class="columns medium-6 list-feat-img">
    <?php if (get_field('featured_book_list_image', $featured[0]->ID)) { ?>
      <a class="nodec" href="<?php echo get_permalink($featured[0]->ID); ?>"><img src="<?php echo get_field('featured_book_list_image', $featured[0]->ID); ?>" /></a>
    <?php } else { ?>
      <a class="nodec" href="<?php echo get_permalink($featured[0]->ID); ?>"><?php echo get_the_post_thumbnail($featured[0]->ID, "a-jump"); ?></a>      
    <?php } ?>
    </div>
    <div class="columns medium-6 list-feat-text">
      <?php if (get_field('featured_book_type', $featured[0]->ID) == 'newrelease') {
      ?>
        <h4>NEW RELEASE</h4>
        <a class="nodec" href="<?php echo get_permalink($featured[0]->ID); ?>"><h2><?php echo get_the_title($featured[0]->ID); ?></h2></a>
        <h5><?php echo get_field('book_subheading', $featured[0]->ID); ?></h5>
        <p class="list-text"><?php echo strip_tags(get_field('featured_book_list_description', $featured[0]->ID)); ?></p>
        <a href="<?php echo get_permalink($featured[0]->ID); ?>"><button class="button-a">Info & Purchasing</button></a>
      <?php
      } else if (get_field('featured_book_type', $featured[0]->ID) == 'comingsoon') {
      ?>
        <h4>COMING SOON</h4>
        <a class="nodec" href="<?php echo get_permalink($featured[0]->ID); ?>"><h2><?php echo get_the_title($featured[0]->ID); ?></h2></a>
        <h5><?php echo get_field('book_subheading', $featured[0]->ID); ?></h5>
        <p class="list-text"><?php echo strip_tags(get_field('featured_book_list_description', $featured[0]->ID), "<span><a><strong><em>"); ?></p>
        <a href="<?php echo get_permalink($featured[0]->ID); ?>"><button class="button-a">Preorder</button></a>
      <?php 
      }
      ?>
    </div>
  </div>
  <div class="row">
    <div class="columns large-12 list-sep-cont">
      <hr class="home-sep list-sep-a">
      <div class="view-all-list">
        <a id="view-all">Sort by</a>
        <div class="hide">
          <ul>
            <li>
              <a id="outof" href="#">Out of Print</a>
            </li>
            <li>
              <a id="avail" href="#">Available</a>
            </li>
            <li>
              <a id="viewall" href="#">View All</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="row grid-a">
				<?php if ( $loop->have_posts() ) : ?>
				<?php woocommerce_product_loop_start(); ?>
					<?php woocommerce_product_subcategories(); ?>
            <?php
              while ( $loop->have_posts() ) : $loop->the_post();
                if (($loop->current_post + 1) == ($loop->post_count)) { ?>
                  <div class="book-item columns large-3 medium-6 text-center end">
                <?php } else { ?>
                  <div class="book-item columns large-3 medium-6 text-center">
                <?php }
                    wc_get_template_part( 'content', 'product' ); ?>
                  </div>
              <?php
              endwhile;
            ?>
				<?php woocommerce_product_loop_end(); ?>
				<?php endif; ?>
      </div>
    </div>
