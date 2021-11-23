<?php

/**
 * Includes function related to cart and checkout tab.
 *
 * @package WBPEDDI_PeepSo_EDD_Integration
 * @author Wbcom Designs
 */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'LD_Course_Review_Manager' ) ) :

	/**
	 * Includes all functions related to PeepSo EDD cart & checkout.
	 *
	 * @class LD_Course_Review_Manager
	 */
	class LD_Course_Review_Manager {

		/**
		 * The single instance of the class.
		 *
		 * @var LD_Course_Review_Manager
		 */
		protected static $_instance = null;

		/**
		 * Main LD_Course_Review_Manager Instance.
		 *
		 * Ensures only one instance of LD_Course_Review_Manager is loaded or can be loaded.
		 *
		 * @return LD_Course_Review_Manager - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * LD_Course_Review_Manager Constructor.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->includes();
			$this->init_hooks();
		}

		public function includes() {
			include_once 'ld-comment-template-functions.php';
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {

			add_filter( 'comments_template', array( $this, 'course_comments_template_loader' ), 10, 1 );

			add_filter( 'preprocess_comment', array( $this, 'check_comment_rating' ), 0 );

			add_action( 'comment_post', array( $this, 'add_comment_rating' ), 1 );


			add_action( 'wb_ld_course_review_before', 'wb_ld_course_review_display_gravatar', 10 );

			add_action( 'wb_ld_course_review_before_comment_meta', 'wb_ld_course_review_display_rating', 10 );
			add_action( 'wb_ld_course_review_before_comment_meta', 'wb_ld_course_review_display_title', 11 );

			add_action( 'wb_ld_course_review_meta', 'wb_ld_course_review_display_meta', 10 );

			add_action( 'wb_ld_course_review_comment_text', 'wb_ld_course_review_display_comment_text', 10 );


			// additional
			add_action( 'wb_ld_course_before_comments_list', 'wb_ld_course_display_reviews_overview', 10 );

			add_action( 'wp_enqueue_scripts', array( $this, 'rating_enqueue_scripts' ), 9999 );
		}

		public function rating_enqueue_scripts() {

			wp_register_script(
			$handle		 = 'wb_ld_course_rating_js', $src		 = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/js/jquery.rateyo.min.js', $deps		 = array( 'jquery' ), $ver		 = time(), $in_footer	 = true
			);
			wp_localize_script(
			'wb_ld_course_rating_js', 'wb_ld_course_rating_js_params', array(
				'ajax_url'		 => admin_url( 'admin-ajax.php' ),
				'theme_color'	 => apply_filters( 'learnmate_ld_star_review_color', '#ffb606' )
			)
			);
			wp_enqueue_script( 'wb_ld_course_rating_js' );

			$css_path = is_rtl() ? '/assets/css/rtl' : '/assets/css';

			wp_register_style(
			$handle	 = 'wb_ld_course_rating_css', $src	 = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . $css_path . '/jquery.rateyo.min.css', $deps	 = array(), $ver	 = time(), $media	 = 'all'
			);
			wp_enqueue_style( 'wb_ld_course_rating_css' );
		}

		/**
		 * Rating field for comments.
		 * @param int $comment_id
		 */
		public static function add_comment_rating( $comment_id ) {
			if ( isset( $_POST[ 'rating' ] ) && 'sfwd-courses' === get_post_type( $_POST[ 'comment_post_ID' ] ) ) {
				if ( !$_POST[ 'rating' ] || $_POST[ 'rating' ] > 5 || $_POST[ 'rating' ] < 0 ) {
					return;
				}
				add_comment_meta( $comment_id, 'rating', (int) esc_attr( $_POST[ 'rating' ] ), true );

				if ( isset( $_POST[ 'title' ] ) && !empty( $_POST[ 'title' ] ) ) {
					add_comment_meta( $comment_id, 'title', esc_attr( $_POST[ 'title' ] ), true );
				}

				$post_id = isset( $_POST[ 'comment_post_ID' ] ) ? (int) $_POST[ 'comment_post_ID' ] : 0;
				if ( $post_id ) {
					// self::clear_transients( $post_id );
				}
			}
		}

		/**
		 * Ensure product average rating and review count is kept up to date.
		 * @param int $post_id
		 */
		public static function clear_transients( $post_id ) {

			if ( 'product' === get_post_type( $post_id ) ) {
				$product = wc_get_product( $post_id );
				self::get_rating_counts_for_product( $product );
				self::get_average_rating_for_product( $product );
				self::get_review_count_for_product( $product );
			}
		}

		/**
		 * Validate the comment ratings.
		 *
		 * @param  array $comment_data
		 * @return array
		 */
		public static function check_comment_rating( $comment_data ) {
			// If posting a comment (not trackback etc) and not logged in
			if ( !is_admin() && isset( $_POST[ 'comment_post_ID' ], $_POST[ 'rating' ], $comment_data[ 'comment_type' ] ) && 'sfwd-courses' === get_post_type( $_POST[ 'comment_post_ID' ] ) && empty( $_POST[ 'rating' ] ) && '' === $comment_data[ 'comment_type' ] ) {
				wp_die( __( 'Please rate the course.', 'reign-learndash-addon' ) );
				exit;
			}
			return $comment_data;
		}

		/**
		 * Add EDD Checkout Tab To PeepSo Profile Page.
		 *
		 * @param string $links Links.
		 * @since    1.0.0
		 */
		public function course_comments_template_loader( $template ) {
			if ( get_post_type() !== 'sfwd-courses' ) {
				return $template;
			}
			$template = LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'course-review/single-course-reviews.php';
			return $template;
		}

	}

	endif;

/**
 * Main instance of LD_Course_Review_Manager.
 *
 * @return LD_Course_Review_Manager
 */
LD_Course_Review_Manager::instance();
