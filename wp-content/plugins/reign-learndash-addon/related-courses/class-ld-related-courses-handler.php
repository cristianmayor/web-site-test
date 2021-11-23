<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'LD_Related_Products_Handler' ) ) :

/**
 * @class LD_Related_Products_Handler
 */
class LD_Related_Products_Handler {
	
	/**
	 * The single instance of the class.
	 *
	 * @var LD_Related_Products_Handler
	 */
	protected static $_instance = null;
	
	/**
	 * Main LD_Related_Products_Handler Instance.
	 *
	 * Ensures only one instance of LD_Related_Products_Handler is loaded or can be loaded.
	 *
	 * @return LD_Related_Products_Handler - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * LD_Related_Products_Handler Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'reign_learndash_after_course_content', array( $this, 'render_related_courses' ) );
	}

	public function render_related_courses() {
		global $wbtm_reign_settings;
		$enable_related_course = isset( $wbtm_reign_settings[ 'learndash' ][ 'enable_related_courses' ] ) ? $wbtm_reign_settings[ 'learndash' ][ 'enable_related_courses' ] : 'enable';
		$title_related_courses = isset( $wbtm_reign_settings[ 'learndash' ][ 'title_related_courses' ] ) ? $wbtm_reign_settings[ 'learndash' ][ 'title_related_courses' ] : sprintf( esc_html_x( 'Related %s', 'Enrolled Courses Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'courses' ) );
		$num_of_related_courses = isset( $wbtm_reign_settings[ 'learndash' ][ 'num_of_related_courses' ] ) ? $wbtm_reign_settings[ 'learndash' ][ 'num_of_related_courses' ] : '3';

		if( 'enable' !== $enable_related_course ) { return; }


		global $post;
		$global_post_backup = $post;
		
		// Default arguments
		$args = array(
			'posts_per_page' => $num_of_related_courses, // How many items to display
			'post__not_in'   => array( get_the_ID() ), // Exclude current post
			'no_found_rows'  => true, // We don't need pagination so this speeds up the query
			'post_type' => 'sfwd-courses', // How many items to display
		);

		// Check for current post category and add tax_query to the query arguments
		$cats = wp_get_post_terms( get_the_ID(), 'ld_course_category' );
		$cats_ids = array();
		foreach( $cats as $wpex_related_cat ) {
			$cats_ids[] = $wpex_related_cat->term_id;
		}
		if ( ! empty( $cats_ids ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'ld_course_category',
					'field'    => 'term_id',
					'terms'    => $cats_ids,
					'operator' => 'IN',
				),
			);
		}
		else{
			return;
		}

		// Query posts
		$wpex_query = new wp_query( $args );
		
		if( !empty( $wpex_query->posts ) ) {
			$view_to_render = 'lm-grid-view';
			echo '<div class="lm-related-course-section">';
				echo '<h2 class="entry-title">' . $title_related_courses . '</h2>';
				echo '<div id="lm-course-archive-data" class="' . $view_to_render . '">';
					// Loop through posts
					foreach( $wpex_query->posts as $related_post ) :
						$post = $related_post;
						setup_postdata( $post );
						learnmate_get_template( 'ld-template-parts/course-list-view.php' );
					// End loop
					endforeach;
				echo '</div>';
			echo '</div>';
		}

		// Reset post data
		$post = $global_post_backup;
		wp_reset_postdata();
	}
		
}

endif;

/**
 * Main instance of LD_Related_Products_Handler.
 * @return LD_Related_Products_Handler
 */
LD_Related_Products_Handler::instance();