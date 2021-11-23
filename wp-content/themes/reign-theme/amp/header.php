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
	<body <?php body_class( 'reign-amp' ); ?>>
		<?php do_action( 'reign_amp_before_page' ) ?>
		<div id="page" class="site reign-amp-site">
			<?php do_action( 'reign_amp_before_masthead' ); ?>
			<header id="masthead" class="site-header" role="banner">
				<?php do_action( 'reign_amp_begin_masthead' ); ?>
				
				<!--  Add AMP header html code -->
                                <?php get_template_part( 'amp/branding' ); ?>
				
				<?php do_action( 'reign_amp_end_masthead' ); ?>
			</header>
			<?php do_action( 'reign_amp_after_masthead' ); ?>
			<?php do_action( 'reign_amp_before_content' ); ?>
			<div id="content" class="site-content">
				<?php do_action( 'reign_amp_content_top' ); ?>