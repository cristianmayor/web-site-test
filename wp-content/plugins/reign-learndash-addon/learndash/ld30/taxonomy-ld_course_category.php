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

	<?php if ( have_posts() ) : ?>

		<!-- <header class="page-header">
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header> --><!-- .page-header -->

		<?php
		learnmate_get_template( 'ld-template-parts/course-topbar.php' );
		?>

		<?php
		$view_to_render = isset( $_COOKIE['learnmate_course_view'] ) ? $_COOKIE['learnmate_course_view'] : 'lm-grid-view';
		echo '<div id="lm-course-archive-data" class="' . $view_to_render . '">';
			while ( have_posts() ) :
				the_post();
				learnmate_get_template( 'ld-template-parts/course-list-view.php' );
			endwhile;
		echo '</div>';

		// if ( $blog_list_layout == 'masonry-view' ) {
		// 	echo '</div>';
		// }

		// Previous/next page navigation.
		echo '<div class="lm-course-pagination-section">';
			the_posts_pagination( array(
				'prev_text'          => __( '<i class="fa fa-angle-double-left"></i>', 'reign-learndash-addon' ),
				'next_text'          => __( '<i class="fa fa-angle-double-right"></i>', 'reign-learndash-addon' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'reign-learndash-addon' ) . ' </span>',
			) );
		echo '</div>';	

	else :

		// learnmate_get_template( 'template-parts/content', 'none' );

	endif;
	?>

</div>

<?php do_action( 'reign_after_content_section' ); ?>

<?php
get_footer();
