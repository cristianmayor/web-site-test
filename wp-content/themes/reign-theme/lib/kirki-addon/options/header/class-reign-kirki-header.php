<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Reign_Kirki_Header')) :

    /**
     * @class Reign_Kirki_Header
     */
    class Reign_Kirki_Header {

        /**
         * The single instance of the class.
         *
         * @var Reign_Kirki_Header
         */
        protected static $_instance = null;

        /**
         * Main Reign_Kirki_Header Instance.
         *
         * Ensures only one instance of Reign_Kirki_Header is loaded or can be loaded.
         *
         * @return Reign_Kirki_Header - Main instance.
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Reign_Kirki_Header Constructor.
         */
        public function __construct() {
            $this->init_hooks();
            $this->includes();
        }

        public function includes() {
            //include_once 'class-reign-kirki-header-main-menu.php';
            //include_once 'class-reign-kirki-header-sub-menu.php';
            include_once 'class-reign-kirki-header-sticky-menu.php';
            include_once 'class-reign-kirki-header-mobile-menu.php';
            include_once 'class-reign-kirki-header-topbar.php';
        }

        /**
         * Hook into actions and filters.
         */
        private function init_hooks() {
            add_action('customize_register', array($this, 'add_panels_and_sections'));
            add_filter('kirki/fields', array($this, 'add_fields'));
        }

        public function add_panels_and_sections($wp_customize) {

            $wp_customize->add_panel(
                    'reign_header_panel', array(
                'priority' => 21,
                'title' => __('Desktop Header', 'reign'),
                'description' => '',
                    )
            );

            $wp_customize->add_section(
                    'reign_header_style', array(
                'title' => __('Layout', 'reign'),
                'priority' => 10,
                'panel' => 'reign_header_panel',
                'description' => '',
                    )
            );
        }

        public function add_fields($fields) {

            $default_value_set = reign_get_customizer_default_value_set();

            $fields[] = array(
                'type' => 'radio-image',
                'settings' => 'reign_header_layout',
                'label' => esc_attr__('Layout', 'reign'),
                'description' => esc_attr__('Select header layout for header.', 'reign'),
                'section' => 'reign_header_style',
                'default' => 'v2',
                'priority' => 10,
                'choices' => apply_filters('reign_theme_header_choices', array(
                    'v1' => REIGN_THEME_URI . '/lib/images/header-v1.jpg',
                    'v2' => REIGN_THEME_URI . '/lib/images/header-v2.jpg',
                    'v3' => REIGN_THEME_URI . '/lib/images/header-v3.jpg',
                    'v4' => REIGN_THEME_URI . '/lib/images/header-v4.png',
                )),
                'active_callback' => array(
                    array(
                        'setting' => 'reign_header_header_type',
                        'operator' => '!==',
                        'value' => true,
                    ),
                ),
            );

            if (class_exists('WooCommerce') && class_exists('WC_Widget_Product_Search')) {
                $fields[] = array(
                    'type' => 'select',
                    'settings' => 'reign_header_search_option',
                    'label' => esc_attr__('Header Search Option', 'reign'),
                    'description' => esc_attr__('Select search option for header layout 4.', 'reign'),
                    'section' => 'reign_header_style',
                    'default' => 'product_search',
                    'priority' => 10,
                    'choices' => array(
                        'product_search' => esc_attr__('Product Search', 'reign'),
                        'default_search' => esc_attr__('Default Search', 'reign'),
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_header_layout',
                            'operator' => '==',
                            'value' => 'v4',
                        ),
                    ),
                );
            }


            $fields[] = array(
                'type' => 'sortable',
                'settings' => 'reign_header_icons_set',
                'label' => esc_attr__('Header Icons Options', 'reign'),
                'description' => '',
                'section' => 'reign_header_style',
                'priority' => 10,
                'default' => $default_value_set['reign_header_icons_set'],
                'choices' => array(
                    'search' => esc_html__('Search', 'reign'),
                    'cart' => esc_html__('Cart', 'reign'),
                    'message' => esc_html__('Message', 'reign'),
                    'notification' => esc_html__('Notification', 'reign'),
                    'user-menu' => esc_html__('User Menu', 'reign'),
                    'login' => esc_html__('Login', 'reign'),
                    'register-menu' => esc_html__('Register', 'reign'),
                ),
                /* comment below to make layout meun icon section available for elementor header */
                'active_callback' => array(
                    array(
                        'setting' => 'reign_header_header_type',
                        'operator' => '!==',
                        'value' => true,
                    ),
                ),
            );

            $fields[] = array(
                'type' => 'switch',
                'settings' => 'reign_header_main_menu_more_enable',
                'label' => esc_attr__('Enable \'More\' menus wrap in header menu.', 'reign'),
                'description' => esc_attr__('Enable or Disable \'More\' menus option for header menu.', 'reign'),
                'section' => 'reign_header_style',
                'default' => 0,
                'priority' => 10,
                'choices' => array(
                    'on' => esc_attr__('Enable', 'reign'),
                    'off' => esc_attr__('Disable', 'reign'),
                ),
            );

            return $fields;
        }

    }

    endif;

/**
 * Main instance of Reign_Kirki_Header.
 * @return Reign_Kirki_Header
 */
Reign_Kirki_Header::instance();
