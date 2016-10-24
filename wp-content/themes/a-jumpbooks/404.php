<?php
/**
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages and that
* other "pages" on your WordPress site will use a different template.
*
* @package FoundationPress
* @since FoundationPress 1.0.0
*/

get_header(); ?>

<?php get_template_part( 'template-parts/featured-image' ); ?>

<div id="page" role="main">

  <div class="row">
    <div class="columns large-11 small-centered">
      <div class="row">
        <div class="columns medium-12 generic-page page-404">
          <h2 class="title">Page not found. Check out our <a class="h2anchor" href="<?php echo get_permalink(woocommerce_get_page_id('shop')); ?>">catalog</a>.</h2>
        </div>
      </div>
      <div class="row">
        <div class="columns large-12 last-sep-cont">
          <hr>
          <p class="home-footer"><span>A-Jump Books ©2016.</span> <span>All Rights Reserved.</span></p>
          <a href="http://www.mrfa.design" target="_blank" class="siteby">Site by MRFA</a>
        </div>
      </div>
    </div>
  </div>

</div>
</section>

<?php get_footer(); ?>