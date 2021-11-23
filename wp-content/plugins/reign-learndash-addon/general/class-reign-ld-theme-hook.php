<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( !class_exists( 'Reign_LD_Theme_Hook' ) ) :

	/**
	 * @class Reign_LD_Theme_Hook
	 */
	class Reign_LD_Theme_Hook {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_LD_Theme_Hook
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_LD_Theme_Hook Instance.
		 *
		 * Ensures only one instance of Reign_LD_Theme_Hook is loaded or can be loaded.
		 *
		 * @return Reign_LD_Theme_Hook - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_LD_Theme_Hook Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'template_redirect', array( $this, 'remove_learndash_template_content' ) );

			add_action( 'widgets_init', array( $this, 'ld_register_widgets' ), 11 );
			add_action( 'widgets_init', array( $this, 'reign_learndash_widget_area_support' ) );

			//add_filter( 'reign_set_sidebar_id', array( $this, 'set_courses_archive_sidebar' ) );
			//add_filter( 'reign_sidebar_id_for_left_sidebar', array( $this, 'set_courses_archive_sidebar' ) );

			$ld_post_types = array(
				'sfwd-quiz',
				'sfwd-assignment',
				'sfwd-certificates',
			);
			foreach ( $ld_post_types as $post_type ) {
				add_filter( 'reign_' . $post_type . '_single_header_image', array( $this, 'single_header_image' ), 10, 2 );
				add_filter( 'reign_' . $post_type . '_archive_header_image', array( $this, 'archive_header_image' ), 10, 1 );
			}

			add_filter( 'reign_customizer_supported_post_types', array( $this, 'add_post_type' ), 10, 1 );

			add_action( 'customize_register', array( $this, 'ld_post_types_customize_option_remove' ), 20, 1 );

			add_action( 'wp_enqueue_scripts', array( $this, 'learndash_enqueue_scripts' ), 9999 );

			add_action( 'reign_breadcrumbs', array( $this, 'learnmate_ld_breadcrumbs' ) );

			add_filter( 'alter_reign_breadcrumbs', array( $this, 'modify_reign_breadcrumbs' ), 10, 1 );

			add_action( 'body_class', array( $this, 'manage_ld_profile_expand' ), 10, 1 );

			add_filter( 'reign_selector_set_to_apply_theme_color', array( $this, 'apply_theme_color_to_text' ), 10, 1 );

			add_filter( 'reign_selector_set_to_apply_theme_color_to_background', array( $this, 'apply_theme_color_to_background' ), 10, 1 );

			/* to apply section background to selected selectors for addon */
			add_filter( 'reign_selector_set_to_apply_section_bg_color', array( $this, 'apply_section_bg_color' ), 10, 1 );

			/* to apply border to selected border color for addon */
			add_filter( 'reign_selector_set_to_apply_border_color', array( $this, 'apply_border_color' ), 10, 1 );

			add_filter( 'lm_filter_course_author_url', array( $this, 'lm_filter_course_author_url' ), 10, 1 );

			add_filter( 'reign_page_header_section_title', array( $this, 'reign_page_header_section_title' ), 10, 1 );
		}

		public function ld_post_types_customize_option_remove( $wp_customize ) {
			$wp_customize->remove_section( 'reign_sfwd-lessons_archive' );
			$wp_customize->remove_section( 'reign_sfwd-topic_archive' );
		}

		public function reign_page_header_section_title( $title ) {
			if ( is_author() && isset( $_GET[ 'post_type' ] ) ) {
				$author_id = get_query_var( 'author' );
				if ( $author_id ) {
					$author = get_user_by( 'id', $author_id );
					if ( !empty( get_user_meta( $author_id, 'first_name', true ) ) ) {
						$author_name = get_user_meta( $author_id, 'first_name', true ) . '\'s ';
					} else {
						$author_info = get_userdata( $author_id );
						$author_name = $author_info->data->user_login . '\'s ';
					}
					$title = $author_name . __( 'Courses', 'reign-learndash-addon' );
				}
			}
			return $title;
		}

		public function lm_filter_course_author_url( $author_url ) {
			$author_url .= '?post_type=sfwd-courses';
			return $author_url;
		}

		public function apply_theme_color_to_background( $selector_for_background ) {
			if ( !is_plugin_active( 'design-upgrade-pro-learndash/design-upgrade-pro-learndash.php' ) ) {
				$selector_for_background .= '.lm-course-item-wrapper a.lm-course-readmore-button, .lm-course-pagination-section .page-numbers.current, .lm-course-item-wrapper .lm-course-item .lm-course-content .lm-course-meta .btn-join, .lm-course-item-wrapper .lm-course-item .lm-course-content .lm-course-meta #btn-join.btn-join, div#lm-course-archive-data.lm-grid-view .lm-course-meta:before, .lm-course-tabs-wrapper ul.lm-coure-tabs li.active:before, dd.course_progress div.course_progress_blue, .lm-course-content .lm-course-progress-bar dd.course_progress div.course_progress_blue, ul.lm-author-social li a:hover, .widget_ldcoursenavigation .widget_course_return a, #uploadfile_form input[type="submit"]#uploadfile_btn, #learndash_next_prev_link a.prev-link, #learndash_next_prev_link a.next-link, #learndash_back_to_lesson a:hover, .lm-distraction-free-toggle, #learndash_profile #learndash_course_points_user_message, #ld_course_list .ld_course_grid  .btn-primary, .lm-distraction-free-reading .lm-dfr-sticky, .reign-ld-course-overview-widget .rtm-lm-course-overview-actions .overview-btn, .reign-ld-course-info .reign-ld-course-thumbnail .thumb-overlay, .learndash-wrapper .ld-focus .ld-focus-header .ld-mobile-nav span';
			}
			return $selector_for_background;
		}

		public function apply_section_bg_color( $selector_for_section_bg ) {
			if ( !is_plugin_active( 'design-upgrade-pro-learndash/design-upgrade-pro-learndash.php' ) ) {
				$selector_for_section_bg .= ', .lm-course-top.lm-course-top.switch-layout-container, #lm-course-archive-data.lm-list-view .lm-course-item-wrapper, .lm-course-item-wrapper .lm-course-item, .single-sfwd-courses .lm-course-item-wrapper .lm-course-item .lm-course-content .lm-course-meta, .lm-course-pagination-section .page-numbers, .lm-course-tabs-wrapper ul.lm-coure-tabs, .lm-tab-content-wrapper .lm-tab-content.active, pre, code, .learndash-pager.learndash-pager a, #learndash_lessons #lesson_heading, #learndash_profile .learndash_profile_heading, #learndash_quizzes #quiz_heading, #learndash_lesson_topics_list div > strong, #course_list > div:nth-child(2n), .lm-distraction-free-reading .header_popup, .lm-distraction-free-reading .lm-board-popup-main .footer_popup, .learndash .learndash_topic_dots.type-dots, table#leardash_upload_assignment, .learndash_lesson_materials h4, .lm-board-popup-sidebar .lm-board-popup-course-navigation.lm-board-popup-course-navigation ul li.learnmate-current-menu-item, .learndash.learndash .learndash_topic_dots.type-dots, .learndash-wrapper .ld-profile-summary, .learndash-wrapper .ld-item-list .ld-item-list-item, .learndash-wrapper .ld-item-list .ld-item-list-item .ld-item-list-item-expanded:before, .learndash-wrapper .ld-tabs .ld-tabs-navigation, .learndash-wrapper .ld-tabs .ld-tabs-content, .learndash-wrapper .ld-breadcrumbs, .learndash-wrapper .ld-pagination .ld-pages, .learndash-wrapper .ld-file-upload, .learndash-wrapper .ld-table-list .ld-table-list-footer, .learndash-wrapper .ld-topic-status, .wpProQuiz_content .wpProQuiz_listItem, .wpProQuiz_content .wpProQuiz_addToplist, .learndash-wrapper .wpProQuiz_content .wpProQuiz_response, .learndash_content, .learndash_uploaded_assignments table tr:nth-child(2n+1), .learndash-wrapper .ld-focus .ld-focus-header, .learndash-wrapper .ld-focus .ld-focus-header .ld-brand-logo, .learndash-wrapper .ld-focus .ld-focus-sidebar, .learndash-wrapper .ld-focus .ld-focus-sidebar .ld-course-navigation .ld-lesson-item, .learndash-wrapper .ld-focus .ld-focus-sidebar .ld-course-navigation .ld-topic-list.ld-table-list:before, .learndash-wrapper .ld-course-status.ld-course-status-enrolled, .learndash-wrapper .ld-course-status.ld-course-status-not-enrolled';
			} else {
				$selector_for_section_bg .= ", .learndash-wrapper .ld-profile-summary, .learndash-wrapper .ld-tabs .ld-tabs-navigation .ld-tab.ld-active, .learndash-wrapper .ld-tabs .ld-tabs-content";
			}
			return $selector_for_section_bg;
		}

		public function apply_border_color( $selector_for_border_color ) {
			if ( !is_plugin_active( 'design-upgrade-pro-learndash/design-upgrade-pro-learndash.php' ) ) {
				$selector_for_border_color .= ', .lm-course-top.switch-layout-container, .lm-course-switch-layout.switch-layout, .lm-course-switch-layout.switch-layout a:first-child, .switch-layout-container .courses-searching.courses-searching form input[type=search], .lm-course-item-wrapper .lm-course-item.lm-course-item, div#lm-course-archive-data.lm-grid-view .thumb_inactive .lm-course-content, .lm-course-item-wrapper .lm-course-item .lm-course-content .lm-course-meta.lm-course-meta > div, .lm-tab-content.lm-tab-content, .single-sfwd-courses .lm-course-item-wrapper .lm-course-item .lm-course-content .lm-course-meta, .lm-course-tabs-wrapper ul.lm-coure-tabs.lm-coure-tabs li a, .lm-course-tabs-wrapper ul.lm-coure-tabs.lm-coure-tabs, .lm-tab-content.lm-tab-content-description .lm-tab-course-content.lm-tab-course-content, .lm-tab-content.lm-tab-content-description .lm-tab-course-info.lm-tab-course-info ul li, ul.lm-author-social.lm-author-social, .site #learndash_course_content #lm-lesson-heading, .site #lm-learndash-quizzes #lm-quiz-heading, .site #learndash_course_materials li a, .learndash_course_content ul.lm-topics-list.lm-topics-list li.lm-topic, .learndash_course_content ul.lm-topics-list.lm-topics-list li.lm-topic:last-child, ul.lm-author-social.lm-author-social li a, .rating-box.rating-box, .reign-learndash-Reviews ul.wb-ld-course-commentlist.wb-ld-course-commentlist li, .lm-ld-course-item-single.lm-course-item-wrapper .lm-course-item .lm-course-content .lm-course-meta.lm-course-meta > div, .lm-board-popup-sidebar .learndash_course_content ul.lm-course-hierarchy.lm-course-hierarchy li, .lm-board-popup-sidebar .lm-quiz-list-wrap ul.lm-quiz-list.lm-quiz-list li, .lm-distraction-free-reading.lm-distraction-free-reading .lm-board-popup-course-navigation, pre, code, .site .learndash_lesson_materials, .site #learndash_lessons, .site #learndash_quizzes, .site #learndash_profile, .site #learndash_lesson_topics_list > div, body #learndash_lesson_topics_list div > strong, .site #lessons_list > div h4, .site #course_list > div h4, .site #quiz_list > div h4, .site #learndash_lesson_topics_list ul > li > span.topic_item, .site #lessons_list > div > div, .site #course_list > div > div, .site #quiz_list > div > div, .site .single-sfwd-lessons #learndash_lesson_topics_list ul > li > span.sn, .site .singular-sfwd-lessons #learndash_lesson_topics_list ul > li > span.sn, .learndash-wrapper .ld-profile-summary .ld-profile-stats .ld-profile-stat, .learndash-wrapper .ld-item-list .ld-item-list-item, .learndash-wrapper .ld-content-actions, .learndash-wrapper .ld-table-list .ld-table-list-items, .wpProQuiz_content .wpProQuiz_listItem, .learndash-wrapper .ld-quiz-actions, .learndash-wrapper .wpProQuiz_content .wpProQuiz_response, .learndash-wrapper .ld-table-list .ld-table-list-item, .learndash-wrapper .ld-table-list.ld-no-pagination, .learndash-wrapper .ld-focus .ld-focus-header, .learndash-wrapper .ld-focus .ld-focus-header .ld-progress, .learndash-wrapper .ld-focus .ld-focus-header .ld-content-action, .learndash-wrapper .ld-focus .ld-focus-sidebar .ld-course-navigation .ld-lesson-item, .learndash-wrapper .ld-focus .ld-focus-sidebar, .lm-tab-course-content-data .lm-tab-course-content.lm-tab-course-content, .lm-tab-course-content-data .lm-tab-course-info.lm-tab-course-info ul li, .learndash-wrapper .ld-tabs .ld-tabs-navigation, .learndash-wrapper .ld-tabs.ld-tabs .ld-tabs-content, .learndash-wrapper .ld-tabs.ld-tabs .ld-tabs-navigation:after, .learndash-wrapper .ld-focus .ld-focus-header .ld-user-menu, .learndash-wrapper .ld-focus .ld-focus-header .ld-content-actions';
			}
			return $selector_for_border_color;
		}

		public function apply_theme_color_to_text( $selector_for_color ) {
			$selector_for_color .= ' .lm-course-switch-layout.switch-layout a.switch-active, .lm-course-item-wrapper .lm-course-item .lm-course-content .lm-course-meta ul.lm-review-stars.lm-filled, .lm-course-tabs-wrapper ul.lm-coure-tabs li.active a i, .lm-tab-content.lm-tab-content-description .lm-tab-course-info ul li i, .lm-learndash-quizzes ul.lm-quiz-list li i, .learndash_course_content li.lm-lesson span.lm-topics-toggle, .learndash_course_content ul.lm-topics-list li.lm-topic .topic_item .lm-topic-meta-left i, .learndash_course_content ul.lm-topics-list li.lm-topic .topic_item .lm-topic-meta-right i, .learndash_course_content ul.lm-topics-list li.lm-topic.quiz-item i, .learndash_lesson_materials ul li:before, #learndash_quizzes .quiz_list > div > h4 a:after, #course_list .learndash-course-status a:after, #learndash_back_to_lesson a, .learndash_lesson_topics_list .type-list .topic_item .topic-notcompleted span:before, .learndash_course_content  ul.lm-course-hierarchy li .lm-lesson-section-header a.notcompleted:after, .widget_reign_ld_course_categories ul li:before, .widget_sfwd-certificates-widget ul li:before, .widget_sfwd-courses-widget ul li:before, .widget_sfwd-lessons-widget ul li:before, .widget_sfwd-quiz-widget ul li:before, .widget_ldcoursenavigation .learndash_navigation_lesson_topics_list .list_arrow.collapse:before, .widget_ldcoursenavigation .learndash_navigation_lesson_topics_list .list_arrow.expand:before, .widget_ldcoursenavigation .learndash_topic_widget_list .topic_item .topic-notcompleted span:before';
			return $selector_for_color;
		}

		public function manage_ld_profile_expand( $classes ) {
			global $wbtm_reign_settings;
			$ld_profile_layout = isset( $wbtm_reign_settings[ 'learndash' ][ 'ld_profile_layout' ] ) ? $wbtm_reign_settings[ 'learndash' ][ 'ld_profile_layout' ] : 'collapsed';
			if ( 'expanded' === $ld_profile_layout ) {
				$classes[] = 'lm-ld-profile-expanded';
			}
			return $classes;
		}

		public function remove_learndash_template_content() {
			global $sfwd_lms, $post;
			if ( !is_a( $post, 'WP_Post' ) ) {
				return;
			}
			/* Date: 2020-09-16 LEGACY Code Comment
			if ( 'sfwd-courses' === $post->post_type ) {
				if ( !defined( 'LEARNDASH_LEGACY_THEME' ) ) {
					remove_filter( 'the_content', array( $sfwd_lms->post_types[ 'sfwd-courses' ], 'template_content' ), 1000 );
				} else {
					if ( LEARNDASH_LEGACY_THEME === LearnDash_Theme_Register::get_active_theme_key() ) {
						remove_filter( 'the_content', array( $sfwd_lms->post_types[ 'sfwd-courses' ], 'template_content' ), 1000 );
					}
				}
			}
			*/
		}

		public function ld_register_widgets() {
			$list_of_ld_widgets = array(
				'class-reign-ld-widget-course-categories.php'	 => 'Reign_LD_Widget_Course_Categories',
				'class-reign-ld-widget-course-search.php'		 => 'Reign_LD_Widget_Course_Search',
				'class-reign-ld-widget-course-listing.php'		 => 'Reign_LD_Widget_Course_Listing',
			);
			foreach ( $list_of_ld_widgets as $file_name => $class_name ) {
				include_once LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'ld-widgets/' . $file_name;
				register_widget( $class_name );
			}
		}

		public function reign_learndash_widget_area_support() {
			register_sidebar( array(
				'name'			 => sprintf( esc_html_x( '%s Archive Sidebar', 'Course Archive Sidebar Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'course' ) ),
				'id'			 => 'ld-course-archive-sidebar',
				'description'	 => esc_html__( 'Widgets in this area are used in the learndash courses archive page.', 'reign-learndash-addon' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );
			register_sidebar( array(
				'name'			 => sprintf( esc_html_x( 'Single %s Sidebar', 'Single %s Sidebar Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'course' ) ),
				'id'			 => 'ld-single-course-sidebar',
				'description'	 => sprintf( esc_html_x( 'Widgets in this area are used in the learndash single %s page.', 'Single %s Sidebar Description Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'course' ) ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );
			register_sidebar( array(
				'name'			 => sprintf( esc_html_x( 'Single %s Sidebar', 'Single %s Sidebar Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'lesson' ) ),
				'id'			 => 'ld-single-lesson-sidebar',
				'description'	 => sprintf( esc_html_x( 'Widgets in this area are used in the learndash single %s page.', 'Single %s Sidebar Description Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'lesson' ) ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );
			register_sidebar( array(
				'name'			 => sprintf( esc_html_x( 'Single %s Sidebar', 'Single %s Sidebar Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'topic' ) ),
				'id'			 => 'ld-single-topic-sidebar',
				'description'	 => sprintf( esc_html_x( 'Widgets in this area are used in the learndash single %s page.', 'Single %s Sidebar Description Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'topic' ) ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );
		}

		public function set_courses_archive_sidebar( $sidebar_id ) {
			if ( is_post_type_archive( 'sfwd-courses' ) ) {
				$sidebar_id = 'ld-course-archive-sidebar';
			} else if ( is_tax( 'ld_course_category' ) ) {
				$sidebar_id = 'ld-course-archive-sidebar';
			} elseif ( is_singular( 'sfwd-courses' ) ) {
				$sidebar_id = 'ld-single-course-sidebar';
			} elseif ( is_singular( 'sfwd-lessons' ) ) {
				$sidebar_id = 'ld-single-lesson-sidebar';
			} elseif ( is_singular( 'sfwd-topic' ) ) {
				$sidebar_id = 'ld-single-topic-sidebar';
			} elseif ( is_singular( 'sfwd-quiz' ) ) {
				$sidebar_id = 'ld-course-archive-sidebar';
			} elseif ( is_singular( 'sfwd-assignment' ) ) {
				$sidebar_id = 'ld-course-archive-sidebar';
			} elseif ( is_singular( 'sfwd-certificates' ) ) {
				$sidebar_id = 'ld-course-archive-sidebar';
			}
			return $sidebar_id;
		}

		public function single_header_image( $header_banner_image_url, $post_type ) {
			$header_banner_image_url = get_theme_mod( 'reign_' . $post_type . '_single_header_image', '' );
			return $header_banner_image_url;
		}

		public function archive_header_image( $header_banner_image_url ) {
			$post_type				 = 'sfwd-courses';
			$header_banner_image_url = get_theme_mod( 'reign_' . $post_type . '_archive_header_image', '' );
			return $header_banner_image_url;
		}

		public function add_post_type( $post_types ) {
			$post_types[]	 = array(
				'slug'	 => 'sfwd-courses',
				'name'	 => __( 'Courses', 'reign-learndash-addon' ),
				'has_archive' => true,
			);
			$post_types[]	 = array(
				'slug'	 => 'sfwd-lessons',
				'name'	 => __( 'Course Lessons', 'reign-learndash-addon' ),
			);
			$post_types[]	 = array(
				'slug'	 => 'sfwd-topic',
				'name'	 => __( 'Course Topics', 'reign-learndash-addon' ),
			);
			return $post_types;
		}

		public function learndash_enqueue_scripts() {

			wp_register_script(
			$handle		 = 'reign_select2_js', $src		 = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/js/select2.min.js', $deps		 = array( 'jquery' ), $ver		 = time(), $in_footer	 = true
			);
			wp_enqueue_script( 'reign_select2_js' );

			wp_register_script(
			$handle		 = 'reign_learndash_js', $src		 = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/js/learndash.js', $deps		 = array( 'jquery' ), $ver		 = time(), $in_footer	 = true
			);
			wp_localize_script(
			'reign_learndash_js', 'reign_learndash_js_params', array(
				'ajax_url'	 => admin_url( 'admin-ajax.php' ),
				'home_url'	 => get_home_url(),
			)
			);
			wp_enqueue_script( 'reign_learndash_js' );

			$css_path = is_rtl() ? '/assets/css/rtl' : '/assets/css';

			wp_register_style(
			$handle	 = 'reign_select2_css', $src	 = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/css/select2.min.css', $deps	 = array(), $ver	 = time(), $media	 = 'all'
			);
			wp_enqueue_style( 'reign_select2_css' );

			if ( defined( 'LEARNDASH_LEGACY_THEME' ) ) {
				if ( learndash_is_active_theme( 'ld30' ) ) {
					wp_register_style(
					$handle	 = 'reign_learndash_css', $src	 = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . $css_path . '/learndash-ld30.css', $deps	 = array(), $ver	 = time(), $media	 = 'all'
					);
				} else {
					wp_register_style(
					$handle	 = 'reign_learndash_css', $src	 = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . $css_path . '/learndash.css', $deps	 = array(), $ver	 = time(), $media	 = 'all'
					);
				}
			} else {
				wp_register_style(
				$handle	 = 'reign_learndash_css', $src	 = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . $css_path . '/learndash.css', $deps	 = array(), $ver	 = time(), $media	 = 'all'
				);
			}
			wp_enqueue_style( 'reign_learndash_css' );
		}

		public function modify_reign_breadcrumbs( $alter_reign_breadcrumbs ) {
			global $post;
			if ( $post ) {
				if ( is_post_type_archive( 'sfwd-courses' ) ) {
					$alter_reign_breadcrumbs = true;
				} elseif ( 'sfwd-courses' === $post->post_type ) {
					$alter_reign_breadcrumbs = true;
				} elseif ( 'sfwd-lessons' === $post->post_type ) {
					$alter_reign_breadcrumbs = true;
				} elseif ( 'sfwd-topic' === $post->post_type ) {
					$alter_reign_breadcrumbs = true;
				} elseif ( 'sfwd-quiz' === $post->post_type ) {
					$alter_reign_breadcrumbs = true;
				}
			}
			return $alter_reign_breadcrumbs;
		}

		// Breadcrumbs
		public function learnmate_ld_breadcrumbs() {

			$breadcrumb_array = array();

			$breadcrums_id		 = 'breadcrumbs';
			$breadcrums_class	 = 'breadcrumbs rtm-ld-breadcrumbs';
			$separator			 = '<i class="fa fa-angle-double-right"></i>';

			global $post;

			if ( is_post_type_archive( 'sfwd-courses' ) ) {
				$breadcrumb_array[] = array(
					'title'	 => __( 'Courses', 'reign-learndash-addon' ),
					'href'	 => '',
				);
			} elseif ( 'sfwd-courses' === $post->post_type ) {
				$breadcrumb_array[] = array(
					'title'	 => __( 'Courses', 'reign-learndash-addon' ),
					'href'	 => get_post_type_archive_link( 'sfwd-courses' ),
				);

				$course_id			 = $post->ID;
				$breadcrumb_array[]	 = array(
					'title'	 => get_the_title( $course_id ),
					'href'	 => '',
				);
			} elseif ( 'sfwd-lessons' === $post->post_type ) {
				$breadcrumb_array[] = array(
					'title'	 => __( 'Courses', 'reign-learndash-addon' ),
					'href'	 => get_post_type_archive_link( 'sfwd-courses' ),
				);

				$lesson_id	 = $post->ID;
				$assign_meta = get_post_meta( $lesson_id, '_' . $post->post_type, true );
				$course_id	 = (!empty( $assign_meta[ $post->post_type . '_course' ] ) ) ? $assign_meta[ $post->post_type . '_course' ] : 0;

				$breadcrumb_array[]	 = array(
					'title'	 => get_the_title( $course_id ),
					'href'	 => get_permalink( $course_id ),
				);
				$breadcrumb_array[]	 = array(
					'title'	 => get_the_title( $lesson_id ),
					'href'	 => '',
				);
			} elseif ( 'sfwd-topic' === $post->post_type ) {
				$breadcrumb_array[] = array(
					'title'	 => __( 'Courses', 'reign-learndash-addon' ),
					'href'	 => get_post_type_archive_link( 'sfwd-courses' ),
				);

				$topic_id	 = $post->ID;
				$assign_meta = get_post_meta( $topic_id, '_' . $post->post_type, true );
				$course_id	 = (!empty( $assign_meta[ $post->post_type . '_course' ] ) ) ? $assign_meta[ $post->post_type . '_course' ] : 0;
				$lesson_id	 = (!empty( $assign_meta[ $post->post_type . '_lesson' ] ) ) ? $assign_meta[ $post->post_type . '_lesson' ] : 0;

				$breadcrumb_array[]	 = array(
					'title'	 => get_the_title( $course_id ),
					'href'	 => get_permalink( $course_id ),
				);
				$breadcrumb_array[]	 = array(
					'title'	 => get_the_title( $lesson_id ),
					'href'	 => get_permalink( $lesson_id ),
				);
				$breadcrumb_array[]	 = array(
					'title'	 => get_the_title( $topic_id ),
					'href'	 => '',
				);
			} elseif ( 'sfwd-quiz' === $post->post_type ) {
				$breadcrumb_array[] = array(
					'title'	 => __( 'Courses', 'reign-learndash-addon' ),
					'href'	 => get_post_type_archive_link( 'sfwd-courses' ),
				);

				$quiz_id	 = $post->ID;
				$assign_meta = get_post_meta( $quiz_id, '_' . $post->post_type, true );
				$course_id	 = (!empty( $assign_meta[ $post->post_type . '_course' ] ) ) ? $assign_meta[ $post->post_type . '_course' ] : 0;
				$lesson_id	 = (!empty( $assign_meta[ $post->post_type . '_lesson' ] ) ) ? $assign_meta[ $post->post_type . '_lesson' ] : 0;

				if ( $course_id ) {
					$breadcrumb_array[] = array(
						'title'	 => get_the_title( $course_id ),
						'href'	 => get_permalink( $course_id ),
					);
				}

				if ( $lesson_id ) {
					$breadcrumb_array[] = array(
						'title'	 => get_the_title( $lesson_id ),
						'href'	 => get_permalink( $lesson_id ),
					);
				}

				$breadcrumb_array[] = array(
					'title'	 => get_the_title( $quiz_id ),
					'href'	 => '',
				);
			}

			if ( !empty( $breadcrumb_array ) && is_array( $breadcrumb_array ) ) {
				echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
				foreach ( $breadcrumb_array as $key => $value ) {
					echo '<li>';
					if ( !empty( $value[ 'href' ] ) ) {
						echo '<a href="' . $value[ 'href' ] . '">' . $value[ 'title' ] . '</a>';
					} else {
						echo '<strong>' . $value[ 'title' ] . '</strong>';
					}
					echo '</li>';
					if ( count( $breadcrumb_array ) != ( $key + 1 ) ) {
						echo '<li class="separator">';
						echo $separator;
						echo '</li>';
					}
				}
				echo '</ul>';
			}
		}

	}

	endif;
/**
 * Main instance of Reign_LD_Theme_Hook.
 * @return Reign_LD_Theme_Hook
 */
Reign_LD_Theme_Hook::instance();
