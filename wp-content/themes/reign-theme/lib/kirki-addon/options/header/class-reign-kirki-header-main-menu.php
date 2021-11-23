<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Header_Main_Menu' ) ) :

	/**
	 * @class Reign_Kirki_Header_Main_Menu
	 */
	class Reign_Kirki_Header_Main_Menu {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Header_Main_Menu
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Header_Main_Menu Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Header_Main_Menu is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Header_Main_Menu - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Header_Main_Menu Constructor.
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
			'reign_header_main_menu', array(
				'title'			 => __( 'Main Menu', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_header_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Header_Main_Menu.
 * @return Reign_Kirki_Header_Main_Menu
 */
Reign_Kirki_Header_Main_Menu::instance();
