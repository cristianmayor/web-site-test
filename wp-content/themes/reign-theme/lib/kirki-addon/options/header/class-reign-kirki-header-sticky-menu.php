<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Header_Sticky_Menu' ) ) :

	/**
	 * @class Reign_Kirki_Header_Sticky_Menu
	 */
	class Reign_Kirki_Header_Sticky_Menu {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Header_Sticky_Menu
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Header_Sticky_Menu Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Header_Sticky_Menu is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Header_Sticky_Menu - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Header_Sticky_Menu Constructor.
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

		public function add_panels_and_sections( $wp_customize ) {

			$wp_customize->add_section(
			'reign_header_sticky_menu', array(
				'title'			 => __( 'Sticky Menu', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_header_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

			$fields[] = array(
				'type'				 => 'switch',
				'settings'			 => 'reign_header_sticky_menu_enable',
				'label'				 => esc_attr__( 'Sticky On Scroll', 'reign' ),
				'description'		 => esc_attr__( 'Enable or Disable sticky header menu.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => 1,
				'priority'			 => 10,
				'choices'			 => array(
					'on'	 => esc_attr__( 'Enable', 'reign' ),
					'off'	 => esc_attr__( 'Disable', 'reign' ),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'switch',
				'settings'			 => 'reign_header_sticky_menu_custom_style_enable',
				'label'				 => esc_attr__( 'Config Sticky Menu', 'reign' ),
				'description'		 => esc_attr__( 'Config sticky menu style.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => 0,
				'priority'			 => 10,
				'choices'			 => array(
					'on'	 => esc_attr__( 'Custom', 'reign' ),
					'off'	 => esc_attr__( 'Default', 'reign' ),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_header_sticky_menu_bg_color',
				'label'				 => esc_attr__( 'Header Background Color', 'reign' ),
				'description'		 => esc_attr__( 'The background color of sticky header.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_header_sticky_menu_bg_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky',
						'property'	 => 'background-color',
					)
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_sticky_header_logo_color',
				'label'				 => esc_attr__( 'Site Title', 'reign' ),
				'description'		 => esc_attr__( 'The color of site title.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_sticky_header_logo_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky .site-branding .site-title a',
						'property'	 => 'color',
					),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'image',
				'settings'			 => 'reign_sticky_header_menu_logo',
				'label'				 => esc_attr__( 'Sticky Header Logo', 'reign' ),
				'description'		 => esc_attr__( 'Add, Remove, Change sticky header logo.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'priority'			 => 10,
				'default'			 => '',
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_header_sticky_menu_text_color',
				'label'				 => esc_attr__( 'Menu Item Font Color', 'reign' ),
				'description'		 => esc_attr__( 'The color of sticky menu item.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_header_sticky_menu_text_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky .primary-menu > li > a',
						'property'	 => 'color',
					)
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);


			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_header_sticky_menu_text_hover_color',
				'label'				 => esc_attr__( 'Menu Item Font Color [Hover]', 'reign' ),
				'description'		 => esc_attr__( 'The color of sticky menu item hover.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_header_sticky_menu_text_hover_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky .version-one .primary-menu > li a:hover, #masthead.site-header.sticky .version-one .primary-menu > li a:hover, #masthead.site-header.sticky .version-two .primary-menu > li a:hover, #masthead.site-header.sticky .version-three .primary-menu > li a:hover',
						'property'	 => 'color',
					)
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_header_sticky_menu_text_active_color',
				'label'				 => esc_attr__( 'Menu Item Font Color [Active]', 'reign' ),
				'description'		 => esc_attr__( 'The color of sticky menu item active.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_header_sticky_menu_text_active_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky .version-one .primary-menu > li.current-menu-item > a, #masthead.site-header.sticky .version-one .primary-menu > li.current-menu-item > a, #masthead.site-header.sticky .version-two .primary-menu > li.current-menu-item > a, #masthead.site-header.sticky .version-three .primary-menu > li.current-menu-item > a',
						'property'	 => 'color',
					)
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_header_sticky_menu_bg_hover_color',
				'label'				 => esc_attr__( 'Menu Item Font Background [Hover]', 'reign' ),
				'description'		 => esc_attr__( 'The background color of sticky menu item hover.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_header_sticky_menu_bg_hover_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky .primary-menu > li a:hover:before, #masthead.site-header.sticky .version-one .primary-menu > li a:hover:before, #masthead.site-header.sticky .version-two .primary-menu > li a:hover:before, #masthead.site-header.sticky .version-three .primary-menu > li a:hover:before',
						'property'	 => 'background',
					)
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_header_sticky_menu_bg_active_color',
				'label'				 => esc_attr__( 'Menu Item Font Background [Active]', 'reign' ),
				'description'		 => esc_attr__( 'The background color of sticky menu item active.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_header_sticky_menu_bg_active_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky .primary-menu > li.current-menu-item a:before, #masthead.site-header.sticky .version-one .primary-menu > li.current-menu-item a:before, #masthead.site-header.sticky .version-two .primary-menu > li.current-menu-item a:before, #masthead.site-header.sticky .version-three .primary-menu > li.current-menu-item a:before',
						'property'	 => 'background',
					)
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_sticky_header_icon_color',
				'label'				 => esc_attr__( 'Icon Color', 'reign' ),
				'description'		 => esc_attr__( 'The color of sticky header icon.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_sticky_header_icon_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky .rg-search-icon:before, #masthead.site-header.sticky .rg-icon-wrap span:before, #masthead.site-header.sticky .rg-icon-wrap, #masthead.site-header.sticky .user-link-wrap .user-link, #masthead.site-header.sticky .ps-user-name, #masthead.site-header.sticky .ps-dropdown--userbar .ps-dropdown__toggle, #masthead.site-header.sticky .ps-widget--userbar__logout>a',
						'property'	 => 'color',
					),
					array(
						'element'	 => '#masthead.site-header.sticky .wbcom-nav-menu-toggle span',
						'property'	 => 'background',
					),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'color',
				'settings'			 => 'reign_sticky_header_icon_hover_color',
				'label'				 => esc_attr__( 'Icon Color [Hover]', 'reign' ),
				'description'		 => esc_attr__( 'The color of sticky header icon hover.', 'reign' ),
				'section'			 => 'reign_header_sticky_menu',
				'default'			 => $default_value_set[ 'reign_sticky_header_icon_hover_color' ],
				'priority'			 => 10,
				'choices'			 => array( 'alpha' => true ),
				'output'			 => array(
					array(
						'element'	 => '#masthead.site-header.sticky .rg-search-icon:hover:before, #masthead.site-header.sticky .rg-icon-wrap span:hover:before, #masthead.site-header.sticky .rg-icon-wrap:hover, #masthead.site-header.sticky .user-link-wrap .user-link:hover, #masthead.site-header.sticky .ps-user-name:hover, #masthead.site-header.sticky .ps-dropdown--userbar .ps-dropdown__toggle:hover, #masthead.site-header.sticky .ps-widget--userbar__logout>a:hover',
						'property'	 => 'color',
					),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_header_sticky_menu_custom_style_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
					array(
						'setting'	 => 'reign_header_header_type',
						'operator'	 => '!==',
						'value'		 => true,
					),
				),
			);

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Header_Sticky_Menu.
 * @return Reign_Kirki_Header_Sticky_Menu
 */
Reign_Kirki_Header_Sticky_Menu::instance();
