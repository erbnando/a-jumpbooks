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

<?php get_template_part( 'template-parts/featured-image' ); ?>

<div id="page" role="main">

<?php while ( have_posts() ) : the_post(); ?>
  <div class="row">
    <div class="columns large-11 small-centered">
      <div class="row">
        <div class="columns medium-6 detail-feat-img">
          <img src="<?php get_template_directory_uri() ?>/wp-content/themes/a-jumpbooks/assets/images/list_featured.png">
          <div class="book-links">
            <h5>Relevant Links</h5>
            <p>April 10th, 2015: an interview <a href="#">in The Great Leap Sideways</a> with Michael Ashkin about his A-Jump Books publication, Long Branch.</p>
            <p>Jan 10th, 2015: Lorem Ipsum dolor <a href="#">al sigmet condispigin Elit.</a></p>
          </div>
        </div>
        <div class="columns medium-6 detail-feat-text">
          <h2 class="book-title">Long Branch</h2>
          <h5>Photographs and text by Michael Ashkin</h5>
          <div class="book-info">
            <p>144 pages, 89 duotone illustrations</p>
            <p>Smyth-sewn, softcover, 8.5 x 6.75 inches</p>
            <p>ISBN: 978-0-9905587-0-5</p>
            <p>Edition of 500</p>
            <p>Retail: $45</p>
          </div>
          <button class="button-a">Add to Cart</button>
          <div class="book-description">
            <p>Michael Ashkin’s Long Branch documents the eradication of a New Jersey working-class, beachfront neighborhood by a corrupt city government and developers in what has been described as one of the worst cases of eminent domain abuse in the country. Family homes condemned and purchased for pennies on the dollar, residents evicted, city services curtailed, mock police raids staged on abandoned homes; all to cleanse the beachfront and install sanitized condo complexes generating tremendous profit. In the end barely a single tree or street remains of this historic bungalow neighborhood.</p>
            <p>Shot in vertical format, these fractured, unpeopled photographs from 2002 and 2007 resemble an abandoned urban war zone. Accompanying the photographs, Ashkin includes a fractured narrative built of quotes from newspaper articles, letters to the editor, activist blogs, and real estate websites that mirrors linguistically the struggle visible in the photographs.</p>
            <p>This book constitutes the latest project in various media that address the urban landscape. His photographs of the New Jersey Meadowlands have been shown at Documenta11 (Kassel, Germany), Andrea Rosen Gallery (NYC), and the Renaissance Society (Chicago). His sculptural installations, landscape models, and video work have been widely shown, recently at Secession (Vienna).</p>            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="columns large-12 list-sep-cont">
          <hr class="home-sep">
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