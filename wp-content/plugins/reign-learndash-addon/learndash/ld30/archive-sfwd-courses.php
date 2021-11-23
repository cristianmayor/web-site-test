<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Reign
 */
get_header();
?>
<?php do_action( 'reign_before_content_section' ); ?>
<div class="content-wrapper">

	<?php
	learnmate_get_template( 'ld-template-parts/course-topbar.php' );
	?>

	<?php if ( have_posts() ) : ?>
		<?php
		$view_to_render = isset( $_COOKIE['learnmate_course_view'] ) ? $_COOKIE['learnmate_course_view'] : 'lm-grid-view';
		echo '<div id="lm-course-archive-data" class="' . $view_to_render . '">';
			while ( have_posts() ) :
				the_post();
				learnmate_get_template( 'ld-template-parts/course-list-view.php' );
			endwhile;
		echo '</div>';

		// Previous/next page navigation.
		echo '<div class="lm-course-pagination-section">';
			the_posts_pagination( array(
				'prev_text'          => __( '<i class="fa fa-angle-double-left"></i>', 'reign-learndash-addon' ),
				'next_text'          => __( '<i class="fa fa-angle-double-right"></i>', 'reign-learndash-addon' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'reign-learndash-addon' ) . ' </span>',
			) );
		echo '</div>';	

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</div>

<?php do_action( 'reign_after_content_section' ); ?>

<?php
get_footer();
