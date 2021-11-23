<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Reign_LD_Course_List_Customization' ) ) :
/**
 * @class Reign_LD_Course_List_Customization
 */
class Reign_LD_Course_List_Customization {
	/**
	 * The single instance of the class.
	 *
	 * @var Reign_LD_Course_List_Customization
	 */
	protected static $_instance = null;
	/**
	 * Main Reign_LD_Course_List_Customization Instance.
	 *
	 * Ensures only one instance of Reign_LD_Course_List_Customization is loaded or can be loaded.
	 *
	 * @return Reign_LD_Course_List_Customization - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	/**
	 * Reign_LD_Course_List_Customization Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}
	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_filter( 'ld_course_list', array( $this, 'manage_customized_ld_course_list' ), 50, 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'manage_learndash_course_grid_scripts' ) );
		add_filter( 'learndash_template', array( $this, 'remove_learndash_course_grid_course_list' ), 50, 5 );
	}

	public function manage_customized_ld_course_list( $output, $atts, $filter ) {
		$template = '';
		if ( isset( $atts['template'] ) && 'layout-1' === $atts['template'] ) {
			$template = 'reign-course-list-layout1';
		}
		$output = '<div id="lm-course-archive-data" class="lm-grid-view lm-ld-grid-view '. $template .'">' . $output . '</div>';
		return $output;
	}

	public function manage_learndash_course_grid_scripts() {
		global $post;
		
		if( ( is_a( $post, 'WP_Post' ) && ( has_shortcode( $post->post_content, 'ld_course_list') || has_shortcode( $post->post_content, 'ld_lesson_list') || has_shortcode( $post->post_content, 'ld_quiz_list') || has_shortcode( $post->post_content, 'ld_topic_list') ) ) || ( class_exists('BuddyPress') && bp_is_user() ) ) {
			if( defined( 'LEARNDASH_COURSE_GRID_FILE' ) ) {
				if( class_exists('BuddyPress')) {
					if( bp_is_user() ) {
						learndash_course_grid_load_resources();
					}
				}		
				wp_enqueue_style( 'learndash_course_grid_css', plugins_url( 'style.css', LEARNDASH_COURSE_GRID_FILE ) );
				wp_enqueue_script( 'learndash_course_grid_js', plugins_url( 'script.js', LEARNDASH_COURSE_GRID_FILE ), array('jquery' ) );
				wp_enqueue_style( 'ld-cga-bootstrap', plugins_url( 'bootstrap.min.css', LEARNDASH_COURSE_GRID_FILE ) );
			}
		}
	}
	public function remove_learndash_course_grid_course_list( $filepath, $name, $args, $echo, $return_file_path ) {
		remove_filter( 'learndash_template', 'learndash_course_grid_course_list', 99, 5);
		if ( $name == "course_list_template" && $filepath == LEARNDASH_LMS_PLUGIN_DIR . 'templates/course_list_template.php' ) {
			$name = 'course_list_template.php';
			$file = 'templates' . '/'. $name;
			if( isset( $file ) && file_exists( learnmate_locate_template( $file ) ) ) {
				$filepath = learnmate_locate_template( $file );
				return $filepath;
			}
		}
		return $filepath;
	}
}
endif;
/**
 * Main instance of Reign_LD_Course_List_Customization.
 * @return Reign_LD_Course_List_Customization
 */
Reign_LD_Course_List_Customization::instance();