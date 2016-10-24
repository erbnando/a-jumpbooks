<?php
/**
* Template Name: Book Detail
*
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages and that
* other "pages" on your WordPress site will use a different template.
*
* @package FoundationPress
* @since FoundationPress 1.0.0
*/

get_header(); ?>

<div id="page" class="book" role="main">

  <div class="row">
    <div class="columns large-11">
      <div class="row">
		<?php 
			if (is_singular('product')) {
			     woocommerce_content();
			} else {
			    woocommerce_get_template( 'archive-product.php' );
			}
		?>
      </div>
      <?php get_template_part('footer-content'); ?>
    </div>
  </div>

</div>
</section>

<?php get_footer(); ?>