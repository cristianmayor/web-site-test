<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Reign_Kirki_Post_Types_Support' ) ) :

	/**
	 * @class Reign_Kirki_Post_Types_Support
	 */
	class Reign_Kirki_Post_Types_Support {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Post_Types_Support
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Post_Types_Support Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Post_Types_Support is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Post_Types_Support - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Post_Types_Support Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
			add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		}

		public function get_post_types_to_support() {
			global $wp_query;

			$post_types = array(
				array(
					'slug'        => 'post',
					'name'        => __( 'Blog', 'reign' ),
					'has_archive' => true,
				),
				array(
					'slug'        => 'page',
					'name'        => __( 'Page', 'reign' ),
					'has_archive' => false,
				),
			);

			$args = array(
				'public'              => true,
				'_builtin'            => false,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'exclude_from_search' => false,
			);

			$output   = 'names'; // 'names' or 'objects' (default: 'names')
			$operator = 'and'; // 'and' or 'or' (default: 'and')

			$custom_post_types = get_post_types( $args, $output, $operator );

			$temp        = array();
			$has_archive = false;
			if ( is_array( $custom_post_types ) && ! empty( $custom_post_types ) ) {
				foreach ( $custom_post_types as $key => $custom_post_type ) {
					$post_type_data = get_post_type_object( $custom_post_type );
					if ( false !== $post_type_data->has_archive ) {
						$has_archive = true;
					}

					if ( 'sfwd-courses' === $custom_post_type || 'sfwd-lessons' === $custom_post_type || 'sfwd-topic' === $custom_post_type || 'sfwd-quiz' === $custom_post_type ) {

						$custom_post_type = 'sfwd-courses' === $custom_post_type ? learndash_get_custom_label( 'course' ) : $custom_post_type;

						$custom_post_type = 'sfwd-lessons' === $custom_post_type ? learndash_get_custom_label( 'lessons' ) : $custom_post_type;

						$custom_post_type = 'sfwd-topic' === $custom_post_type ? learndash_get_custom_label( 'topic' ) : $custom_post_type;

						$custom_post_type = 'sfwd-quiz' === $custom_post_type ? learndash_get_custom_label( 'quiz' ) : $custom_post_type;

						$sfwd_has_archive = learndash_post_type_has_archive( $custom_post_type );
						if ( $sfwd_has_archive ) {
							$has_archive = true;
						} else {
							$has_archive = false;
						}
					}

					if ( 'job_listing' === $custom_post_type ) {
						$custom_post_type = __( 'Jobs', 'reign' );
					}
					if ( 'tribe_events' === $custom_post_type ) {
						$custom_post_type = __( 'Events', 'reign' );
					}

					$temp[] = array(
						'slug'        => $key,
						'name'        => ucwords( preg_replace( '/[_]+/', ' ', $custom_post_type ) ),
						'has_archive' => $has_archive,
					);
				}

				$post_types = array_merge( $post_types, $temp );
			}

			$post_types = apply_filters( 'reign_customizer_supported_post_types', $post_types );

			return $post_types;
		}

		public function add_panels_and_sections( $wp_customize ) {

			$post_types = $this->get_post_types_to_support();

			foreach ( $post_types as $post_type ) {
				$wp_customize->add_panel(
					'reign_' . $post_type['slug'] . '_panel',
					array(
						'priority'    => 100,
						'title'       => $post_type['name'],
						'description' => '',
						'panel',
					)
				);

				if ( isset( $post_type['has_archive'] ) && false !== $post_type['has_archive'] ) {
					$wp_customize->add_section(
						'reign_' . $post_type['slug'] . '_archive',
						array(
							'title'       => __( 'Archive', 'reign' ),
							'priority'    => 10,
							'panel'       => 'reign_' . $post_type['slug'] . '_panel',
							'description' => '',
						)
					);
				}

				$wp_customize->add_section(
					'reign_' . $post_type['slug'] . '_single',
					array(
						'title'       => __( 'Single', 'reign' ),
						'priority'    => 10,
						'panel'       => 'reign_' . $post_type['slug'] . '_panel',
						'description' => '',
					)
				);

				if ( 'page' === $post_type['slug'] ) {
					$wp_customize->add_section(
						'reign_page_search',
						array(
							'title'       => __( 'Search', 'reign' ),
							'priority'    => 10,
							'panel'       => 'reign_page_panel',
							'description' => '',
						)
					);
				}
			}
		}

		public function add_fields( $fields ) {

			$post_types = $this->get_post_types_to_support();

			global $wp_registered_sidebars;
			$widgets_areas    = array( '0' => __( 'Default', 'reign' ) );
			$get_widget_areas = $wp_registered_sidebars;
			if ( ! empty( $get_widget_areas ) ) {
				foreach ( $get_widget_areas as $widget_area ) {
					$name = isset( $widget_area['name'] ) ? $widget_area['name'] : '';
					$id   = isset( $widget_area['id'] ) ? $widget_area['id'] : '';
					if ( $name && $id ) {
						$widgets_areas[ $id ] = $name;
					}
				}
			}

			foreach ( $post_types as $post_type ) {
                                if ( ! is_plugin_active( 'reign-tutorlms-addon/reign-tutorlms-addon.php' ) ) {
                                    $fields[] = array(
                                            'type'        => 'radio-image',
                                            'settings'    => 'reign_' . $post_type['slug'] . '_archive_layout',
                                            'label'       => esc_attr__( 'Layout', 'reign' ),
                                            'description' => esc_attr__( 'Choose a layout for all archive pages.', 'reign' ),
                                            'section'     => 'reign_' . $post_type['slug'] . '_archive',
                                            'default'     => 'right_sidebar',
                                            'priority'    => 10,
                                            'choices'     => array(
                                                    'left_sidebar'  => REIGN_THEME_URI . '/lib/images/sidebar-left.jpg',
                                                    'right_sidebar' => REIGN_THEME_URI . '/lib/images/sidebar-right.jpg',
                                                    'both_sidebar'  => REIGN_THEME_URI . '/lib/images/sidebar-both.jpg',
                                                    'full_width'    => REIGN_THEME_URI . '/lib/images/sidebar-none.jpg',
                                            ),
                                    );
                                }

				$fields[] = array(
					'type'        => 'switch',
					'settings'    => 'reign_' . $post_type['slug'] . '_archive_header_enable',
					'label'       => esc_attr__( 'Hide Archive Page Sub Header', 'reign' ),
					'description' => esc_attr__( 'Hide page sub header for this post type.', 'reign' ),
					'section'     => 'reign_' . $post_type['slug'] . '_archive',
					'default'     => 0,
					'priority'    => 10,
					'choices'     => array(
						'on'  => esc_attr__( 'Enable', 'reign' ),
						'off' => esc_attr__( 'Disable', 'reign' ),
					),
				);

				$fields[] = array(
					'type'            => 'switch',
					'settings'        => 'reign_' . $post_type['slug'] . '_archive_enable_header_image',
					'label'           => esc_attr__( 'Enable Sub Header Image', 'reign' ),
					'description'     => '',
					'section'         => 'reign_' . $post_type['slug'] . '_archive',
					'default'         => 1,
					'priority'        => 10,
					'choices'         => array(
						'on'  => esc_attr__( 'Enable', 'reign' ),
						'off' => esc_attr__( 'Disable', 'reign' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'reign_' . $post_type['slug'] . '_archive_header_enable',
							'operator' => '===',
							'value'    => false,
						),
					),
				);

				$fields[] = array(
					'type'            => 'image',
					'settings'        => 'reign_' . $post_type['slug'] . '_archive_header_image',
					'label'           => esc_attr__( 'Blog Sub Header Image', 'reign' ),
					'description'     => esc_attr__( 'Set page sub header image for blog page.', 'reign' ),
					'section'         => 'reign_' . $post_type['slug'] . '_archive',
					'priority'        => 10,
					'default'         => reign_get_default_page_header_image(),
					'active_callback' => array(
						array(
							'setting'  => 'reign_' . $post_type['slug'] . '_archive_header_enable',
							'operator' => '===',
							'value'    => false,
						),
						array(
							'setting'  => 'reign_' . $post_type['slug'] . '_archive_enable_header_image',
							'operator' => '===',
							'value'    => true,
						),
					),
				);

                                if ( ! is_plugin_active( 'reign-tutorlms-addon/reign-tutorlms-addon.php' ) ) {
                                    $fields[] = array(
                                            'type'        => 'select',
                                            'settings'    => 'reign_' . $post_type['slug'] . '_archive_left_sidebar',
                                            'label'       => esc_attr__( 'Left Sidebar', 'reign' ),
                                            'description' => esc_attr__( 'Set left sidebar.', 'reign' ),
                                            'section'     => 'reign_' . $post_type['slug'] . '_archive',
                                            'priority'    => 10,
                                            'default'     => '0',
                                            'priority'    => 10,
                                            'choices'     => $widgets_areas,
                                    );

                                    $fields[] = array(
                                            'type'        => 'select',
                                            'settings'    => 'reign_' . $post_type['slug'] . '_archive_right_sidebar',
                                            'label'       => esc_attr__( 'Right Sidebar', 'reign' ),
                                            'description' => esc_attr__( 'Set right sidebar.', 'reign' ),
                                            'section'     => 'reign_' . $post_type['slug'] . '_archive',
                                            'priority'    => 10,
                                            'default'     => '0',
                                            'priority'    => 10,
                                            'choices'     => $widgets_areas,
                                    );

                                    $fields[] = array(
                                            'type'        => 'radio-image',
                                            'settings'    => 'reign_' . $post_type['slug'] . '_single_layout',
                                            'label'       => esc_attr__( 'Layout', 'reign' ),
                                            'description' => esc_attr__( 'Choose a layout to display for all single post pages.', 'reign' ),
                                            'section'     => 'reign_' . $post_type['slug'] . '_single',
                                            'default'     => 'right_sidebar',
                                            'priority'    => 10,
                                            'choices'     => array(
                                                    'left_sidebar'  => REIGN_THEME_URI . '/lib/images/sidebar-left.jpg',
                                                    'right_sidebar' => REIGN_THEME_URI . '/lib/images/sidebar-right.jpg',
                                                    'both_sidebar'  => REIGN_THEME_URI . '/lib/images/sidebar-both.jpg',
                                                    'full_width'    => REIGN_THEME_URI . '/lib/images/sidebar-none.jpg',
                                            ),
                                    );
                                }

				$fields[] = array(
					'type'        => 'radio-image',
					'settings'    => 'reign_search_page_layout',
					'label'       => esc_attr__( 'Layout', 'reign' ),
					'description' => esc_attr__( 'Choose a layout to display for all search post pages.', 'reign' ),
					'section'     => 'reign_page_search',
					'default'     => 'right_sidebar',
					'priority'    => 10,
					'choices'     => array(
						'left_sidebar'  => REIGN_THEME_URI . '/lib/images/sidebar-left.jpg',
						'right_sidebar' => REIGN_THEME_URI . '/lib/images/sidebar-right.jpg',
						'both_sidebar'  => REIGN_THEME_URI . '/lib/images/sidebar-both.jpg',
						'full_width'    => REIGN_THEME_URI . '/lib/images/sidebar-none.jpg',
					),
				);

				if ( 'page' !== $post_type['slug'] ) {
					$fields[] = array(
						'type'        => 'switch',
						'settings'    => 'reign_' . $post_type['slug'] . '_single_header_enable',
						'label'       => esc_attr__( 'Hide ' . $post_type['name'] . ' Sub Header', 'reign' ),
						'description' => esc_attr__( 'Hide page sub header for this post type.', 'reign' ),
						'section'     => 'reign_' . $post_type['slug'] . '_single',
						'default'     => 0,
						'priority'    => 10,
						'choices'     => array(
							'on'  => esc_attr__( 'Enable', 'reign' ),
							'off' => esc_attr__( 'Disable', 'reign' ),
						),
					);
				} else {
					$fields[] = array(
						'type'        => 'switch',
						'settings'    => 'reign_' . $post_type['slug'] . '_single_header_enable',
						'label'       => esc_attr__( 'Hide Page Sub Header', 'reign' ),
						'description' => esc_attr__( 'Hide page sub header.', 'reign' ),
						'section'     => 'reign_' . $post_type['slug'] . '_single',
						'default'     => 0,
						'priority'    => 10,
						'choices'     => array(
							'on'  => esc_attr__( 'Enable', 'reign' ),
							'off' => esc_attr__( 'Disable', 'reign' ),
						),
					);
				}

				if ( 'page' == $post_type['slug'] ) {
					$fields[] = array(
						'type'        => 'switch',
						'settings'    => 'reign_' . $post_type['slug'] . '_single_pagetitle_enable',
						'label'       => esc_attr__( 'Hide ' . $post_type['name'] . ' Title', 'reign' ),
						'description' => esc_attr__( 'Hide page title for this post type.', 'reign' ),
						'section'     => 'reign_' . $post_type['slug'] . '_single',
						'default'     => 0,
						'priority'    => 10,
						'choices'     => array(
							'on'  => esc_attr__( 'Enable', 'reign' ),
							'off' => esc_attr__( 'Disable', 'reign' ),
						),
					);
				}

				$fields[] = array(
					'type'            => 'switch',
					'settings'        => 'reign_' . $post_type['slug'] . '_single_enable_header_image',
					'label'           => esc_attr__( 'Enable Sub Header Image', 'reign' ),
					'description'     => '',
					'section'         => 'reign_' . $post_type['slug'] . '_single',
					'default'         => 1,
					'priority'        => 10,
					'choices'         => array(
						'on'  => esc_attr__( 'Enable', 'reign' ),
						'off' => esc_attr__( 'Disable', 'reign' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'reign_' . $post_type['slug'] . '_single_header_enable',
							'operator' => '===',
							'value'    => false,
						),
					),
				);

				$fields[] = array(
					'type'            => 'image',
					'settings'        => 'reign_' . $post_type['slug'] . '_single_header_image',
					'label'           => esc_attr__( 'Page Sub Header Image', 'reign' ),
					'description'     => esc_attr__( 'Set page sub header image for single post page.', 'reign' ),
					'section'         => 'reign_' . $post_type['slug'] . '_single',
					'priority'        => 10,
					'default'         => reign_get_default_page_header_image(),
					'active_callback' => array(
						array(
							'setting'  => 'reign_' . $post_type['slug'] . '_single_header_enable',
							'operator' => '===',
							'value'    => false,
						),
						array(
							'setting'  => 'reign_' . $post_type['slug'] . '_single_enable_header_image',
							'operator' => '===',
							'value'    => true,
						),
					),
				);

				if ( 'post' === $post_type['slug'] ) {

					$fields[] = array(
						'type'        => 'select',
						'settings'    => 'reign_blog_list_layout',
						'label'       => esc_attr__( 'Blog Listing Layout', 'reign' ),
						'description' => esc_attr__( 'Select your log listing layout here. We have option to choose from 4 different views.', 'reign' ),
						'section'     => 'reign_' . $post_type['slug'] . '_archive',
						'default'     => 'default-view',
						'priority'    => 10,
						'choices'     => array(
							'default-view'   => esc_attr__( 'Default View', 'reign' ),
							'thumbnail-view' => esc_attr__( 'Thumbnail View', 'reign' ),
							'wb-grid-view'   => esc_attr__( 'Grid View', 'reign' ),
							'masonry-view'   => esc_attr__( 'Masonry View', 'reign' ),
						),
					);

					$fields[] = array(
						'type'            => 'number',
						'settings'        => 'reign_blog_per_row',
						'label'           => esc_attr__( 'Blogs Per Row', 'reign' ),
						'description'     => '',
						'section'         => 'reign_' . $post_type['slug'] . '_archive',
						'default'         => '3',
						'priority'        => 10,
						'active_callback' => array(
							array(
								'setting'  => 'reign_blog_list_layout',
								'operator' => 'contains',
								'value'    => array( 'wb-grid-view', 'masonry-view' ),
							),
						),
					);

					$fields[] = array(
						'type'        => 'number',
						'settings'    => 'reign_blog_excerpt_length',
						'label'       => esc_attr__( 'Excerpt Length (words)', 'reign' ),
						'description' => '',
						'section'     => 'reign_' . $post_type['slug'] . '_archive',
						'default'     => '20',
						'priority'    => 10,
					);

					// $fields[] = array(
					// 'type'        => 'switch',
					// 'settings'    => 'reign_single_post_switch_header_image',
					// 'label'       => esc_attr__( 'Switch Header Image With Featured Image', 'reign' ),
					// 'description' => esc_attr__( 'This will show post featured image on top header section and featured image will be removed from post content.', 'reign' ),
					// 'section'     => 'reign_' . $post_type['slug'] . '_single',
					// 'default'     => 0,
					// 'priority'    => 10,
					// 'choices'     => array(
					// 'on'  => esc_attr__( 'Enable', 'reign' ),
					// 'off' => esc_attr__( 'Disable', 'reign' ),
					// ),
					// );

					$fields[] = array(
						'type'        => 'select',
						'settings'    => 'reign_single_post_meta_alignment',
						'label'       => esc_attr__( 'Post Meta Alignment', 'reign' ),
						'description' => esc_attr__( 'Select alignment for post-meta information on single post page.', 'reign' ),
						'section'     => 'reign_' . $post_type['slug'] . '_single',
						'default'     => 'left',
						'priority'    => 10,
						'choices'     => array(
							'left'   => esc_attr__( 'Left', 'reign' ),
							'center' => esc_attr__( 'Center', 'reign' ),
							'right'  => esc_attr__( 'Right', 'reign' ),
						),
					);

					$fields[] = array(
						'type'        => 'select',
						'settings'    => 'reign_blog_list_pagination',
						'label'       => esc_attr__( 'Blog Listing Pagination Type', 'reign' ),
						'description' => esc_attr__( 'Set pagination type on post page.', 'reign' ),
						'section'     => 'reign_' . $post_type['slug'] . '_archive',
						'priority'    => 10,
						'default'     => 'reign_blog_number_pagination',
						'choices'     => array(
							'reign_blog_number_pagination' => esc_attr__( 'Numeric Pagination', 'reign' ),
							'reign_blog_infinite_scroll_pagination' => esc_attr__( 'Infinite Scroll Pagination', 'reign' ),
						),
					);
				}

				$fields[] = array(
					'type'        => 'switch',
					'settings'    => 'reign_single_' . $post_type['slug'] . '_switch_header_image',
					'label'       => esc_attr__( 'Switch Sub Header Image With Featured Image', 'reign' ),
					'description' => esc_attr__( 'This will show post featured image on sub header section and featured image will be removed from post content.', 'reign' ),
					'section'     => 'reign_' . $post_type['slug'] . '_single',
					'default'     => 0,
					'priority'    => 10,
					'choices'     => array(
						'on'  => esc_attr__( 'Enable', 'reign' ),
						'off' => esc_attr__( 'Disable', 'reign' ),
					),
				);

                                if ( ! is_plugin_active( 'reign-tutorlms-addon/reign-tutorlms-addon.php' ) ) {
                                    $fields[] = array(
                                            'type'        => 'select',
                                            'settings'    => 'reign_' . $post_type['slug'] . '_single_left_sidebar',
                                            'label'       => esc_attr__( 'Left Sidebar', 'reign' ),
                                            'description' => esc_attr__( 'Set left sidebar.', 'reign' ),
                                            'section'     => 'reign_' . $post_type['slug'] . '_single',
                                            'priority'    => 10,
                                            'default'     => '0',
                                            'priority'    => 10,
                                            'choices'     => $widgets_areas,
                                    );

                                    $fields[] = array(
                                            'type'        => 'select',
                                            'settings'    => 'reign_' . $post_type['slug'] . '_single_right_sidebar',
                                            'label'       => esc_attr__( 'Right Sidebar', 'reign' ),
                                            'description' => esc_attr__( 'Set right sidebar.', 'reign' ),
                                            'section'     => 'reign_' . $post_type['slug'] . '_single',
                                            'priority'    => 10,
                                            'default'     => '0',
                                            'priority'    => 10,
                                            'choices'     => $widgets_areas,
                                    );
                                }

				$fields[] = array(
					'type'        => 'switch',
					'settings'    => 'reign_search_header_enable',
					'label'       => esc_attr__( 'Hide Page Sub Header', 'reign' ),
					'description' => esc_attr__( 'Hide page sub header for this post type.', 'reign' ),
					'section'     => 'reign_page_search',
					'default'     => 1,
					'priority'    => 10,
					'choices'     => array(
						'on'  => esc_attr__( 'Enable', 'reign' ),
						'off' => esc_attr__( 'Disable', 'reign' ),
					),
				);

				$fields[] = array(
					'type'            => 'switch',
					'settings'        => 'reign_search_enable_header_image',
					'label'           => esc_attr__( 'Enable Header Image', 'reign' ),
					'description'     => '',
					'section'         => 'reign_page_search',
					'default'         => 1,
					'priority'        => 10,
					'choices'         => array(
						'on'  => esc_attr__( 'Enable', 'reign' ),
						'off' => esc_attr__( 'Disable', 'reign' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'reign_search_header_enable',
							'operator' => '===',
							'value'    => false,
						),
					),
				);

				$fields[] = array(
					'type'            => 'image',
					'settings'        => 'reign_search_header_image',
					'label'           => esc_attr__( 'Page Header Image', 'reign' ),
					'description'     => esc_attr__( 'Set page header image for single post page.', 'reign' ),
					'section'         => 'reign_page_search',
					'priority'        => 10,
					'default'         => reign_get_default_page_header_image(),
					'active_callback' => array(
						array(
							'setting'  => 'reign_search_header_enable',
							'operator' => '===',
							'value'    => false,
						),
						array(
							'setting'  => 'reign_search_enable_header_image',
							'operator' => '===',
							'value'    => true,
						),
					),
				);

				$fields[] = array(
					'type'        => 'select',
					'settings'    => 'reign_search_left_sidebar',
					'label'       => esc_attr__( 'Left Sidebar', 'reign' ),
					'description' => esc_attr__( 'Set left sidebar.', 'reign' ),
					'section'     => 'reign_page_search',
					'priority'    => 10,
					'default'     => '0',
					'priority'    => 10,
					'choices'     => $widgets_areas,
				);

				$fields[] = array(
					'type'        => 'select',
					'settings'    => 'reign_search_right_sidebar',
					'label'       => esc_attr__( 'Right Sidebar', 'reign' ),
					'description' => esc_attr__( 'Set right sidebar.', 'reign' ),
					'section'     => 'reign_page_search',
					'priority'    => 10,
					'default'     => '0',
					'priority'    => 10,
					'choices'     => $widgets_areas,
				);
			}

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Post_Types_Support.
 *
 * @return Reign_Kirki_Post_Types_Support
 */
Reign_Kirki_Post_Types_Support::instance();
