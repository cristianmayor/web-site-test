<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Reign
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php do_action( 'reign_before_page' ) ?>
		<div id="page" class="site">
			<?php do_action( 'reign_before_masthead' ); ?>
			<header id="masthead" class="site-header" role="banner">
				<?php do_action( 'reign_begin_masthead' ); ?>
				
				<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>
						<?php do_action( 'reign_masthead' ); ?>
				<?php } ?>
				
				<?php do_action( 'reign_end_masthead' ); ?>
			</header>
			<?php do_action( 'reign_after_masthead' ); ?>
			<?php do_action( 'reign_before_content' ); ?>
			<div id="content" class="site-content">
				<?php do_action( 'reign_content_top' ); ?>