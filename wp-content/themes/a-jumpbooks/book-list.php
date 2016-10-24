<?php
/**
* Template Name: Book List
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
        <div class="columns medium-6 list-feat-img">
          <img src="<?php get_template_directory_uri() ?>/wp-content/themes/a-jumpbooks/assets/images/list_featured.png">
        </div>
        <div class="columns medium-6 list-feat-text">
          <h4>FEATURED</h4>
          <h2>Long Branch</h2>
          <h5>Photographs and text by Michael Ashkin</h5>
          <p class="list-text">Long Branch documents the eradication of a New Jersey working-class, beachfront neighborhood by a corrupt city government and developers in what has been described as one of the worst cases of eminent domain abuse in the country. Family homes condemned and purchased for pennies on the dollar, residents evicted, city services curtailed, mock police raids staged on abandoned homes; all to cleanse the beachfront and install sanitized condo complexes generating tremendous profit. In the  end barely a single tree or street remains of this historic bungalow neighborhood.</p>
          <button class="button-a">Info & Purchasing</button>
        </div>
      </div>
      <div class="row">
        <div class="columns large-12 list-sep-cont">
          <hr class="home-sep">
          <div class="view-all-list">
            <a class="view-all">View all</a>
            <div class="hide">
              <ul>
                <li>
                  <a href="#">Out of Print</a>
                </li>
                <li>
                  <a href="#">Available</a>
                </li>
                <li>
                  <a href="#">View All</a>
                </li>
              </ul>
            </div>            
          </div>
          <div class="row grid-a" data-equalizer>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x800">
              </figure>
              <a href="#"><h5>Long Branch</h5></a>
              <p>Photographs and text by Michael Ashkin</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/800x400">
              </figure>
              <a href="#"><h5>The History of Photography in Pen & Ink (Second Edition)</h5></a>
              <p>Pen & Ink Drawings by Charles Woodard</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x400">
              </figure>
              <h5>From the Bottom of a Well</h5>
              <p>Photographs by Shawn Records</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x800">
              </figure>
              <h5>The Amnesia Pavilions</h5>
              <p>Photographs and text by Nicholas Muellner</p>
            </div>
          </div>

          <div class="row grid-a" data-equalizer>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/800x400">
              </figure>
              <a href="#"><h5>The History of Photography in Pen & Ink (Second Edition)</h5></a>
              <p>Pen & Ink Drawings by Charles Woodard</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x400">
              </figure>
              <h5>From the Bottom of a Well</h5>
              <p>Photographs by Shawn Records</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x800">
              </figure>
              <a href="#"><h5>Long Branch</h5></a>
              <p>Photographs and text by Michael Ashkin</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x800">
              </figure>
              <h5>The Amnesia Pavilions</h5>
              <p>Photographs and text by Nicholas Muellner</p>
            </div>
          </div>

          <div class="row grid-a" data-equalizer>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x400">
              </figure>
              <h5>From the Bottom of a Well</h5>
              <p>Photographs by Shawn Records</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x800">
              </figure>
              <a href="#"><h5>Long Branch</h5></a>
              <p>Photographs and text by Michael Ashkin</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/400x800">
              </figure>
              <h5>The Amnesia Pavilions</h5>
              <p>Photographs and text by Nicholas Muellner</p>
            </div>
            <div class="columns large-3 medium-6 text-center" data-equalizer-watch>
              <figure data-equalizer-watch>
                <img src="http://placehold.it/800x400">
              </figure>
              <a href="#"><h5>The History of Photography in Pen & Ink (Second Edition)</h5></a>
              <p>Pen & Ink Drawings by Charles Woodard</p>
            </div>
          </div>

          <hr class="home-sep last-sep">
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