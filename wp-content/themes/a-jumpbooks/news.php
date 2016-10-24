<?php
/**
* Template Name: News
*
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages and that
* other "pages" on your WordPress site will use a different template.
*
* @package FoundationPress
* @since FoundationPress 1.0.0
*/

get_header(); ?>

<?php
$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

$args = array(
  'numberposts' => -1,
  'posts_per_page' => 1,
  'post_type' => 'news',
  'orderby' => 'date',
  'order' => 'DESC',
  'paged' => $paged,
  );
$news = new WP_Query($args);
?>

<div id="page" role="main">

<?php while ( have_posts() ) : the_post(); ?>
  <div class="row">
    <div class="columns large-11 small-centered">
      <div class="row">
        <div class="columns medium-12 news">
          <h2 class="title">News</h2>
          <div class="news-items">
            <?php
            while ( $news->have_posts() ) : $news->the_post(); 
            ?>
              <p class="news-item"><?php the_content(); ?></p>
            <?php 
            endwhile;
            ?>
          </div>
          <?php
          $big = 999999999; // need an unlikely integer
          echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages
          ) );
          ?>
        </div>
      </div>
      <div class="row">
        <div class="columns large-12 list-sep-cont">
          <hr class="home-sep">
          <?php $pageID = get_option('page_on_front'); ?>
          <h3><?php echo strip_tags(get_field('homepage_intro_text', $pageID)); ?></h3>
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