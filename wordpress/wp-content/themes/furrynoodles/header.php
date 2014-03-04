<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="container">
 *
 * @package furrynoodles
 * @subpackage wordpress_site
 * @since Furrynoodles 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>

<?php wp_head(); ?>

<script type="text/javascript" src="//use.typekit.net/bpd6bfy.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() ?>/style.css">
</head>

<body <?php body_class(); ?>>
<div id="container">
	<header id="masthead">
		<hgroup>
			<h1 class="site-title"><a id="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<!--<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>-->
		</hgroup>

		<nav id="site-navigation" class="main-navigation clear">
			<!--<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>-->
			<a id="contact-email" href="mailto:madeupfruits@gmail.com">madeupfruits@gmail.com</a>
      <br/>
			<a href="/about-us">About us</a>
      <br/>
			<a href="/about-us">Work</a>
		</nav><!-- #site-navigation -->

	</header><!-- #masthead -->

	<div id="content" class="clear">
