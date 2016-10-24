<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<div id="page" role="main">
  <div class="row">
    <div class="columns large-11 small-centered">
      <div class="row">
        <div class="columns medium-12 news">
          <h2 class="title">News</h2>
          <div class="news-items">
					<?php if ( have_posts() ) : ?>

						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>
				      <article class="news-item"><?php the_content(); ?></article>
						<?php endwhile; ?>

						<?php else : ?>
							<?php get_template_part( 'template-parts/content', 'none' ); ?>

						<?php endif; // End have_posts() check. ?>

						<?php /* Display navigation to next/previous pages when applicable */ ?>
						<?php if ( function_exists( 'foundationpress_pagination' ) ) { foundationpress_pagination(); } else if ( is_paged() ) { ?>
							<nav id="post-nav">
								<div class="post-previous"><?php next_posts_link( __( '&larr; Older posts', 'foundationpress' ) ); ?></div>
								<div class="post-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'foundationpress' ) ); ?></div>
							</nav>
						<?php } ?>
	        </div>
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

<?php get_footer();
