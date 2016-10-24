<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?> >
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="shortcut icon" type="image/png" href="<?php get_template_directory_uri() ?>/wp-content/themes/a-jumpbooks/assets/images/favicon.ico"/>
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300italic,300,100italic,100,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
		<?php if (is_shop()) { ?><title>Books – A-Jump Books</title><?php } ?>
		<?php wp_head(); ?>

		<script>
		 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		 })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		 ga('create', 'UA-83047745-1', 'auto');
		 ga('send', 'pageview');

		</script>

	</head>
	<body <?php body_class(); ?>>
	<?php do_action( 'foundationpress_after_body' ); ?>

	<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) == 'offcanvas' ) : ?>
	<div class="off-canvas-wrapper">
		<div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
		<?php get_template_part( 'template-parts/mobile-off-canvas' ); ?>
	<?php endif; ?>

	<?php do_action( 'foundationpress_layout_start' ); ?>
	<div class="mq-flag"></div>
	<div class="main-nav-cont">
		<div class="row main-nav">
			<div class="columns small-centered large-11 no-padding">

				<header id="masthead" class="site-header" role="banner">
					<div class="title-bar" data-responsive-toggle="site-navigation">
						<button class="menu-icon" type="button" data-toggle="mobile-menu"></button>
						<div class="title-bar-title">
							<a class="mobile-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php get_template_directory_uri() ?>/wp-content/themes/a-jumpbooks/assets/images/logo.svg"></a>
						</div>
					</div>

					<nav id="site-navigation" class="main-navigation top-bar" role="navigation">
						<div class="top-bar-left">
							<ul class="menu">
								<li class="home"><a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php get_template_directory_uri() ?>/wp-content/themes/a-jumpbooks/assets/images/logo.svg"></a>
								</li>
							</ul>
						</div>
						<div class="top-bar-right">
							<?php foundationpress_top_bar_r(); ?>

							<?php if ( ! get_theme_mod( 'wpt_mobile_menu_layout' ) || get_theme_mod( 'wpt_mobile_menu_layout' ) == 'topbar' ) : ?>
								<?php get_template_part( 'template-parts/mobile-top-bar' ); ?>
							<?php endif; ?>
						</div>
					</nav>
				</header>

			</div>
		</div>
	</div>
	<div class="fixed-nav-cont">
		<div class="row fixed-nav nav-small">
			<div class="columns small-centered large-11 no-padding">

				<header class="site-header" role="banner">
					<div class="title-bar" data-responsive-toggle="site-navigation">
						<button class="menu-icon menu-icon-small" type="button" data-toggle="mobile-menu"></button>
						<div class="title-bar-title">
							<a class="mobile-logo logo-cont-small" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="logo-small" src="<?php get_template_directory_uri() ?>/wp-content/themes/a-jumpbooks/assets/images/logo.svg"></a>
						</div>
					</div>

					<nav id="site-navigation-fixed" class="main-navigation top-bar" role="navigation">
						<div class="top-bar-left">
							<ul class="menu">
								<li class="home"><a class="logo logo-cont-small" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img class="logo-small" src="<?php get_template_directory_uri() ?>/wp-content/themes/a-jumpbooks/assets/images/logo.svg"></a></li>
							</ul>
						</div>
						<div class="top-bar-right">
							<?php foundationpress_top_bar_r(); ?>

							<?php if ( ! get_theme_mod( 'wpt_mobile_menu_layout' ) || get_theme_mod( 'wpt_mobile_menu_layout' ) == 'topbar' ) : ?>
								<?php get_template_part( 'template-parts/mobile-top-bar' ); ?>
							<?php endif; ?>
						</div>
					</nav>
				</header>

			</div>
		</div>
	</div>
	<div class="nav-placeholder"></div>

	<section class="container">