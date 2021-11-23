<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Add admin page for importing Review(s).
if ( ! class_exists( 'BGR_Admin' ) ) {
	class BGR_Admin {

		/**
		 * Constructor for admin settings
		 *
		 * @since    1.0.0
		 * @author   Wbcom Designs
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'bgr_add_submenu_page_admin_settings' ) );
			add_action( 'admin_menu', array( $this, 'bgr_get_review_count' ) );
			$post_types = get_post_types();
			if ( ! in_array( 'review', $post_types ) ) {
				// Custom Post Type.
				add_action( 'init', array( $this, 'bgr_review_cpt' ) );
				add_action( 'init', array( $this, 'bgr_review_taxonomy_cpt' ) );
			}
		}

		/**
		 * Actions performed on loading admin_menu.
		 *
		 * @since    1.0.0
		 * @author   Wbcom Designs
		 */
		function bgr_add_submenu_page_admin_settings() {
			if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
				add_menu_page( esc_html__( 'WB Plugins', 'bp-group-reviews' ), esc_html__( 'WB Plugins', 'bp-group-reviews' ), 'manage_options', 'wbcomplugins', array( $this, 'bgr_admin_options_page' ), 'dashicons-lightbulb', 59 );
				add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'bp-group-reviews' ), esc_html__( 'General', 'bp-group-reviews' ), 'manage_options', 'wbcomplugins' );
			}
			add_submenu_page( 'wbcomplugins', esc_html__( 'Group Reviews', 'bp-group-reviews' ), esc_html__( 'Group Reviews', 'bp-group-reviews' ), 'manage_options', 'group-review-settings', array( $this, 'bgr_admin_options_page' ) );
		}

		/**
		 * Actions performed on changing admin settings tab
		 *
		 * @since    1.0.0
		 * @param string $current for the admin options.
		 * @author   Wbcom Designs
		 */
		function bgr_admin_options_page( $current = 'welcome' ) {
			if ( isset( $_GET['tab'] ) ) {
				$current = sanitize_text_field( $_GET['tab'] );
			} else {
				$current = 'welcome';
			}
					$bgr_tabs = array(
						'welcome'   => esc_html__( 'Welcome', 'bp-group-reviews' ),
						'general'   => esc_html__( 'General', 'bp-group-reviews' ),
						'criteria'  => esc_html__( 'Criteria', 'bp-group-reviews' ),
						'shortcode' => esc_html__( 'Shortcode', 'bp-group-reviews' ),
						'display'   => esc_html__( 'Display', 'bp-group-reviews' ),
					);
					?>
					<div class="wrap">
                                            <hr class="wp-header-end">
                                            <div class="wbcom-wrap">
                                            <div class="bgr-header">
                                                    <?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
                                                    <h1 class="wbcom-plugin-heading">
                                                            <?php esc_html_e( 'BuddyPress Group Reviews Settings', 'bp-group-reviews' ); ?>
                                                    </h1>
                                            </div>
                                            <div class="wbcom-admin-settings-page">
                                                    <div id="bgr-settings-updated" class="updated settings-error notice is-dismissible">
                                                            <p><strong><?php esc_html_e( 'BuddyPress Group Reviews Settings Saved.', 'bp-group-reviews' ); ?></strong></p>
                                                            <button type="button" class="notice-dismiss">
                                                                    <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'bp-group-reviews' ); ?></span>
                                                            </button>
                                                    </div>
                                                    <?php
                                                    $bgr_tab_html = '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
                                                    foreach ( $bgr_tabs as $bgr_tab => $bgr_name ) {
                                                                    $class         = ( $bgr_tab == $current ) ? 'nav-tab-active' : '';
                                                                    $bgr_tab_html .= '<li><a class="nav-tab ' . $class . '" href="admin.php?page=group-review-settings&tab=' . $bgr_tab . '">' . $bgr_name . '</a></li>';
                                                    }
                                                    $bgr_tab_html .= '</div></ul></div>';
                                                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                    echo $bgr_tab_html;
                                                    echo '<div class="wbcom-tab-content">';
                                                    include 'review-admin-options-page.php';
                                                    echo '</div></div></div>';
		}

		/**
		 * Actions performed to create Review cpt
		 *
		 * @since    1.0.0
		 * @author   Wbcom Designs
		 */
		function bgr_review_cpt() {
			$labels = array(
				'name'               => esc_html__( 'Reviews', 'bp-group-reviews' ),
				'singular_name'      => esc_html__( 'Review', 'bp-group-reviews' ),
				'menu_name'          => esc_html__( 'Reviews', 'bp-group-reviews' ),
				'name_admin_bar'     => esc_html__( 'Reviews', 'bp-group-reviews' ),
				'add_new'            => esc_html__( 'Add New Review', 'bp-group-reviews' ),
				'add_new_item'       => esc_html__( 'Add New Review', 'bp-group-reviews' ),
				'new_item'           => esc_html__( 'New Review', 'bp-group-reviews' ),
				'view_item'          => esc_html__( 'View Reviews', 'bp-group-reviews' ),
				'all_items'          => esc_html__( 'All Reviews', 'bp-group-reviews' ),
				'search_items'       => esc_html__( 'Search Reviews', 'bp-group-reviews' ),
				'parent_item_colon'  => esc_html__( 'Parent Review', 'bp-group-reviews' ),
				'not_found'          => esc_html__( 'No Review Found', 'bp-group-reviews' ),
				'not_found_in_trash' => esc_html__( 'No Review Found In Trash', 'bp-group-reviews' ),
			);
			$args   = array(
				'labels'             => $labels,
				'public'             => true,
				'menu_icon'          => 'dashicons-testimonial',
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array(
					'slug'       => 'review',
					'with_front' => false,
				),
				'capability_type'    => 'post',
				'capabilities'       => array(
					'create_posts' => 'do_not_allow',
				),
				'map_meta_cap'       => true,
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
			);
			register_post_type( 'review', $args );
		}

		/**
		 * Actions performed to create Review cpt taxonomy
		 *
		 * @since    1.0.0
		 * @author   Wbcom Designs
		 */
		function bgr_review_taxonomy_cpt() {
			$category_labels = array(
				'name'              => esc_html_x( 'Reviews Category', 'taxonomy general name', 'bp-group-reviews' ),
				'singular_name'     => esc_html_x( 'Review Category', 'taxonomy singular name', 'bp-group-reviews' ),
				'search_items'      => esc_html__( 'Search Categories', 'bp-group-reviews' ),
				'all_items'         => esc_html__( 'All Categories', 'bp-group-reviews' ),
				'parent_item'       => esc_html__( 'Parent Category', 'bp-group-reviews' ),
				'parent_item_colon' => esc_html__( 'Parent Category:', 'bp-group-reviews' ),
				'edit_item'         => esc_html__( 'Edit Category', 'bp-group-reviews' ),
				'update_item'       => esc_html__( 'Update Category', 'bp-group-reviews' ),
				'add_new_item'      => esc_html__( 'Add Category', 'bp-group-reviews' ),
				'new_item_name'     => esc_html__( 'New Category Name', 'bp-group-reviews' ),
				'menu_name'         => esc_html__( 'Category', 'bp-group-reviews' ),
			);
			$category_args   = array(
				'hierarchical'      => true,
				'labels'            => $category_labels,
				'show_ui'           => false,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'review_category' ),
			);
			register_taxonomy( 'review_category', array( 'review' ), $category_args );
		}

		/**
		 * [bgr_get_review_count description] Function count
		 *
		 * @return [type] [Count on Review menu item ]
		 */
		public function bgr_get_review_count() {
			global $bgr, $menu;
			if ( $bgr['auto_approve_reviews'] != 'yes' ) {

				foreach ( $menu as $each_menu ) {
					if ( $each_menu[2] == 'edit.php?post_type=review' ) {
						$count = wp_count_posts( 'review' );
						if ( $count ) {
							$count = $count->draft;

							$key = $this->bgr_recursive_array_search( 'edit.php?post_type=review', $menu );

							if ( ! $key ) {
								return;
							}

							$menu[ $key ][0] .= sprintf(
								'<span class="awaiting-mod update-plugins count-%1$s"><span class="plugin-count">%1$s</span></span>',
								$count
							);
						}
					}
				}
			}
		}

		/**
		 * [bgr_recursive_array_search description]
		 *
		 * @param  [sting] $needle
		 * @param  [array] $haystack
		 * @return [number]  [Return array key.]
		 */
		public function bgr_recursive_array_search( $needle, $haystack ) {
			foreach ( $haystack as $key => $value ) {
				$current_key = $key;
				if (
					$needle === $value
					or (
				is_array( $value )
				&& $this->bgr_recursive_array_search( $needle, $value ) !== false
					)
				) {
					return $current_key;
				}
			}
			return false;
		}

	}
	new BGR_Admin();
}
