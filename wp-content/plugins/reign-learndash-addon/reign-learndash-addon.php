<?php
/**
 * Plugin Name: Reign LearnDash Addon
 * Plugin URI: https://wbcomdesigns.com/
 * Description: Reign LearnDash Addon provides seemless integration of LearnDash with Reign theme.
 * Version: 3.2.0
 * Author: Wbcom Designs
 * Author URI: https://wbcomdesigns.com/
 * Requires at least: 4.0
 * Tested up to: 5.5.1
 *
 * Text Domain: reign-learndash-addon
 * Domain Path: /languages/
 *
 * @package Reign LearnDash Addon
 * @category Core
 * @author Wbcom Designs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Default values
if ( ! defined( 'LEARNDASH_COURSE_GRID_COLUMNS' ) ) {
	define( 'LEARNDASH_COURSE_GRID_COLUMNS', 3 );
}

// Plugin file
if ( ! defined( 'REIGN_LEARNDASH_ADDON_FILE' ) ) {
	define( 'REIGN_LEARNDASH_ADDON_FILE', __FILE__ );
}
if ( ! defined( 'REIGN_LEARNDASH_ADDON_DIR' ) ) {
	define( 'REIGN_LEARNDASH_ADDON_DIR', plugin_dir_path( __FILE__ ) );
}


if ( ! class_exists( 'LearnMate_LearnDash_Addon' ) ) :

	/**
	 * Main LearnMate_LearnDash_Addon Class.
	 *
	 * @class LearnMate_LearnDash_Addon
	 *
	 * @version 1.0.0
	 */
	class LearnMate_LearnDash_Addon {
		/**
		 * LearnMate_LearnDash_Addon version.
		 *
		 * @var string
		 */
		public $version = '3.2.0';

		/**
		 * The single instance of the class.
		 *
		 * @var LearnMate_LearnDash_Addon
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main LearnMate_LearnDash_Addon Instance.
		 *
		 * Ensures only one instance of LearnMate_LearnDash_Addon is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see INSTANTIATE_LearnMate_LearnDash_Addon()
		 * @return LearnMate_LearnDash_Addon - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}


		/**
		 * LearnMate_LearnDash_Addon Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->define_constants();
			$this->init_hooks();
			$this->includes();
			do_action( 'learnmate_learndash_addon_loaded' );
		}

		/**
		 * Hook into actions and filters.
		 *
		 * @since  1.0.0
		 */
		private function init_hooks() {
			register_activation_hook( __FILE__, array( $this, 'reign_ld_default_comment_enable' ) );
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_filter( 'template_include', array( $this, 'include_learndash_templates' ), 10, 1 );
			add_filter( 'learndash_template', array( $this, 'alter_learndash_template' ), 10, 5 );
			add_filter( 'learndash_post_args', array( $this, 'alter_learndash_post_args' ), 10, 1 );

			/*
			 * Adding license panel to theme license section.
			 */
			add_action( 'reign_other_premium_addon_license_panel', array( $this, 'license_panel' ) );
			add_action( 'init', array( $this, 'define_learndash_plugin_version' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'reign_ld_enqueue_admin_scripts' ) );

		}

		/**
		 * Added comment in LD default option val.
		 *
		 * @since  2.0.0
		 */
		public function reign_ld_default_comment_enable() {
			$option_val             = array();
			$option_val             = get_option( 'learndash_settings_courses_cpt' );
			$option_val['supports'] = array( 'thumbnail', 'revisions', 'comments' );
			update_option( 'learndash_settings_courses_cpt', $option_val );

		}

		public function license_panel() {
			include_once 'edd-license/edd-plugin-license.php';
			echo '<div class="reign_support_faq" style="margin-top:20px;">';
				Reign_Learndash_Addon_edd_license_page();
			echo '</div>';
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @since  1.0.0
		 */
		public function includes() {

			include_once 'edd-license/edd-plugin-license.php';

			include_once 'general/reign-learndash-functions.php';
			include_once 'general/reign-learndash-fontawesome-icons.php';
			include_once 'general/class-reign-ld-theme-ld30-hook.php';
			include_once 'general/class-reign-ld-theme-hook.php';
			include_once 'general/class-reign-ld-course-tabs-data.php';
			include_once 'general/class-reign-ld-lesson-customization.php';
			include_once 'general/class-grid-layout-shortcode.php';
			include_once 'general/class-rtm-ld-buy-course-handler.php';
			include_once 'general/class-reign-learndash-theme-settings.php';
			include_once 'general/class-reign-ld-course-list-customization.php';
			include_once 'coming-soon/class-ld-coming-soon-backend-manager.php';
			include_once 'course-review/class-ld-course-review-manager.php';
			include_once 'related-courses/class-ld-related-courses-handler.php';

			if ( class_exists( 'BuddyPress' ) ) {
				include_once 'buddypress/class-reign-learndash-buddypress-addon.php';
				include_once 'buddypress/class-budyypress-learndash-activity.php';
			}

		}

		public function alter_learndash_template( $filepath, $name, $args, $echo, $return_file_path ) {
			$file = 'templates' . '/' . $name . '.php';
			if ( isset( $file ) && file_exists( learnmate_locate_template( $file ) ) ) {
				$filepath = learnmate_locate_template( $file );
			}
			return $filepath;
		}

		public function alter_learndash_post_args( $post_args ) {
			if ( isset( $post_args['sfwd-courses']['taxonomies']['ld_course_category']['public'] ) ) {
				$post_args['sfwd-courses']['taxonomies']['ld_course_category']['public'] = true;
			}
			if ( isset( $post_args['sfwd-courses']['taxonomies']['ld_course_tag']['public'] ) ) {
				$post_args['sfwd-courses']['taxonomies']['ld_course_tag']['public'] = true;
			}
			return $post_args;
		}

		public function include_learndash_templates( $template ) {
			$post_type = 'sfwd-courses';
			$taxonomy  = 'ld_course_category';
			if ( is_post_type_archive( $post_type ) ) {
				$file = "archive-{$post_type}.php";
			} elseif ( is_singular( $post_type ) ) {
				if ( ! defined( 'LEARNDASH_LEGACY_THEME' ) ) {
					$file = "single-{$post_type}.php";
				} else {
					if ( LEARNDASH_LEGACY_THEME === LearnDash_Theme_Register::get_active_theme_key() && is_singular( 'sfwd-courses' ) ) {
						$file = "single-{$post_type}.php";
					}
				}
			}
			if ( is_tax( $taxonomy ) ) {
				$file = "taxonomy-{$taxonomy}.php";
			} elseif ( is_author() ) {
				$file = 'author.php';
			}

			if ( is_singular( 'sfwd-lessons' ) || is_singular( 'sfwd-topic' ) || is_singular( 'sfwd-quiz' ) ) {
				$file = 'single-sfwd-posttypes.php';
			} elseif ( is_singular( 'sfwd-assignment' ) ) {
				$file = 'single-sfwd-assignment.php';
			} elseif ( is_singular( 'sfwd-certificates' ) ) {
				$file = 'single-sfwd-certificates.php';
			}

			if ( isset( $file ) && file_exists( learnmate_locate_template( $file ) ) ) {
				$template = learnmate_locate_template( $file );
			}
			return $template;
		}

		/**
		 * Define LearnMate_LearnDash_Addon Constants.
		 *
		 * @since  1.0.0
		 */
		private function define_constants() {
			$this->define( 'LearnMate_LearnDash_Addon_PLUGIN_FILE', __FILE__ );
			$this->define( 'LearnMate_LearnDash_Addon_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			$this->define( 'LearnMate_LearnDash_Addon_VERSION', $this->version );
			$this->define( 'LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'LearnMate_LearnDash_Addon_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string      $name Define constant name.
		 * @param  string|bool $value Define constant value.
		 * @since  1.0.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Load Localization files.
		 *
		 * @since  1.0.0
		 */
		public function load_plugin_textdomain() {
			$locale = apply_filters( 'reign_learndash_addon_plugin_locale', get_locale(), 'reign-learndash-addon' );
			load_textdomain( 'reign-learndash-addon', LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'language/learnmate-learndash-addon-' . $locale . '.mo' );
			load_plugin_textdomain( 'reign-learndash-addon', false, plugin_basename( dirname( __FILE__ ) ) . '/language' );
		}

		public function define_learndash_plugin_version() {
			if ( defined( 'LEARNDASH_VERSION' ) ) {
				$this->define( 'Leandash_Version', LEARNDASH_VERSION );
			}
		}
		public function reign_ld_enqueue_admin_scripts() {
			wp_enqueue_script( 'jquery' );
			if ( ! wp_style_is( 'reign-learndash-admin-css', 'enqueued' ) ) {
				wp_enqueue_style( 'reign-learndash-admin-css', LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/css/reign-learndash-admin.css', array(), time() );
			}
			if ( ! wp_script_is( 'jquery-ui-dialog', 'enqueued' ) ) {
				wp_enqueue_script( 'jquery-ui-dialog' );
			}
			if ( ! wp_style_is( 'wp-jquery-ui-dialog', 'enqueued' ) ) {
				wp_enqueue_style( 'wp-jquery-ui-dialog' );
			}

			if ( ! wp_script_is( 'reign-learndash-pagination', 'enqueued' ) ) {
				wp_enqueue_script( 'reign-learndash-pagination', LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/js/pagination.min.js', array( 'jquery' ), time() );
			}
			if ( ! wp_script_is( 'reign-learndash-admin', 'enqueued' ) ) {
				wp_enqueue_script( 'reign-learndash-admin', LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/js/reign-learndash-admin.js', array( 'jquery' ), time() );
				wp_localize_script(
					'reign-learndash-admin',
					'ReignLdObj',
					array(
						'ajaxurl'            => admin_url( 'admin-ajax.php', is_ssl() ? 'admin' : 'http' ),
						'dialog_ok_text'     => esc_html__( 'Proceed', 'reign-learndash-addon' ),
						'dialog_cancel_text' => esc_html__( 'Cancel', 'reign-learndash-addon' ),
						'sync_text'          => esc_html__( 'Sync', 'reign-learndash-addon' ),
						'completed_text'     => esc_html__( 'Completed', 'reign-learndash-addon' ),
					)
				);
			}
		}

	}

endif;

/**
 * Main instance of LearnMate_LearnDash_Addon.
 *
 * Returns the main instance of LearnMate_LearnDash_Addon to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return LearnMate_LearnDash_Addon
 */
function instantiate_learnmate_learndash_addon() {
	return LearnMate_LearnDash_Addon::instance();
}


/**
* Check if learndash active or not.
*/
if ( ! function_exists( 'learndash_plugin_activation_check' ) ) {
	add_action( 'plugins_loaded', 'learndash_plugin_activation_check' );
	function learndash_plugin_activation_check() {
		if ( ! class_exists( 'SFWD_LMS' ) ) {
			add_action( 'admin_notices', 'reign_learndash_plugin_not_activated' );
		} else {
			// Global for backwards compatibility.
			$GLOBALS['learnmate_learndash_addon'] = instantiate_learnmate_learndash_addon();
		}
	}
}

/**
 * Admin notice for reign learndash addon.
 *
 * @return [type] [description]
 */
if ( ! function_exists( 'reign_learndash_plugin_not_activated' ) ) {
	function reign_learndash_plugin_not_activated() {
		$learndash_addon_plugin = __( 'Reign LearnDash Addon', 'reign-learndash-addon' );
		$learndash_plugin       = __( 'LearnDash', 'reign-learndash-addon' );

		echo '<div class="error"><p>'
		. sprintf( __( '%1$s is ineffective as it requires %2$s to be installed and active.', 'reign-learndash-addon' ), '<strong>' . $learndash_addon_plugin . '</strong>', '<strong>' . $learndash_plugin . '</strong>' )
		. '</p></div>';
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

/**
 * Check if reign theme is activated or not.
 */
if ( ! function_exists( 'reign_learndash_theme_activate_check' ) ) {
	add_action( 'admin_init', 'reign_learndash_theme_activate_check' );
	function reign_learndash_theme_activate_check() {
		$theme = wp_get_theme(); // gets the current theme
		if ( 'reign-theme' != $theme->Template ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			add_action( 'admin_notices', 'reign_learndash_theme_not_activated' );
			unset( $_GET['activate'] );
		}
	}
}


/**
 * Admin notice display if reign theme is not activated.
 */
if ( ! function_exists( 'reign_learndash_theme_not_activated' ) ) {
	function reign_learndash_theme_not_activated() {
		$reign           = __( 'Reign', 'reign-learndash-addon' );
		$learndash_addon = __( 'Reign LearnDash Addon', 'reign-learndash-addon' );
		echo '<div class="error"><p>'
			. sprintf( __( '%1$s is not activated please activate the theme. %2$s is work with %1$s theme.', 'reign-learndash-addon' ), '<strong>' . $reign . '</strong>', '<strong>' . $learndash_addon . '</strong>' )
			. '</p></div>';
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

add_action( 'pre_get_posts', 'reign_learndash_ld_course_list_query_args', 99 );
function reign_learndash_ld_course_list_query_args( $query ) {

	if ( is_author() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sfwd-courses' ) {
		$author_id             = get_query_var( 'author' );
		$_GET['course_instid'] = $author_id;
		add_filter( 'posts_clauses', 'reign_learndash_course_posts_clauses', 99 );
	}

	if ( isset( $_GET['course_catid'] ) && $_GET['course_catid'] != '' ) {
		$taxonomy_query = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'ld_course_category',
				'field'    => 'term_id',
				'terms'    => intval( $_GET['course_catid'] ),
			),
		);
		$query->set( 'tax_query', $taxonomy_query );
	}
	if ( isset( $_GET['course_instid'] ) && $_GET['course_instid'] != '' ) {
		add_filter( 'posts_clauses', 'reign_learndash_course_posts_clauses', 99 );

	}
	if ( isset( $_GET['course_instid'] ) && isset( $_GET['course_catid'] ) ) {
		add_filter( 'theme_mod_reign_search_page_layout', 'reign_learndash_theme_mod_reign_search_page_layout', 99 );

		add_filter( 'theme_mod_reign_search_header_image', 'reign_learndash_theme_mod_reign_search_header_image', 99 );
		add_filter( 'theme_mod_reign_search_header_enable', 'reign_learndash_theme_mod_reign_search_header_enable', 99 );
		add_filter( 'reign_set_sidebar_id', 'reign_learndash_reign_set_sidebar_id' );
		add_filter( 'reign_set_left_sidebar_id', 'reign_learndash_reign_set_left_sidebar_id' );
	}

}

function reign_learndash_theme_mod_reign_search_header_enable( $mods ) {

	if ( is_search() && get_post_type() == 'sfwd-courses' ) {
		$post_type = get_post_type();
		$mods      = get_theme_mod( 'reign_' . $post_type . '_archive_header_enable' );
	}
	return $mods;
}

function reign_learndash_theme_mod_reign_search_header_image( $mods ) {
	if ( is_search() && get_post_type() == 'sfwd-courses' ) {
		$post_type = get_post_type();
		$mods      = get_theme_mod( 'reign_' . $post_type . '_archive_header_image' );
	}
	return $mods;
}
/*
 * Set Sidebar mod when course search page active
 */
function reign_learndash_theme_mod_reign_search_page_layout( $mods ) {
	if ( is_search() && get_post_type() == 'sfwd-courses' ) {
		$post_type = get_post_type();
		$mods      = get_theme_mod( 'reign_' . $post_type . '_archive_layout', 'right_sidebar' );
	}
	return $mods;
}
function reign_learndash_reign_set_left_sidebar_id( $sidebar_id ) {
	$post_type = get_post_type();
	if ( $post_type == 'sfwd-courses' && is_search() ) {
		$active_content_layout = get_theme_mod( 'reign_' . $post_type . '_archive_layout', 'right_sidebar' );

		if ( ( $active_content_layout == 'both_sidebar' ) || ( $active_content_layout == 'left_sidebar' ) ) {
			$sidebar_id = get_theme_mod( 'reign_' . $post_type . '_archive_left_sidebar', '' );
		}
	}
	return $sidebar_id;
}
function reign_learndash_reign_set_sidebar_id( $sidebar_id ) {

	$post_type = get_post_type();
	if ( $post_type == 'sfwd-courses' && is_search() ) {
		$active_content_layout = get_theme_mod( 'reign_' . $post_type . '_archive_layout', 'right_sidebar' );

		if ( ( $active_content_layout == 'both_sidebar' ) || ( $active_content_layout == 'right_sidebar' ) ) {
			$sidebar_id = get_theme_mod( 'reign_' . $post_type . '_archive_right_sidebar', '' );
		}
	}
	return $sidebar_id;
}

add_action( 'wp_head', 'remove_pre_get_posts_clauses', 99 );
function remove_pre_get_posts_clauses() {
	remove_action( 'pre_get_posts', 'reign_learndash_ld_course_list_query_args', 99 );
}

function reign_learndash_course_posts_clauses( $clauses ) {
	global $wpdb;
	if ( is_author() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sfwd-courses' ) {
		$author_id             = get_query_var( 'author' );
		$_GET['course_instid'] = $author_id;
	}
	if ( isset( $_GET['course_instid'] ) && $_GET['course_instid'] != '' ) {
		$clauses['join'] .= " INNER JOIN {$wpdb->prefix}postmeta pm6 ON ( {$wpdb->prefix}posts.ID = pm6.post_id ) INNER JOIN {$wpdb->prefix}postmeta pm7 ON ( {$wpdb->prefix}posts.ID = pm7.post_id ) ";

		$where = ' ' . $clauses['where'] . " AND {$wpdb->prefix}posts.post_author = {$_GET['course_instid']} ";

		$clauses['where'] = str_replace( "AND ({$wpdb->prefix}posts.post_author = " . $_GET['course_instid'] . ')', '', $clauses['where'] );

		$clauses['where'] = $where . " OR ( (  (pm6.meta_key = '_ld_instructor_ids' AND pm6.meta_value REGEXP '.*;s:[0-9]+:\"{$_GET['course_instid']}\"*' ) OR (pm7.meta_key = 'ir_shared_instructor_ids' AND  FIND_IN_SET ({$_GET['course_instid']}, pm7.meta_value)) ) {$clauses['where']}  )";

		$clauses['groupby'] = " {$wpdb->prefix}posts.ID";

		remove_filter( 'posts_clauses', 'reign_learndash_course_posts_clauses', 99 );
	}

	return $clauses;
}


function reign_learndash_wbcom_before_content_section() {
	if ( is_author() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sfwd-courses' ) {
		global $wpdb, $wbtm_reign_settings;

		$author_id = get_query_var( 'author' );
		if ( ! isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) ) {
			$course_sql = "SELECT SQL_CALC_FOUND_ROWS {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta pm6 ON ( {$wpdb->prefix}posts.ID = pm6.post_id ) INNER JOIN {$wpdb->prefix}postmeta pm7 ON ( {$wpdb->prefix}posts.ID = pm7.post_id ) WHERE 1=1 AND {$wpdb->prefix}posts.post_type = 'sfwd-courses' AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'graded' OR {$wpdb->prefix}posts.post_status = 'not_graded' OR {$wpdb->prefix}posts.post_status = 'private') AND {$wpdb->prefix}posts.post_author = {$author_id} OR ( ( (pm6.meta_key = '_ld_instructor_ids' AND pm6.meta_value REGEXP '.*;s:[0-9]+:\"{$author_id}\"*') or (pm7.meta_key = 'ir_shared_instructor_ids' AND  FIND_IN_SET ({$author_id}, pm7.meta_value))  ) AND {$wpdb->prefix}posts.post_type = 'sfwd-courses' AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'graded' OR {$wpdb->prefix}posts.post_status = 'not_graded' OR {$wpdb->prefix}posts.post_status = 'private') ) GROUP BY {$wpdb->prefix}posts.ID";

			$course_results        = $wpdb->get_results( $course_sql );
			$total_comment         = 0;
			$total_reviews_average = 0;
			$total_reviews         = 0;

			if ( ! empty( $course_results ) ) {
				foreach ( $course_results as $course ) {
					$args                     = array(
						'post_id' => $course->ID, // use post_id, not post_ID
					);
					$total_comment           += count( get_comments( $args ) );
					$course_reviews_analytics = wb_ld_get_course_reviews_analytics( $course->ID );
					foreach ( $course_reviews_analytics['reviews_analytics'] as $key => $value ) {
						if ( $key == '5star' ) {
							$total_reviews += 5 * $value['count'];
						}
						if ( $key == '4star' ) {
							$total_reviews += 4 * $value['count'];
						}
						if ( $key == '3star' ) {
							$total_reviews += 3 * $value['count'];
						}
						if ( $key == '2star' ) {
							$total_reviews += 2 * $value['count'];
						}
						if ( $key == '1star' ) {
							$total_reviews += 1 * $value['count'];
						}
					}
				}
			}
			$total_reviews_average = ( $total_reviews != 0 && $total_comment != 0 ) ? round( ( $total_reviews / $total_comment ), 1 ) : 0;

		}

		$first_name         = get_the_author_meta( 'user_firstname', $author_id );
		$last_name          = get_the_author_meta( 'user_lastname', $author_id );
		$author_name        = get_the_author_meta( 'display_name', $author_id );
		$_ld_instructor_ids = get_post_meta( $course->ID, '_ld_instructor_ids', true );

		if ( ! empty( $first_name ) ) {
			$author_name = $first_name . ' ' . $last_name;
		}
		$author_avatar_url  = get_avatar_url( $author_id );
		$author_description = get_the_author_meta( 'description', $author_id );

		$social_links_list = array();
		$email             = get_the_author_meta( 'email', $author_id );
		if ( ! empty( $email ) ) {
			$social_links_list[] = array(
				'title'      => esc_html__( 'Email', 'reign-learndash-addon' ),
				'link'       => 'mailto:' . $email,
				'icon_class' => 'fa fa-envelope',
			);
		}

		$url = get_the_author_meta( 'url', $author_id );
		if ( ! empty( $url ) ) {
			$social_links_list[] = array(
				'title'      => esc_html__( 'Website', 'reign-learndash-addon' ),
				'link'       => $url,
				'icon_class' => 'fa fa-link',
			);
		}

		if ( defined( 'WPSEO_VERSION' ) ) {
			$twitter = get_the_author_meta( 'twitter', $author_id );
			if ( ! empty( $twitter ) ) {
				$social_links_list[] = array(
					'title'      => esc_html__( 'Twitter', 'reign-learndash-addon' ),
					'link'       => $twitter,
					'icon_class' => 'fa fa-twitter',
				);
			}

			$facebook = get_the_author_meta( 'facebook', $author_id );
			if ( ! empty( $facebook ) ) {
				$social_links_list[] = array(
					'title'      => esc_html__( 'Facebook', 'reign-learndash-addon' ),
					'link'       => $facebook,
					'icon_class' => 'fa fa-facebook',
				);
			}
		}

		?>
		<div class="lm-site-header-section">
			<div class="reign-learndash-author-info">
				<div class="container">
					<div class="lm-course-author-info-tab">
						<div class="lm-course-author lm-course-author-avatar" itemscope="" itemtype="http://schema.org/Person">
							<img alt="instructor avatar" src="<?php echo esc_url( $author_avatar_url ); ?>" class="lm-author-avatar" width="150" height="150">
						</div>
						<div class="lm-author-bio">
							<div class="lm-author-top">
								<h4 class="lm-author-title"><?php echo $author_name; ?></h4>
								<?php if ( ! isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) ) { ?>
								<div class="learndash-rating-box">
									<div class="wb-ld-average-value" itemprop="ratingValue"><?php echo $total_reviews_average; ?></div>
									<div class="wb-ld-review-stars-rated"></div>
									<div class="review-amount" itemprop="ratingCount">
									<?php
									if ( $total_comment <= 1 ) {
										echo '(' . $total_comment . __( ' rating', 'reign-learndash-addon' ) . ')';
									} else {
										echo '(' . $total_comment . __( ' ratings', 'reign-learndash-addon' ) . ')';
									}
									?>
									</div>
								</div>
								<?php } ?>
							</div>
							<ul class="lm-author-social">
								<?php
								foreach ( $social_links_list as $key => $social_link ) {
									?>
									<li>
										<a href="<?php echo $social_link['link']; ?>"><i class="<?php echo $social_link['icon_class']; ?>"></i></a>
									</li>
									<?php
								}
								?>
							</ul>
						</div>
						<div class="lm-author-description">
								<?php echo $author_description; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php

	}
}

register_activation_hook( __FILE__, 'reign_ld_default_reign_theme_option' );
function reign_ld_default_reign_theme_option() {
	$wbtm_reign_settings['learndash'] = array(
		'course_layout'            => 'default',
		'course_category_filter'   => 'on',
		'course_instructor_filter' => 'on',
		'enable_related_courses'   => 'enable',
		'title_related_courses'    => 'Related Courses',
		'num_of_related_courses'   => 3,
	);
			update_option( 'reign_options', $wbtm_reign_settings );
}
