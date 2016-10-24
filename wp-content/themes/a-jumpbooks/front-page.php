<?php
/**
* Template Name: Homepage
*
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages and that
* other "pages" on your WordPress site will use a different template.
*
* @package FoundationPress
* @since FoundationPress 1.0.0
*/

get_header(); ?>

<div id="page" role="main">
<?php while ( have_posts() ) : the_post(); ?>
  <div class="row">
    <div class="columns large-11 small-centered">
      <div class="row">
        <div class="columns large-10 feat-img">
        <?php 
        $images = get_field('homepage_slider');
        if( $images ): ?>
          <div class="home-slider">
            <?php foreach( $images as $image ): ?>
              <div class="slide">
                <div style="background-image: url(<?php echo $image['url']; ?>)">
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        </div>
        <div class="columns large-2 feat-text">
          <?php if (get_field("homepage_category")) { ?>
            <h4 class="caps"><?php the_field("homepage_category"); ?></h4>
          <?php } ?>
          <?php if (get_field("homepage_title")) { ?>
            <h2><?php the_field("homepage_title"); ?></h2>
          <?php } ?>
          <?php if (get_field("homepage_subtitle")) { ?>
            <h5><?php the_field("homepage_subtitle"); ?></h5>
          <?php } ?>
          <?php if (get_field("homepage_button_text")) { ?>
            <a href="<?php the_field("homepage_button_url"); ?>"><button class="button-a"><?php the_field("homepage_button_text"); ?></button></a>
          <?php } ?>
        </div>
      </div>
      <div class="row">
        <div class="columns large-12 last-sep-cont">
          <hr class="last-sep">
          <p class="home-footer-text"><?php echo strip_tags(get_field('homepage_intro_text')); ?></p>
          <p class="home-footer"><span>A-Jump Books ©2016.</span> <span>All Rights Reserved.</span></p>
          <a href="http://www.mrfa.design" target="_blank" class="siteby">Site by MRFA</a>
        </div>
      </div>
    </div>
  </div>
<?php endwhile;?>

</div>
</section>

<?php get_footer(); ?>