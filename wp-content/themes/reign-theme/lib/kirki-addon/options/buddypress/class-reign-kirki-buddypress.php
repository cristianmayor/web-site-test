<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Reign_Kirki_BuddyPress')) :

    /**
     * @class Reign_Kirki_BuddyPress
     */
    class Reign_Kirki_BuddyPress {

        /**
         * The single instance of the class.
         *
         * @var Reign_Kirki_BuddyPress
         */
        protected static $_instance = null;

        /**
         * Main Reign_Kirki_BuddyPress Instance.
         *
         * Ensures only one instance of Reign_Kirki_BuddyPress is loaded or can be loaded.
         *
         * @return Reign_Kirki_BuddyPress - Main instance.
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Reign_Kirki_BuddyPress Constructor.
         */
        public function __construct() {
            $this->init_hooks();
            $this->includes();
        }

        public function includes() {
            
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
                'reign_buddypress_panel', array(
                    'priority' => 200,
                    'title' => __('BuddyPress Icons', 'reign'),
                    'description' => '',
                )
            );

            $wp_customize->add_section(
                'reign_member_single_settings', array(
                    'title' => __('BuddyPress Member Single', 'reign'),
                    'priority' => 10,
                    'panel' => 'reign_buddypress_panel',
                    'description' => '',
                )
            );

            $wp_customize->add_section(
                'reign_group_single_settings', array(
                    'title' => __('BuddyPress Group Single', 'reign'),
                    'priority' => 10,
                    'panel' => 'reign_buddypress_panel',
                    'description' => '',
                )
            );
        }

        public function add_fields($fields) {

            $default_value_set = reign_get_customizer_default_value_set();

            $fields[] = array(
                'type' => 'select',
                'settings' => 'buddypress_single_member_nav_style',
                'label' => esc_html__('Single Member Navigation Style', 'reign'),
                'section' => 'reign_member_single_settings',
                'default' => 'iconic',
                'choices' => array(
                    'default' => esc_attr__('Default', 'reign'),
                    'iconic' => esc_attr__('Icon + Label', 'reign'),
                ),
            );

            $fields[] = array(
                'type' => 'select',
                'settings' => 'buddypress_single_group_nav_style',
                'label' => esc_html__('Single Group Navigation Style', 'reign'),
                'section' => 'reign_group_single_settings',
                'default' => 'iconic',
                'choices' => array(
                    'default' => esc_attr__('Default', 'reign'),
                    'iconic' => esc_attr__('Icon + Label', 'reign'),
                ),
            );

            return $fields;
        }

    }

    endif;

/**
 * Main instance of Reign_Kirki_BuddyPress.
 * @return Reign_Kirki_BuddyPress
 */
Reign_Kirki_BuddyPress::instance();
