<?php

namespace WBCOM_ESSENTIAL;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( WBCOMESSENTIAL::class ) ) {

	final class WBCOMESSENTIAL {

		/**
		 * Plugin instance.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @var Plugin
		 */
		public static $instance;

		/**
		 * Disables class cloning and throw an error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object. Therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'stax-buddy-builder' ), BPB_VERSION );
		}

		/**
		 * Disables unserializing of the class.
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'stax-buddy-builder' ), BPB_VERSION );
		}

		/**
		 * Ensures only one instance of the plugin class is loaded or can be loaded.
		 *
		 * @return Plugin An instance of the class.
		 * @since 1.0.0
		 * @access public
		 * @static
		 */
		public static function get_instance() {
			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function __construct() {
			$this->includes();
		}

		/**
		 * Include plugin files
		 */
		public function includes() {
			require __DIR__ . '/includes/wbcom-essential-function.php';
			if ( did_action( 'elementor/loaded' ) ) {
				require __DIR__ . '/plugins/elementor/Plugins.php';
			}
		}

	}
}
