<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Reign_Kirki_Colors')) :

    /**
     * @class Reign_Kirki_Colors
     */
    class Reign_Kirki_Colors {

        /**
         * The single instance of the class.
         *
         * @var Reign_Kirki_Colors
         */
        protected static $_instance = null;

        /**
         * Main Reign_Kirki_Colors Instance.
         *
         * Ensures only one instance of Reign_Kirki_Colors is loaded or can be loaded.
         *
         * @return Reign_Kirki_Colors - Main instance.
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Reign_Kirki_Colors Constructor.
         */
        public function __construct() {
            $this->init_hooks();
        }

        /**
         * Hook into actions and filters.
         */
        private function init_hooks() {

            /* remove default background color from theme. */
            add_action('customize_register', array($this, 'reign_remove_wp_background_color'));

            add_filter('kirki/fields', array($this, 'reign_add_fields'));
            //add_filter( 'kirki/fields', array( $this, 'add_fields' ) );

            add_action('init', array($this, 'reign_map_color_scheme_values'));
        }

        public function reign_remove_wp_background_color($wp_customize) {
            $wp_customize->remove_control('background_color');
        }

        public function reign_add_fields($fields) {

            $default_value_set = reign_color_scheme_set();

            $selector_for_background = '';
            $selector_for_background = apply_filters('reign_selector_set_to_apply_theme_color_to_background', $selector_for_background);

            $selector_for_boder = '';
            $selector_for_boder = apply_filters('reign_selector_set_to_apply_theme_color_to_border', $selector_for_boder);

            $selector_for_section_bg = '';
            $selector_for_section_bg = apply_filters('reign_selector_set_to_apply_section_bg_color', $selector_for_section_bg);

            $selector_for_border_color = '';
            $selector_for_border_color = apply_filters('reign_selector_set_to_apply_border_color', $selector_for_border_color);

            $fields[] = array(
                'type' => 'radio-buttonset',
                'settings' => 'reign_color_scheme',
                'label' => __('Color Scheme', 'reign'),
                'section' => 'colors',
                'default' => 'reign_default',
                'priority' => 10,
                'choices' => [
                    'reign_default' => esc_html__('Default', 'reign'),
                    'reign_clean' => esc_html__('Clean', 'reign'),
                    'reign_dark' => esc_html__('Dark', 'reign'),
                    'reign_ectoplasm' => esc_html__('Ectoplasm', 'reign'),
                    'reign_sunrise' => esc_html__('Sunrise', 'reign'),
                    'reign_coffee' => esc_html__('Coffee', 'reign'),
                ],
            );

            foreach ($default_value_set as $color_scheme_key => $default_set) {

                // Top Bar Color Scheme
                $fields_on_hold = array();
                $fields_on_hold[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_topbar_bg_color',
                    'label' => esc_attr__('Top Bar Background Color', 'reign'),
                    'description' => esc_attr__('The background color of topbar.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_topbar_bg_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.reign-header-top',
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields_on_hold[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_topbar_text_color',
                    'label' => esc_attr__('Top Bar Text Color', 'reign'),
                    'description' => esc_attr__('The color of topbar text.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_topbar_text_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.reign-header-top, .reign-header-top a',
                            'property' => 'color',
                        ),
                        array(
                            'element' => '.reign-header-top .header-top-left span',
                            'property' => 'border-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );


                $fields_on_hold[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_topbar_text_hover_color',
                    'label' => esc_attr__('Top Bar Text Color [Hover]', 'reign'),
                    'description' => esc_attr__('The color of topbar text hover.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_topbar_text_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.reign-header-top a:hover',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields_on_hold = apply_filters('reign_header_topbar_fields_on_hold', $fields_on_hold);

                foreach ($fields_on_hold as $key => $value) {
                    $fields[] = $value;
                }

                // Header Color Scheme: Header BG
                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_bg_color',
                    'label' => esc_attr__('Header Background Color', 'reign'),
                    'description' => esc_attr__('The background color of header.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_bg_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'body #masthead.site-header',
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                // Header Color Scheme: Header BG
                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_nav_bg_color',
                    'label' => esc_attr__('Header Layout 4 Navigation Background Color', 'reign'),
                    'description' => esc_attr__('The background color only for header layout 4 navigation.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_nav_bg_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.version-four .rg-hdr-v4-row-2, body.reign-header-v4 #masthead.sticky.site-header',
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                        array(
                            'setting' => 'reign_header_layout',
                            'operator' => '==',
                            'value' => 'v4',
                        ),
                    ),
                );

                // Header Color Scheme: Header Site Title
                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_title_tagline_typography',
                    'label' => esc_attr__('Site Title Font Color', 'reign'),
                    'description' => esc_attr__('The color of site title.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_title_tagline_typography'],
                    'priority' => 10,
                    'output' => array(
                        array(
                            'element' => '.site-branding .site-title a',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                // Header Color Scheme: Header Main Menu
                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_main_menu_font',
                    'label' => esc_attr__('Main Menu Item Font Color', 'reign'),
                    'description' => esc_attr__('The color of header main menu item.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_main_menu_font'],
                    'priority' => 10,
                    'output' => array(
                        array(
                            'element' => '#masthead.site-header .main-navigation .primary-menu > li a, #masthead .user-link-wrap .user-link, #masthead .psw-userbar__name>a',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );


                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_main_menu_text_hover_color',
                    'label' => esc_attr__('Main Menu Item Font Color [Hover]', 'reign'),
                    'description' => esc_attr__('The color of header main menu item hover.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_main_menu_text_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '#masthead.site-header .main-navigation .primary-menu > li a:hover, #masthead .user-link-wrap .user-link:hover, #masthead .psw-userbar__name>a:hover',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_main_menu_text_active_color',
                    'label' => esc_attr__('Main Menu Item Font Color [Active]', 'reign'),
                    'description' => esc_attr__('The color of header main menu item active.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_main_menu_text_active_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '#masthead.site-header .main-navigation .primary-menu > li.current-menu-item a',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_main_menu_bg_hover_color',
                    'label' => esc_attr__('Main Menu Item Background Color [Hover]', 'reign'),
                    'description' => esc_attr__('The background color of header main menu item hover.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_main_menu_bg_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.primary-menu > li a:hover:before, .version-one .primary-menu > li a:hover:before, .version-two .primary-menu > li a:hover:before, .version-three .primary-menu > li a:hover:before, .version-one .primary-menu > li a:before, .version-two .primary-menu > li a:before, .version-three .primary-menu > li a:before',
                            'property' => 'background',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_main_menu_bg_active_color',
                    'label' => esc_attr__('Main Menu Item Background Color [Active]', 'reign'),
                    'description' => esc_attr__('The background color of header main menu item active.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_main_menu_bg_active_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.primary-menu > li.current-menu-item a:before, .version-one .primary-menu > li.current-menu-item a:before, .version-two .primary-menu > li.current-menu-item a:before, .version-three .primary-menu > li.current-menu-item a:before',
                            'property' => 'background',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                // Header Color Scheme: Header Sub Menu
                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_sub_menu_bg_color',
                    'label' => esc_attr__('Sub Menu Item Background Color', 'reign'),
                    'description' => esc_attr__('The background color of header sub menu.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_sub_menu_bg_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '#primary-menu .children, #primary-menu .sub-menu, #primary-menu .children:after, #primary-menu .sub-menu:after, #primary-menu ul li ul li a, #masthead .user-profile-menu, #masthead .user-profile-menu:after, #masthead .user-profile-menu li ul.sub-menu, #masthead .user-profile-menu li ul.sub-menu:before, .ps-dropdown--menu .ps-dropdown__menu>a',
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_sub_menu_font',
                    'label' => esc_attr__('Sub Menu Item Font Color', 'reign'),
                    'description' => esc_attr__('The color of header sub menu item.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_sub_menu_font'],
                    'priority' => 10,
                    'output' => array(
                        array(
                            'choice' => 'color',
                            'element' => '#masthead.site-header .main-navigation .primary-menu > li .sub-menu li a, #masthead .user-profile-menu li > a, .ps-dropdown--menu .ps-dropdown__menu>a, .ps-dropdown--menu .ps-dropdown__menu i',
                            'property' => 'color',
                        ),
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_sub_menu_text_hover_color',
                    'label' => esc_attr__('Sub Menu Item Font Color [Hover]', 'reign'),
                    'description' => esc_attr__('The color of header sub menu item hover.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_sub_menu_text_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => '#masthead.site-header .main-navigation .primary-menu ul li a:hover, #masthead.site-header.sticky .main-navigation .primary-menu ul li a:hover, #masthead .user-profile-menu li > a:hover, .ps-dropdown--menu .ps-dropdown__menu>a:hover, .ps-dropdown--menu .ps-dropdown__menu a:hover i',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_sub_menu_bg_hover_color',
                    'label' => esc_attr__('Sub Menu Item Background Color [Hover]', 'reign'),
                    'description' => esc_attr__('The background color of header sub menu item hover.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_sub_menu_bg_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => '#masthead.site-header .main-navigation .primary-menu ul li a:hover, #masthead .user-profile-menu li > a:hover, .ps-dropdown--menu .ps-dropdown__menu>a:hover',
                            'property' => 'background',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                // Header Color Scheme: Header Icon
                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_icon_color',
                    'label' => esc_attr__('Header Icon Color', 'reign'),
                    'description' => esc_attr__('The color of header icon.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_icon_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.rg-search-icon:before, .rg-icon-wrap span:before, #masthead .rg-icon-wrap, #masthead .ps-user-name, #masthead .ps-dropdown--userbar .ps-dropdown__toggle, #masthead .ps-widget--userbar__logout>a, #masthead .version-four .user-link-wrap .user-link, #masthead .psw-userbar__menu-toggle, #masthead .psw-userbar__logout',
                            'property' => 'color',
                        ),
                        array(
                            'element' => '.wbcom-nav-menu-toggle span',
                            'property' => 'background',
                        ),
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_header_icon_hover_color',
                    'label' => esc_attr__('Header Icon Color [Hover]', 'reign'),
                    'description' => esc_attr__('The color of header icon hover.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_header_icon_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.rg-search-icon:hover:before, .rg-icon-wrap span:hover:before, #masthead .rg-icon-wrap:hover, #masthead .ps-user-name:hover, #masthead .ps-dropdown--userbar .ps-dropdown__toggle:hover, #masthead .ps-widget--userbar__logout>a:hover, #masthead .version-four .user-link-wrap .user-link:hover, #masthead .psw-userbar__menu-toggle:hover, #masthead .psw-userbar__logout:hover',
                            'property' => 'color',
                        ),
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_site_body_bg_color',
                    'label' => esc_attr__('Body Background Color', 'reign'),
                    'description' => esc_attr__('The background color of site body.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_site_body_bg_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => 'body:not(.elementor-page), .edd-rvi-wrapper-single, .edd-rvi-wrapper-checkout, #edd-rp-single-wrapper, #edd-rp-checkout-wrapper, .edd-sd-share, #isa-related-downloads, .edd_review, .edd-reviews-form-inner, body .lm-distraction-free-reading, .rlla-distraction-free-reading-active .rlla-distraction-free-reading, .bp-nouveau .bp-single-vert-nav .bp-navs.vertical li:focus, .bp-nouveau .bp-single-vert-nav .bp-navs.vertical li:hover',
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_site_body_text_color',
                    'label' => esc_attr__('Body Text Color', 'reign'),
                    'description' => esc_attr__('The color of body text.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_site_body_text_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => 'body:not(.elementor-page), body:not(.elementor-page) p, body #masthead p, .rg-woocommerce_mini_cart ul.woocommerce-mini-cart li .quantity, #buddypress .field-visibility-settings, #buddypress .field-visibility-settings-notoggle, #buddypress .field-visibility-settings-toggle, #buddypress .standard-form p.description',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    ),
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_site_sections_bg_color',
                    'label' => esc_attr__('Sections Background Color', 'reign'),
                    'description' => esc_attr__('The background color of sections.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_site_sections_bg_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '.search-wrap .rg-search-form-wrap, [off-canvas], .rg-dropdown, .user-notifications .rg-dropdown:after, .user-notifications:hover .rg-dropdown:after, #masthead .rg-woocommerce_mini_cart, #masthead .rg-woocommerce_mini_cart:after, #masthead .rg-edd_mini_cart, #masthead .rg-edd_mini_cart:after, .blog .default-view, .blog .thumbnail-view, .blog .wb-grid-view, .archive .default-view, .archive .thumbnail-view, .archive .wb-grid-view, .masonry .masonry-view, .search .post, .search .hentry, .widget-area-inner .widget, .widget-area .widget, .bp-widget-area .widget, .bp-plugin-widgets, #buddypress .activity .item-list > li, body.activity #buddypress #item-body div.item-list-tabs#subnav, body.group-home #buddypress #item-body div.item-list-tabs#subnav, #buddypress #whats-new-textarea, #buddypress #whats-new-content #whats-new-options, #buddypress #whats-new-content.active #whats-new-options, #buddypress form#whats-new-form textarea, .woocommerce ul.products li.product, .bp-inner-wrap, .bp-group-inner-wrap, #buddypress div.pagination .pagination-links a, #buddypress div.pagination .pagination-links span, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown:after, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown:after, body:not(.activity) .inner-item-body-wrap, .bp-nouveau .activity-update-form, body.bp-nouveau.activity-modal #bp-nouveau-activity-form, body.bp-nouveau.activity-modal #bp-nouveau-activity-form:not(.modal-popup):not(.bp-activity-edit), .bp-nouveau #buddypress form#whats-new-form textarea, .bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links), .bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links), .activity-list .activity-item .activity-content .activity-inner, .activity-list .activity-item .activity-content blockquote, .activity-list .activity-item .activity-meta.action, .bp-nouveau .buddypress-wrap form.bp-dir-search-form button[type=submit], .bp-nouveau .buddypress-wrap form.bp-invites-search-form button[type=submit], .bp-nouveau .buddypress-wrap form.bp-messages-search-form button[type=submit], .bp-nouveau .buddypress-wrap form#media_search_form button[type=submit], .bp-nouveau #buddypress div.bp-pagination .bp-pagination-links a, .bp-nouveau #buddypress div.bp-pagination .bp-pagination-links span, .bp-nouveau .bp-list:not(.grid) > li, .buddypress-wrap .bp-feedback, .bp-nouveau .grid.bp-list > li .list-wrap, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action:after, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action:after, .bp-nouveau .buddypress-wrap .bp-tables-user tbody tr, .bp-nouveau .buddypress-wrap table.forum tbody tr, .bp-nouveau .buddypress-wrap .profile, .bp-nouveau .bp-messages-content, .bp-nouveau .bupr-bp-member-reviews-block, .bp-nouveau .bp-member-add-form, .media .rtmedia-container, .bp-nouveau .bptodo-adming-setting, .bp-nouveau .bptodo-form-add, .bp-nouveau #send-invites-editor, .bp-nouveau form#group-settings-form, .bp-nouveau form#settings-form, .bp-nouveau form#account-group-invites-form, .bp-nouveau form#account-capabilities-form, .bp-nouveau .buddypress-wrap table.wp-profile-fields tbody tr, .buddypress-wrap .profile.edit .editfield, .buddypress-wrap .standard-form .description, .bp-nouveau .bp-messages-content #thread-preview, .bp-nouveau .buddypress-wrap table.notification-settings, .bp-messages-content #thread-preview .preview-content .preview-message, .bp-nouveau #message-threads, .rtmedia-uploader .drag-drop, .bp-nouveau .groups-header .desc-wrap .group-description, .bp-nouveau .bp-single-vert-nav .bp-navs.vertical:not(.tabbed-links) ul, .buddypress-wrap:not(.bp-single-vert-nav) .bp-navs:not(.group-create-links) li, .bp-nouveau #buddypress div#item-header.single-headers #item-header-cover-image #item-header-content #item-buttons, .bp-nouveau #buddypress div#item-header.single-headers #item-header-cover-image #item-header-content #item-buttons:after, .rg-nouveau-sidebar-menu, #buddypress div#invite-list, #bptodo-tabs, .bplock-login-form-container .tab-content, ul.bplock-login-shortcode-tabs li.current, #buddypress #cover-image-container.wbtm-cover-header-type-3, .bp-nouveau #buddypress #cover-image-container.wbtm-cover-header-type-3, .comment-list article, .commentlist article, .comment-list .pingback, .comment-list .trackback, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, #add_payment_method #payment div.payment_box, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, .woocommerce-error, .woocommerce-info, .woocommerce-message, .select2-dropdown, nav.fes-vendor-menu, .single-download article, .rtm-download-item-bottom, .bp-nouveau .badgeos-achievements-list-item, #edd_checkout_wrap, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a, .woocommerce div.product .woocommerce-tabs .panel, .woocommerce #content div.product div.summary, .woocommerce div.product div.summary, .woocommerce-page #content div.product div.summary, .woocommerce-page div.product div.summary, .wbtm-member-directory-type-4 .item-wrapper, .wbtm-group-directory-type-4 .group-content-wrap, .mycred-table, .bp-nouveau .groups-type-navs, .fes-vendor-dashboard, .bp-nouveau .bp-vertical-navs .rg-nouveau-sidebar-menu, .bp-nouveau .buddypress-wrap .select-wrap, #bbpress-forums ul.bbp-forums, #bbpress-forums div.odd, #bbpress-forums ul.odd, #bbpress-forums div.even, #bbpress-forums ul.even, .bbp-topic-form, #bbpress-forums .bbp-login-form fieldset.bbp-form, .bbp-reply-form, #bbpress-forums .rg-replies .bbp-body > div, #bbpress-forums div.bbp-forum-header, #bbpress-forums div.bbp-topic-header, #bbpress-forums div.bbp-reply-header, #bbpress-forums .forums.bbp-search-results .bbp-body > div, #bbpress-forums #bbp-single-user-details #bbp-user-navigation, .ps-navbar, .ps-member, .ps-tabs__item, .ps-tabs__item:hover, .ps-tabs__item:focus, .ps-members__header, .ps-members__filters, .ps-input.ps-input--select:read-only, .ps-input.ps-input--select:read-only:hover, .ps-input.ps-input--select:read-only:focus, .ps-dropdown__menu, .ps-notif__box, .ps-input:hover, .ps-btn--app, .ps-tip.ps-btn:not(.ps-button-cancel):hover, .ps-tip.ps-btn:not(.ps-button-cancel):focus, .ps-tip.ps-btn:not(.ps-button-cancel) .active, .ps-group, .ps-postbox, .ps-post, .ps-post__options-menu .ps-dropdown__menu, .ps-comments__list, .ps-comments__input, .ps-comments__input:hover, .ps-comments__input:focus, .ps-reactions__dropdown, .ps-reactions__likes, .ps-posts__filters-group, .ps-focus__info, .ps-focus__menu, .ps-profile__edit, .ps-posts__filter-box, .ps-posts__filter-search, .ps-input:disabled, .ps-input:read-only, .ps-input.ps-input--disabled, .ps-friends__tab--active, .ps-friends__tab, .ps-modal__content, .ps-modal__footer, .ps-group__edit-fields, .bb-activity-media-wrap .bb-activity-media-elem.document-activity, .bb-activity-media-wrap .bb-activity-media-elem.document-activity .document-description-wrap, .bb-activity-media-wrap .bb-activity-media-elem.document-activity:hover, .bb-activity-media-wrap .bb-activity-media-elem.document-activity .document-action-wrap .document-action_list, .rg-nouveau-sidebar-menu .sub-menu, #group-invites-container, #item-body #group-invites-container li.selected, .bb-media-model-inner, .bb-media-info-section .activity-comments > ul, #bp-message-thread-list, #bp-message-thread-list li, .activity-list .activity-item .bp-generic-meta.action, .bp-nouveau .buddypress-wrap .rg-nouveau-sidebar-menu, .ps-badgeos__list-wrapper, #component .badgeos_achievement_main_container, #component .badgeos_ranks_main_container' . $selector_for_section_bg,
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                if (is_plugin_active('dokan-pro/dokan-pro.php')) {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_colors_theme',
                        'label' => esc_attr__('Theme Color', 'reign'),
                        'description' => esc_attr__('The color of primary color, active color.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_colors_theme'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'function' => 'css',
                                'element' => '.woocommerce nav.woocommerce-pagination ul li .page-numbers:not(.current):hover, .woocommerce-page nav.woocommerce-pagination ul li .page-numbers:not(.current):hover, .widget_categories ul li:before, .widget_archive ul li:before, .widget.widget_nav_menu ul li:before, .widget.widget_meta ul li:before, .widget.widget_recent_comments ul li:before, .widget_rss ul li:before, .widget_pages ul li:before, .widget.widget_links ul li:before, .widget.widget_recent_entries ul li:before, ul.pmpro_billing_info_list li:before, .widget_edd_categories_tags_widget ul li:before, .widget_edd_cart_widget ul li:before, #buddypress div.item-list-tabs ul li.current a, #buddypress div.item-list-tabs ul li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.current a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a:focus,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a:hover,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a:focus,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a:hover,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li a:hover,.buddypress-wrap.bp-vertical-navs .dir-navs.activity-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.groups-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.members-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.sites-nav-tabs ul li.selected a,
								.buddypress-wrap.bp-vertical-navs .main-navs.group-nav-tabs ul li.selected a,
								.buddypress-wrap.bp-vertical-navs .main-navs.user-nav-tabs ul li.selected a,
								.bp-dir-vert-nav .dir-navs ul li.selected a,
								.bp-single-vert-nav .bp-navs.vertical li.selected a,
								.buddypress-wrap .bp-navs.tabbed-links ul li.current a,
								.bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li.current a,
								.bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li a:hover,
								.buddypress-wrap .bp-navs.tabbed-links ul li a:hover,
								.buddypress-wrap .bp-navs li:not(.selected) a:hover,
                                                                
                                                                .bp-nouveau.buddypress #buddypress:not(.bp-vertical-navs) .groups-nav li.selected a:hover, .bp-nouveau.buddypress #buddypress:not(.bp-vertical-navs) .groups-nav li a:hover, .bp-nouveau.buddypress #buddypress:not(.bp-vertical-navs) .groups-nav li.selected a:focus, .bp-nouveau.buddypress #buddypress:not(.bp-vertical-navs) .groups-nav li.selected a,

								.buddypress-wrap .bp-navs li.current a,
								.buddypress-wrap .bp-navs li.current a:focus,
								.buddypress-wrap .bp-navs li.current a:hover,
								.buddypress-wrap .bp-navs li.selected a,
								.buddypress-wrap .bp-navs li.selected a:focus,
								.buddypress-wrap .bp-navs li.selected a:hover,
								.widget-area .widget.buddypress div.item-options a.selected,
								footer div.footer-wrap a:hover,
								footer .widget-area .widget.buddypress div.item-options a:hover,
								footer .widget-area .widget.buddypress div.item-options a.selected,
								.wbtm-member-directory-type-1 .action-wrap:hover,
								.wbtm-member-directory-type-3 .action-wrap:hover,
								.bp-nouveau .wbtm-member-directory-type-1 .action-wrap:hover,
								.bp-nouveau .wbtm-member-directory-type-3 .action-wrap:hover,
								.woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a,
								.fes-vendor-menu ul li.active a, .fes-vendor-menu .edd-tabs li.active a, .fes-vendor-menu .edd-tabs li.active .icon, .fes-vendor-menu .edd-tabs li:hover a, .fes-vendor-menu .edd-tabs li:hover .icon, .rg-woo-breadcrumbs a.current,
								#bbpress-forums .bbp-topic-pagination a:hover, #bbpress-forums .bbp-pagination-links a:hover,
								#bbpress-forums #bbp-single-user-details #bbp-user-navigation li.current a,
								.tribe-common.tribe-events .tribe-events-calendar-list__event-date-tag-daynum,
								.tribe-common.tribe-events .tribe-events-calendar-list__event-date-tag-weekday,
								.tribe-events .tribe-events-c-nav__next:focus,
								.tribe-events .tribe-events-c-nav__next:hover,
								.tribe-events .tribe-events-c-nav__prev:focus,
								.tribe-events .tribe-events-c-nav__prev:hover,
								.tribe-events .tribe-events-c-ical__link,
								.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date,
								.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link,
								.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link:focus, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link:hover, .tribe-events .tribe-events-calendar-month__day-date-link:focus, .tribe-events .tribe-events-calendar-month__day-date-link:hover,
								#tribe_events_filters_wrapper.tribe-events-filters-horizontal .tribe_events_filter_control button:hover,
								.tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum,
								.tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link,
								.tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link:focus,
								.tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link:hover,
								.tribe-events .tribe-events-c-nav__today:focus,
								.tribe-events .tribe-events-c-nav__today:hover,
								.tribe-common .tribe-common-cta--thin-alt:active,
								.tribe-common .tribe-common-cta--thin-alt:focus,
								.tribe-common .tribe-common-cta--thin-alt:hover,
								.single-tribe_events .rg-event-heading .tribe-event-schedule-short .rg-schedule-short-m,
								.single-tribe_events .rg-event-heading .tribe-event-schedule-short .rg-schedule-short-d,
                                                                .buddypress-wrap .grid-filters a.active,
                                                                .ps-tabs__item--active>a, .ps-tabs__item>a:hover, .ps-tabs__item>a:focus, .ps-tip.ps-btn--active, .ps-focus__menu-item--active, .ps-tabs__item--active i, .ps-friends__tab--active>a, .psw-profile__menu-item i, .ps-tabs .current.slick-slide a, .ps-postbox__menu-item--type .ps-postbox__menu-item-link>.ps-icon.active:before',
                                'property' => 'color',
                            ),
                            array(
                                'function' => 'css',
                                'element' => '.rg-posts-navigation .nav-links .page-numbers.current, #bbpress-forums .bbp-pagination-links span.current, .rg-count, h3.lm-header-title:after, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_pmpro_price_top, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_levels_table_button .pmpro_btn, .woocommerce div.product .woocommerce-tabs ul.tabs li.active:before, #pmpro_account-membership .pmpro_actionlinks a, #pmpro_account-profile .pmpro_actionlinks a, #pmpro_cancel .pmpro_actionlinks a, #pmpro_form .pmpro_btn,

							.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links) li:after,
							.bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li:after,

							.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links) li:hover:after,
							.bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li:hover:after,
							.bp-nouveau .groups-type-navs li:after, .bp-nouveau .groups-type-navs li:hover:after,

							.buddypress-wrap .bp-navs li.current a .count,
							.buddypress-wrap .bp-navs li.selected a .count,
							.buddypress_object_nav .bp-navs li.current a .count,
							.buddypress_object_nav .bp-navs li.selected a .count,
							.bp-nouveau .rtm-bp-navs.bp-navs ul.subnav li.selected span,

							.buddypress-wrap.bp-vertical-navs .dir-navs.activity-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.groups-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.members-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.sites-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .main-navs.group-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .main-navs.user-nav-tabs ul li.selected a span,
							.widget_edd_cart_widget p.edd-cart-number-of-items span.edd-cart-quantity,
							.single-download .type-download input[type="radio"]:checked + label span:before, .single-download .type-download .edd_price_options label:before, label.selectit:before,
							input[type="radio"]:checked + label span:before, .edd_price_options label:before, label.selectit:before,
							.fes-vendor-menu ul li.active a:after, .fes-vendor-menu ul li a:hover:after, #component #edd_user_history th, .fes-table th, .edd-table th, .fes-vendor-dashboard table th, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link a:before,
							
							.woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce-page nav.woocommerce-pagination ul li span.current,
							#bbpress-forums #bbp-single-user-details #bbp-user-avatar,
							.tribe-events .datepicker .day.active, .tribe-events .datepicker .day.active.focused, .tribe-events .datepicker .day.active:focus, .tribe-events .datepicker .day.active:hover, .tribe-events .datepicker .month.active, .tribe-events .datepicker .month.active.focused, .tribe-events .datepicker .month.active:focus, .tribe-events .datepicker .month.active:hover, .tribe-events .datepicker .year.active, .tribe-events .datepicker .year.active.focused, .tribe-events .datepicker .year.active:focus, .tribe-events .datepicker .year.active:hover,
							.tribe-events .tribe-events-c-ical__link:active, .tribe-events .tribe-events-c-ical__link:focus, .tribe-events .tribe-events-c-ical__link:hover,
							.tribe-events .tribe-events-calendar-month__mobile-events-icon--event,
                                                        .ps-focus__menu-item--active:after, .ps-notif__bubble, .psw-profile__progress-bar>span, #component #badgeos-earned-achievements-container .selected, #component #badgeos-achievements-container .selected, #component #badgeos-earned-ranks-container .selected, #component #badgeos-list-ranks-container .selected' . $selector_for_background,
                                'property' => 'background',
                                'suffix' => ' !important',
                            ),
                            array(
                                'function' => 'css',
                                'element' => '.rg-link-block, .rg-posts-navigation .nav-links .page-numbers.current, #bbpress-forums .bbp-pagination-links span.current, #bbpress-forums .bbp-topic-pagination a:hover, #bbpress-forums .bbp-pagination-links a:hover, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce-page nav.woocommerce-pagination ul li span.current, .woocommerce nav.woocommerce-pagination ul li .page-numbers:hover, .woocommerce-page nav.woocommerce-pagination ul li .page-numbers:hover, .widget-title span, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_pmpro_price_top, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_levels_table_button .pmpro_btn, #pmpro_account-membership .pmpro_actionlinks a, #pmpro_account-profile .pmpro_actionlinks a, #pmpro_cancel .pmpro_actionlinks a, #pmpro_form .pmpro_btn, body #buddypress #item-body div.item-list-tabs#subnav li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.current a, #buddypress div.activity-comments form textarea:focus, body #buddypress div.activity-comments ul li form textarea:focus, .single-download .type-download .edd_price_options input[type="radio"] + label span:after, .single-download .type-download .edd_price_options label:after, .edd_price_options input[type="radio"] + label span:after, .edd_price_options label:after, .edd_pagination span.current, .rg-has-border, .woocommerce div.product div.images .flex-control-thumbs li img.flex-active, .woocommerce div.product div.images .flex-control-thumbs li img:hover,
									.tribe-common.tribe-events .tribe-events-calendar-list__event-date-tag, .tribe-common .tribe-common-anchor-thin:active, .tribe-common .tribe-common-anchor-thin:focus, .tribe-common .tribe-common-anchor-thin:hover, .tribe-events .tribe-events-c-ical__link, .tribe-events-pro .tribe-events-pro-week-day-selector__day--active,
									.tribe-common .tribe-common-cta--thin-alt:active,
									.tribe-common .tribe-common-cta--thin-alt:focus,
									.tribe-common .tribe-common-cta--thin-alt:hover,
									.tribe-common .tribe-common-cta--thin-alt,
									.tribe-events-pro .tribe-events-pro-map__event-card-wrapper--active .tribe-events-pro-map__event-card-button, .single-tribe_events .rg-event-heading .tribe-event-schedule-short .rg-schedule-short-date' . $selector_for_boder,
                                'property' => 'border-color',
                                'suffix' => ' !important',
                            ),
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                } else {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_colors_theme',
                        'label' => esc_attr__('Theme Color', 'reign'),
                        'description' => esc_attr__('The color of primary color, active color.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_colors_theme'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'function' => 'css',
                                'element' => '.woocommerce nav.woocommerce-pagination ul li .page-numbers:not(.current):hover, .woocommerce-page nav.woocommerce-pagination ul li .page-numbers:not(.current):hover, .widget_categories ul li:before, .widget_archive ul li:before, .widget.widget_nav_menu ul li:before, .widget.widget_meta ul li:before, .widget.widget_recent_comments ul li:before, .widget_rss ul li:before, .widget_pages ul li:before, .widget.widget_links ul li:before, .widget.widget_recent_entries ul li:before, ul.pmpro_billing_info_list li:before, .widget_edd_categories_tags_widget ul li:before, .widget_edd_cart_widget ul li:before, #buddypress div.item-list-tabs ul li.current a, #buddypress div.item-list-tabs ul li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.current a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a:focus,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.current a:hover,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a:focus,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li.selected a:hover,.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) .bp-navs li a:hover,.buddypress-wrap.bp-vertical-navs .dir-navs.activity-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.groups-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.members-nav-tabs ul li.selected a,.buddypress-wrap.bp-vertical-navs .dir-navs.sites-nav-tabs ul li.selected a,
								.buddypress-wrap.bp-vertical-navs .main-navs.group-nav-tabs ul li.selected a,
								.buddypress-wrap.bp-vertical-navs .main-navs.user-nav-tabs ul li.selected a,
								.bp-dir-vert-nav .dir-navs ul li.selected a,
								.bp-single-vert-nav .bp-navs.vertical li.selected a,
								.buddypress-wrap .bp-navs.tabbed-links ul li.current a,
								.bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li.current a,
								.bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li a:hover,
								.buddypress-wrap .bp-navs.tabbed-links ul li a:hover,
								.buddypress-wrap .bp-navs li:not(.selected) a:hover,
                                                                
                                                                .bp-nouveau.buddypress #buddypress:not(.bp-vertical-navs) .groups-nav li.selected a:hover, .bp-nouveau.buddypress #buddypress:not(.bp-vertical-navs) .groups-nav li a:hover, .bp-nouveau.buddypress #buddypress:not(.bp-vertical-navs) .groups-nav li.selected a:focus, .bp-nouveau.buddypress #buddypress:not(.bp-vertical-navs) .groups-nav li.selected a,

								.buddypress-wrap .bp-navs li.current a,
								.buddypress-wrap .bp-navs li.current a:focus,
								.buddypress-wrap .bp-navs li.current a:hover,
								.buddypress-wrap .bp-navs li.selected a,
								.buddypress-wrap .bp-navs li.selected a:focus,
								.buddypress-wrap .bp-navs li.selected a:hover,
								.widget-area .widget.buddypress div.item-options a.selected,
								footer div.footer-wrap a:hover,
								footer .widget-area .widget.buddypress div.item-options a:hover,
								footer .widget-area .widget.buddypress div.item-options a.selected,
								.wbtm-member-directory-type-1 .action-wrap:hover,
								.wbtm-member-directory-type-3 .action-wrap:hover,
								.bp-nouveau .wbtm-member-directory-type-1 .action-wrap:hover,
								.bp-nouveau .wbtm-member-directory-type-3 .action-wrap:hover,
								.woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link.is-active a,
								.fes-vendor-menu ul li.active a, .fes-vendor-menu .edd-tabs li.active a, .fes-vendor-menu .edd-tabs li.active .icon, .fes-vendor-menu .edd-tabs li:hover a, .fes-vendor-menu .edd-tabs li:hover .icon, .rg-woo-breadcrumbs a.current,
								#bbpress-forums .bbp-topic-pagination a:hover, #bbpress-forums .bbp-pagination-links a:hover,
								#bbpress-forums #bbp-single-user-details #bbp-user-navigation li.current a,
								.tribe-common.tribe-events .tribe-events-calendar-list__event-date-tag-daynum,
								.tribe-common.tribe-events .tribe-events-calendar-list__event-date-tag-weekday,
								.tribe-events .tribe-events-c-nav__next:focus,
								.tribe-events .tribe-events-c-nav__next:hover,
								.tribe-events .tribe-events-c-nav__prev:focus,
								.tribe-events .tribe-events-c-nav__prev:hover,
								.tribe-events .tribe-events-c-ical__link,
								.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date,
								.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link,
								.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link:focus, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link:hover, .tribe-events .tribe-events-calendar-month__day-date-link:focus, .tribe-events .tribe-events-calendar-month__day-date-link:hover,
								#tribe_events_filters_wrapper.tribe-events-filters-horizontal .tribe_events_filter_control button:hover,
								.tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum,
								.tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link,
								.tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link:focus,
								.tribe-events-pro .tribe-events-pro-week-grid__header-column--current .tribe-events-pro-week-grid__header-column-daynum-link:hover,
								.tribe-events .tribe-events-c-nav__today:focus,
								.tribe-events .tribe-events-c-nav__today:hover,
								.tribe-common .tribe-common-cta--thin-alt:active,
								.tribe-common .tribe-common-cta--thin-alt:focus,
								.tribe-common .tribe-common-cta--thin-alt:hover,
								.single-tribe_events .rg-event-heading .tribe-event-schedule-short .rg-schedule-short-m,
								.single-tribe_events .rg-event-heading .tribe-event-schedule-short .rg-schedule-short-d,
                                                                .buddypress-wrap .grid-filters a.active,
                                                                .ps-tabs__item--active>a, .ps-tabs__item>a:hover, .ps-tabs__item>a:focus, .ps-tip.ps-btn--active, .ps-focus__menu-item--active, .ps-tabs__item--active i, .ps-friends__tab--active>a, .psw-profile__menu-item i, .ps-tabs .current.slick-slide a, .ps-postbox__menu-item--type .ps-postbox__menu-item-link>.ps-icon.active:before',
                                'property' => 'color',
                            ),
                            array(
                                'function' => 'css',
                                'element' => '.rg-posts-navigation .nav-links .page-numbers.current, #bbpress-forums .bbp-pagination-links span.current, .rg-count, h3.lm-header-title:after, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_pmpro_price_top, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_levels_table_button .pmpro_btn, .woocommerce div.product .woocommerce-tabs ul.tabs li.active:before, #pmpro_account-membership .pmpro_actionlinks a, #pmpro_account-profile .pmpro_actionlinks a, #pmpro_cancel .pmpro_actionlinks a, #pmpro_form .pmpro_btn,

							.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links) li:after,
							.bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li:after,

							.bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links) li:hover:after,
							.bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links) li:hover:after,
							.bp-nouveau .groups-type-navs li:after, .bp-nouveau .groups-type-navs li:hover:after,

							.buddypress-wrap .bp-navs li.current a .count,
							.buddypress-wrap .bp-navs li.selected a .count,
							.buddypress_object_nav .bp-navs li.current a .count,
							.buddypress_object_nav .bp-navs li.selected a .count,
							.bp-nouveau .rtm-bp-navs.bp-navs ul.subnav li.selected span,

							.buddypress-wrap.bp-vertical-navs .dir-navs.activity-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.groups-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.members-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .dir-navs.sites-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .main-navs.group-nav-tabs ul li.selected a span,
							.buddypress-wrap.bp-vertical-navs .main-navs.user-nav-tabs ul li.selected a span,
							.widget_edd_cart_widget p.edd-cart-number-of-items span.edd-cart-quantity,
							.single-download .type-download input[type="radio"]:checked + label span:before, .single-download .type-download .edd_price_options label:before, label.selectit:before,
							input[type="radio"]:checked + label span:before, .edd_price_options label:before, label.selectit:before,
							.fes-vendor-menu ul li.active a:after, .fes-vendor-menu ul li a:hover:after, #component #edd_user_history th, .fes-table th, .edd-table th, .fes-vendor-dashboard table th, .woocommerce-account .woocommerce-MyAccount-navigation li.woocommerce-MyAccount-navigation-link a:before,
							
							.woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce-page nav.woocommerce-pagination ul li span.current,
							#bbpress-forums #bbp-single-user-details #bbp-user-avatar,
							.tribe-events .datepicker .day.active, .tribe-events .datepicker .day.active.focused, .tribe-events .datepicker .day.active:focus, .tribe-events .datepicker .day.active:hover, .tribe-events .datepicker .month.active, .tribe-events .datepicker .month.active.focused, .tribe-events .datepicker .month.active:focus, .tribe-events .datepicker .month.active:hover, .tribe-events .datepicker .year.active, .tribe-events .datepicker .year.active.focused, .tribe-events .datepicker .year.active:focus, .tribe-events .datepicker .year.active:hover,
							.tribe-events .tribe-events-c-ical__link:active, .tribe-events .tribe-events-c-ical__link:focus, .tribe-events .tribe-events-c-ical__link:hover,
							.tribe-events .tribe-events-calendar-month__mobile-events-icon--event,
                                                        .ps-focus__menu-item--active:after, .ps-notif__bubble, .psw-profile__progress-bar>span, #component #badgeos-earned-achievements-container .selected, #component #badgeos-achievements-container .selected, #component #badgeos-earned-ranks-container .selected, #component #badgeos-list-ranks-container .selected',
                                'property' => 'background',
                            ),
                            array(
                                'function' => 'css',
                                'element' => '.rg-link-block, .rg-posts-navigation .nav-links .page-numbers.current, #bbpress-forums .bbp-pagination-links span.current, #bbpress-forums .bbp-topic-pagination a:hover, #bbpress-forums .bbp-pagination-links a:hover, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce-page nav.woocommerce-pagination ul li span.current, .woocommerce nav.woocommerce-pagination ul li .page-numbers:hover, .woocommerce-page nav.woocommerce-pagination ul li .page-numbers:hover, .widget-title span, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_pmpro_price_top, .rtm_pmpro_levels_plan .rtm_pmpro_featured .rtm_levels_table_button .pmpro_btn, #pmpro_account-membership .pmpro_actionlinks a, #pmpro_account-profile .pmpro_actionlinks a, #pmpro_cancel .pmpro_actionlinks a, #pmpro_form .pmpro_btn, body #buddypress #item-body div.item-list-tabs#subnav li.selected a, body #buddypress #item-body div.item-list-tabs#subnav li.current a, #buddypress div.activity-comments form textarea:focus, body #buddypress div.activity-comments ul li form textarea:focus, .single-download .type-download .edd_price_options input[type="radio"] + label span:after, .single-download .type-download .edd_price_options label:after, .edd_price_options input[type="radio"] + label span:after, .edd_price_options label:after, .edd_pagination span.current, .rg-has-border, .woocommerce div.product div.images .flex-control-thumbs li img.flex-active, .woocommerce div.product div.images .flex-control-thumbs li img:hover,
									.tribe-common.tribe-events .tribe-events-calendar-list__event-date-tag, .tribe-common .tribe-common-anchor-thin:active, .tribe-common .tribe-common-anchor-thin:focus, .tribe-common .tribe-common-anchor-thin:hover, .tribe-events .tribe-events-c-ical__link, .tribe-events-pro .tribe-events-pro-week-day-selector__day--active,
									.tribe-common .tribe-common-cta--thin-alt:active,
									.tribe-common .tribe-common-cta--thin-alt:focus,
									.tribe-common .tribe-common-cta--thin-alt:hover,
									.tribe-common .tribe-common-cta--thin-alt,
									.tribe-events-pro .tribe-events-pro-map__event-card-wrapper--active .tribe-events-pro-map__event-card-button, .single-tribe_events .rg-event-heading .tribe-event-schedule-short .rg-schedule-short-date',
                                'property' => 'border-color',
                            ),
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                }

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_site_headings_color',
                    'label' => esc_attr__('Headings Color', 'reign'),
                    'description' => esc_attr__('The color of headings.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_site_headings_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => 'h1, h2, h3, h4, h5, h6',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_site_link_color',
                    'label' => esc_attr__('Link Color', 'reign'),
                    'description' => esc_attr__('The color of link.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_site_link_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => 'a, .entry-meta span.author.vcard a, .woocommerce div.product .woocommerce-tabs ul.tabs li a, .llms-loop-item-content .llms-loop-link, .llms-loop-item-content .llms-loop-link:visited, .dokan-single-store .dokan-store-tabs ul li a, #buddypress .activity-list .activity-item .activity-meta.action div.generic-button a.button, #buddypress .activity-list .activity-item .activity-meta.action a.button, .bp-nouveau #buddypress .activity-comments .activity-meta a, .bp-nouveau #buddypress .activity-meta .bp-share-btn .bp-share-button, #shiftnav-toggle-main .rg-mobile-header-icon-wrap a, #buddypress .activity-content .groups-meta a, #buddypress div.groups-meta a, .ps-member__name a, .ps-navbar__menu-link, .ps-navbar__menu-item--home>a, .ps-group__name a, .ps-postbox__menu-item--type .ps-postbox__menu-item-link, .ps-postbox__menu-item-link, .ps-post__title .ps-tag__link:first-of-type, .ps-post__options-menu .ps-dropdown__menu>a, .ps-comment__author .ps-tag__link, .ps-comment__actions-dropdown .ps-dropdown__menu>a, .ps-post__author, .ps-postbox__menu-item--type .ps-dropdown__menu>a, .ps-focus__name',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_site_link_hover_color',
                    'label' => esc_attr__('Link Color [Hover]', 'reign'),
                    'description' => esc_attr__('The color of link hover.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_site_link_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => 'a:hover, .entry-meta span.author.vcard a:hover, .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, .llms-loop-item-content .llms-loop-link:hover, .llms-loop-item-content .llms-loop-link:visited:hover, .dokan-single-store .dokan-store-tabs ul li a:hover, #buddypress .activity-list .activity-item .activity-meta.action div.generic-button a.button:hover, #buddypress .activity-list .activity-item .activity-meta.action a.button:hover, .bp-nouveau #buddypress .activity-comments .activity-meta a:hover, .bp-nouveau #buddypress .activity-meta .bp-share-btn .bp-share-button:hover, #shiftnav-toggle-main .rg-mobile-header-icon-wrap a:hover, #buddypress .activity-content .groups-meta a:hover, #buddypress div.groups-meta a:hover,
								.tribe-common a:active, .tribe-common a:focus, .tribe-common a:hover,
                                                                .ps-navbar__menu-link:hover, .ps-navbar__menu-link:focus, .ps-navbar__menu-item--home>a:hover, .ps-member__name a:hover, .ps-member__name a:focus, .ps-group__name a:hover, .ps-group__name a:focus, .ps-postbox__menu-item--type .ps-postbox__menu-item-link:hover, .ps-postbox__menu-item-link:hover, .ps-post__title .ps-tag__link:first-of-type:hover, .ps-post__title .ps-tag__link:first-of-type:focus, .ps-post__privacy>a:hover, .ps-post__privacy>a:focus, .ps-post__date:hover, .ps-post__date:focus, .ps-post__copy:hover, .ps-post__copy:focus, .ps-post__options-menu>a:hover, .ps-post__options-menu>a:focus, .ps-post__options-menu .ps-dropdown__menu>a:hover, .ps-post__options-menu .ps-dropdown__menu>a:focus, .ps-post__options-menu .ps-dropdown__menu>a.active, .ps-comment__author .ps-tag__link:hover, .ps-comment__author .ps-tag__link:focus, .ps-comment__actions-dropdown .ps-dropdown__menu>a:hover, .ps-comment__actions-dropdown .ps-dropdown__menu>a:focus, .ps-comment__actions-dropdown .ps-dropdown__menu>a.active, .ps-comment__info a:hover, .ps-comment__info a:focus, .ps-comment__action--reply:hover, .ps-comment__action--reply:focus, .ps-post__author:hover, .ps-post__author:focus, .ps-comment__actions-dropdown>a:hover, .ps-comment__actions-dropdown>a:focus, .ps-post__action:hover, .ps-post__action:focus, .ps-posts__filter-toggle:hover, .ps-postbox__menu-item--type .ps-dropdown__menu>a:hover, .ps-postbox__menu-item--type .ps-dropdown__menu>a:focus, .ps-postbox__menu-item--type .ps-dropdown__menu>a.active, .ps-focus__menu-item:hover, .ps-focus__menu-item:focus, .ps-focus__details>a:hover, .ps-focus__details>a:focus, .ps-focus__details>a:hover i, .ps-focus__details>a:hover strong, .ps-focus__details>a:focus i, .ps-focus__details>a:focus strong, .ps-focus__like:hover span',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_accent_color',
                    'label' => esc_attr__('Content Link Color', 'reign'),
                    'description' => esc_attr__('The color of content area link.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_accent_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => '.description a, .post .entry-content a:not(.read-more), .bbp-forum-content a, span#subscription-toggle .is-subscribed a, span#favorite-toggle .is-favorite a, #buddypress .activity-content .activity-inner a, .woocommerce-product-details__short-description a, .woocommerce-Tabs-panel--description a, .bbp-reply-content a, .woocommerce-MyAccount-content a, .lm-tab-course-content a, .lm-author-description a, .rlla-tab-course-content a, .rlla-author-description a, .ps-stream-body a, .job_description a, .single-resume-content a, .geodir-field-post_content a, #ps-activitystream .ps-post__content a',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_accent_hover_color',
                    'label' => esc_attr__('Content Link Color [Hover]', 'reign'),
                    'description' => esc_attr__('The color of content area link hover.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_accent_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => '.description a:hover .post .entry-content a:not(.read-more):hover, .bbp-forum-content a:hover, span#subscription-toggle a:hover, span#favorite-toggle a:hover, #buddypress .activity-content .activity-inner a:hover, .woocommerce-product-details__short-description a:hover, .woocommerce-Tabs-panel--description a:hover, .bbp-reply-content a:hover, .woocommerce-MyAccount-content a:hover, .lm-tab-course-content a:hover, .lm-author-description a:hover, .rlla-tab-course-content a:hover, .rlla-author-description a:hover, .ps-stream-body a:hover, .job_description a:hover, .single-resume-content a:hover, .geodir-field-post_content a:hover, #ps-activitystream .ps-post__content a:hover',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                if (is_plugin_active('peepso-multivendor/peepso-multivendor.php')) {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_site_button_text_color',
                        'label' => esc_attr__('Button Text Color', 'reign'),
                        'description' => esc_attr__('The color of button text.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_site_button_text_color'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'function' => 'css',
                                'element' => 'button, .rg-login-btn-wrap .button, .rg-register-btn-wrap .button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, .edd-submit.button, .edd-submit.button.gray, .edd-submit.button:visited, div.fes-form.fes-form .fes-submit input[type=submit], .peepso .wbpm-peepo-multivendor-wrapper .woocommerce .products .button,
									.tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn, body #tribe-events .tribe-events-button.tribe-events-button, body .tribe-events-button.tribe-events-button,
                                                                        .ps-btn--action, .ps-btn:not(.ps-tip):not(a):not(.ps-js-btn-edit):not(.ps-js-btn-edit-all)',
                                'property' => 'color',
                                'suffix' => ' !important',
                            )
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                } else {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_site_button_text_color',
                        'label' => esc_attr__('Button Text Color', 'reign'),
                        'description' => esc_attr__('The color of button text.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_site_button_text_color'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'function' => 'css',
                                'element' => 'button, .rg-login-btn-wrap .button, .rg-register-btn-wrap .button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, .edd-submit.button, .edd-submit.button.gray, .edd-submit.button:visited, div.fes-form.fes-form .fes-submit input[type=submit],
									.tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn, body #tribe-events .tribe-events-button.tribe-events-button, body .tribe-events-button.tribe-events-button,
                                                                        .ps-btn--action, .ps-btn:not(.ps-tip):not(a):not(.ps-js-btn-edit):not(.ps-js-btn-edit-all)',
                                'property' => 'color',
                            )
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                }

                if (is_plugin_active('peepso-multivendor/peepso-multivendor.php')) {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_site_button_text_hover_color',
                        'label' => esc_attr__('Button Text Color [Hover]', 'reign'),
                        'description' => esc_attr__('The color of button text hover.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_site_button_text_hover_color'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'function' => 'css',
                                'element' => 'button:hover, .rg-login-btn-wrap .button:hover, .rg-register-btn-wrap .button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, a.rg-action.button:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress form#whats-new-form #whats-new-submit input:hover, #buddypress #profile-edit-form ul.button-nav li.current a:hover, #buddypress div.generic-button a:hover, #buddypress .item-list.rg-group-list div.action a:hover, #buddypress div#item-header #item-header-content1 div.generic-button a:hover, body #buddypress .activity-list li.load-more a:hover, body #buddypress .activity-list li.load-newest a:hover, .media .rtm-load-more a#rtMedia-galary-next:hover, .rg-group-section .item-list.rg-group-list div.action a:hover, .field-wrap button:hover, .field-wrap input[type=button]:hover, .field-wrap input[type=submit]:hover, a.read-more.button:hover, .form-submit #submit:hover, form.woocommerce-product-search input[type="submit"]:hover, #buddypress form.woocommerce-product-search input[type="submit"]:hover, #buddypress .widget_search input[type="submit"]:hover, button#bbp_topic_submit:hover, .nav-links > div:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .rg-woocommerce_mini_cart .button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce .cart .button:hover, .woocommerce .cart input.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, #buddypress input[type=submit]:focus, .edd-submit.button:hover, .edd-submit.button.gray:hover, .edd-submit.button:visited:hover, div.fes-form.fes-form .fes-submit input[type=submit]:hover, .peepso .wbpm-peepo-multivendor-wrapper .woocommerce .products .button:hover,
									.tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:hover, body #tribe-events .tribe-events-button.tribe-events-button:hover, body .tribe-events-button.tribe-events-button:hover,
                                                                        .ps-btn:not(.ps-btn--app):hover, .ps-btn:not(.ps-btn--app):focus, .ps-btn .active, .ps-btn:not(.ps-tip):not(a):hover',
                                'property' => 'color',
                                'suffix' => ' !important',
                            )
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                } else {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_site_button_text_hover_color',
                        'label' => esc_attr__('Button Text Color [Hover]', 'reign'),
                        'description' => esc_attr__('The color of button text hover.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_site_button_text_hover_color'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'function' => 'css',
                                'element' => 'button:hover, .rg-login-btn-wrap .button:hover, .rg-register-btn-wrap .button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, a.rg-action.button:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress form#whats-new-form #whats-new-submit input:hover, #buddypress #profile-edit-form ul.button-nav li.current a:hover, #buddypress div.generic-button a:hover, #buddypress .item-list.rg-group-list div.action a:hover, #buddypress div#item-header #item-header-content1 div.generic-button a:hover, body #buddypress .activity-list li.load-more a:hover, body #buddypress .activity-list li.load-newest a:hover, .media .rtm-load-more a#rtMedia-galary-next:hover, .rg-group-section .item-list.rg-group-list div.action a:hover, .field-wrap button:hover, .field-wrap input[type=button]:hover, .field-wrap input[type=submit]:hover, a.read-more.button:hover, .form-submit #submit:hover, form.woocommerce-product-search input[type="submit"]:hover, #buddypress form.woocommerce-product-search input[type="submit"]:hover, #buddypress .widget_search input[type="submit"]:hover, button#bbp_topic_submit:hover, .nav-links > div:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .rg-woocommerce_mini_cart .button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce .cart .button:hover, .woocommerce .cart input.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, #buddypress input[type=submit]:focus, .edd-submit.button:hover, .edd-submit.button.gray:hover, .edd-submit.button:visited:hover, div.fes-form.fes-form .fes-submit input[type=submit]:hover,
									.tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:hover, body #tribe-events .tribe-events-button.tribe-events-button:hover, body .tribe-events-button.tribe-events-button:hover,
                                                                        .ps-btn:not(.ps-btn--app):hover, .ps-btn:not(.ps-btn--app):focus, .ps-btn .active, .ps-btn:not(.ps-tip):not(a):hover',
                                'property' => 'color',
                            )
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                }

                if (is_plugin_active('peepso-multivendor/peepso-multivendor.php')) {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_site_button_bg_color',
                        'label' => esc_attr__('Button Background Color', 'reign'),
                        'description' => esc_attr__('The background color of button.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_site_button_bg_color'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'element' => 'button, .rg-login-btn-wrap .button, .rg-register-btn-wrap .button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, .edd-submit.button, .edd-submit.button.gray, .edd-submit.button:visited, div.fes-form.fes-form .fes-submit input[type=submit], .peepso .wbpm-peepo-multivendor-wrapper .woocommerce .products .button,
									.tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn, body #tribe-events .tribe-events-button.tribe-events-button, body .tribe-events-button.tribe-events-button,
                                                                        .ps-btn--action, .ps-btn:not(.ps-tip):not(a):not(.ps-js-btn-edit):not(.ps-js-btn-edit-all)',
                                'property' => 'background-color',
                                'suffix' => ' !important',
                            )
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                } else {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_site_button_bg_color',
                        'label' => esc_attr__('Button Background Color', 'reign'),
                        'description' => esc_attr__('The background color of button.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_site_button_bg_color'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'element' => 'button, .rg-login-btn-wrap .button, .rg-register-btn-wrap .button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, .edd-submit.button, .edd-submit.button.gray, .edd-submit.button:visited, div.fes-form.fes-form .fes-submit input[type=submit],
									.tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn, body #tribe-events .tribe-events-button.tribe-events-button, body .tribe-events-button.tribe-events-button,
                                                                        .ps-btn--action, .ps-btn:not(.ps-tip):not(a):not(.ps-js-btn-edit):not(.ps-js-btn-edit-all)',
                                'property' => 'background-color',
                            )
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                }

                if (is_plugin_active('peepso-multivendor/peepso-multivendor.php')) {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_site_button_bg_hover_color',
                        'label' => esc_attr__('Button Background Color [Hover]', 'reign'),
                        'description' => esc_attr__('The background color of button hover.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_site_button_bg_hover_color'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'element' => 'button:hover, .rg-login-btn-wrap .button:hover, .rg-register-btn-wrap .button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, a.rg-action.button:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress form#whats-new-form #whats-new-submit input:hover, #buddypress #profile-edit-form ul.button-nav li.current a:hover, #buddypress div.generic-button a:hover, #buddypress .item-list.rg-group-list div.action a:hover, #buddypress div#item-header #item-header-content1 div.generic-button a:hover, body #buddypress .activity-list li.load-more a:hover, body #buddypress .activity-list li.load-newest a:hover, .media .rtm-load-more a#rtMedia-galary-next:hover, .rg-group-section .item-list.rg-group-list div.action a:hover, .field-wrap button:hover, .field-wrap input[type=button]:hover, .field-wrap input[type=submit]:hover, a.read-more.button:hover, .form-submit #submit:hover, form.woocommerce-product-search input[type="submit"]:hover, #buddypress form.woocommerce-product-search input[type="submit"]:hover, #buddypress .widget_search input[type="submit"]:hover, button#bbp_topic_submit:hover, .nav-links > div:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .rg-woocommerce_mini_cart .button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce .cart .button:hover, .woocommerce .cart input.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, #buddypress input[type=submit]:focus, .edd-submit.button:hover, .edd-submit.button.gray:hover, .edd-submit.button:visited:hover, div.fes-form.fes-form .fes-submit input[type=submit]:hover, .peepso .wbpm-peepo-multivendor-wrapper .woocommerce .products .button:hover,
									.tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:hover, body #tribe-events .tribe-events-button.tribe-events-button:hover, body .tribe-events-button.tribe-events-button:hover,
                                                                        .ps-btn:not(.ps-btn--app):hover, .ps-btn:not(.ps-btn--app):focus, .ps-btn .active, .ps-btn:not(.ps-tip):not(a):hover',
                                'property' => 'background-color',
                                'suffix' => ' !important',
                            )
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                } else {
                    $fields[] = array(
                        'type' => 'color',
                        'settings' => $color_scheme_key . '-' . 'reign_site_button_bg_hover_color',
                        'label' => esc_attr__('Button Background Color [Hover]', 'reign'),
                        'description' => esc_attr__('The background color of button hover.', 'reign'),
                        'section' => 'colors',
                        'default' => $default_value_set[$color_scheme_key]['reign_site_button_bg_hover_color'],
                        'priority' => 10,
                        'choices' => array('alpha' => true),
                        'output' => array(
                            array(
                                'element' => 'button:hover, .rg-login-btn-wrap .button:hover, .rg-register-btn-wrap .button:hover, input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover, a.rg-action.button:hover, #buddypress .comment-reply-link:hover, #buddypress .generic-button a:hover, #buddypress .standard-form button:hover, #buddypress a.button:hover, #buddypress input[type=button]:hover, #buddypress input[type=reset]:hover, #buddypress input[type=submit]:hover, #buddypress ul.button-nav li a:hover, a.bp-title-button:hover, #buddypress form#whats-new-form #whats-new-submit input:hover, #buddypress #profile-edit-form ul.button-nav li.current a:hover, #buddypress div.generic-button a:hover, #buddypress .item-list.rg-group-list div.action a:hover, #buddypress div#item-header #item-header-content1 div.generic-button a:hover, body #buddypress .activity-list li.load-more a:hover, body #buddypress .activity-list li.load-newest a:hover, .media .rtm-load-more a#rtMedia-galary-next:hover, .rg-group-section .item-list.rg-group-list div.action a:hover, .field-wrap button:hover, .field-wrap input[type=button]:hover, .field-wrap input[type=submit]:hover, a.read-more.button:hover, .form-submit #submit:hover, form.woocommerce-product-search input[type="submit"]:hover, #buddypress form.woocommerce-product-search input[type="submit"]:hover, #buddypress .widget_search input[type="submit"]:hover, button#bbp_topic_submit:hover, .nav-links > div:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .rg-woocommerce_mini_cart .button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.disabled:hover, .woocommerce #respond input#submit:disabled:hover, .woocommerce #respond input#submit:disabled[disabled]:hover, .woocommerce a.button.disabled:hover, .woocommerce a.button:disabled:hover, .woocommerce a.button:disabled[disabled]:hover, .woocommerce button.button.disabled:hover, .woocommerce button.button:disabled:hover, .woocommerce button.button:disabled[disabled]:hover, .woocommerce input.button.disabled:hover, .woocommerce input.button:disabled:hover, .woocommerce input.button:disabled[disabled]:hover, .woocommerce .cart .button:hover, .woocommerce .cart input.button:hover, .woocommerce div.product form.cart .button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover, #buddypress input[type=submit]:focus, .edd-submit.button:hover, .edd-submit.button.gray:hover, .edd-submit.button:visited:hover, div.fes-form.fes-form .fes-submit input[type=submit]:hover,
									.tribe-common .tribe-common-c-btn:hover, .tribe-common a.tribe-common-c-btn:hover, body #tribe-events .tribe-events-button.tribe-events-button:hover, body .tribe-events-button.tribe-events-button:hover,
                                                                        .ps-btn:not(.ps-btn--app):hover, .ps-btn:not(.ps-btn--app):focus, .ps-btn .active, .ps-btn:not(.ps-tip):not(a):hover',
                                'property' => 'background-color',
                            )
                        ),
                        'active_callback' => array(
                            array(
                                'setting' => 'reign_color_scheme',
                                'operator' => '===',
                                'value' => $color_scheme_key,
                            ),
                        )
                    );
                }

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_site_border_color',
                    'label' => esc_attr__('Border Color', 'reign'),
                    'description' => esc_attr__('The border color of site.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_site_border_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => '#scroll-top, .blog .hentry, .search .hentry, .archive .hentry, .post-navigation, .posts-navigation, th, td, .widget-title, form#search-groups-form input#groups_search, form#search-members-form input#members_search, #buddypress .activity-content .activity-meta, .content-wrapper .entry-header.page-header, .content-wrapper header.woocommerce-products-header, .bp-content-area header.entry-header, #buddypress #whats-new-options, #buddypress div.activity-comments form.ac-form, #buddypress div.activity-comments form textarea, body #buddypress div.activity-comments ul li form textarea, body #buddypress #item-body div.item-list-tabs#subnav ul, .bp-nouveau .buddypress-wrap.bp-dir-hori-nav:not(.bp-vertical-navs) nav:not(.tabbed-links):before, .bp-nouveau .bp-single-vert-nav .item-body:not(#group-create-body) #subnav:not(.tabbed-links):before, #buddypress div.activity-comments ul, body.activity-permalink #buddypress div.activity-comments ul, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-2 .action-wrap, .bp-inner-wrap, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-1 .action.rg-dropdown:after, #buddypress .item-list.rg-member-list.wbtm-member-directory-type-3 .action.rg-dropdown:after, .bp-group-inner-wrap, #buddypress .item-list.rg-group-list.wbtm-group-directory-type-2 div.action, #buddypress div.message-search input#messages_search, #buddypress table#message-threads tr.unread td, .bp-nouveau .activity-update-form, .bp-nouveau #buddypress form#whats-new-form textarea, .bp-nouveau .activity-list .activity-item .activity-content .activity-inner, .activity-list .activity-item > .activity-meta.action, .bp-nouveau #buddypress .activity-content .activity-meta a, .bp-nouveau #buddypress div.activity-meta a, .buddypress-wrap .activity-comments .acomment-content, .buddypress.widget ul.item-list li, .bp-nouveau .buddypress-wrap form.bp-dir-search-form, .bp-nouveau .buddypress-wrap form.bp-invites-search-form, .bp-nouveau .buddypress-wrap form.bp-messages-search-form, .bp-nouveau .buddypress-wrap form#media_search_form, .bp-nouveau .buddypress-wrap .select-wrap, .bp-nouveau .buddypress-wrap .bptodo-form-add select, .buddypress-wrap .members-list li .user-update, .bp-nouveau .bp-list:not(.grid) > li, .bp-nouveau .grid.bp-list > li .list-wrap, .buddypress-wrap .groups-list li .group-desc, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-1 > li .action:after, .bp-nouveau #buddypress.buddypress-wrap .grid.bp-list.wbtm-member-directory-type-3 > li .action:after, .bp-nouveau #buddypress .item-list.rg-member-list.grid.bp-list.wbtm-member-directory-type-2 > li .action, .buddypress-wrap .profile.edit .editfield, .bp-messages-content #thread-preview, .bp-messages-content .preview-pane-header, .bp-messages-content .single-message-thread-header, .bp-nouveau #message-threads li, .bp-nouveau #message-threads, .buddypress .bp-invites-content ul.item-list>li, .groups-header .desc-wrap, .buddypress-wrap .bp-tables-user tr td.label, .buddypress-wrap table.forum tr td.label, .buddypress-wrap table.wp-profile-fields tr td.label, .wbtm-show-item-buttons, .bp-nouveau .wbtm-show-item-buttons, .bp-dir-vert-nav .screen-content, .bp-single-vert-nav .item-body:not(#group-create-body), .buddypress-wrap .tabbed-links ol, .buddypress-wrap .tabbed-links ul, .buddypress-wrap .single-screen-navs li, #buddypress form fieldset, #buddypress table.forum tr td.label, #buddypress table.messages-notices tr td.label, #buddypress table.notifications tr td.label, #buddypress table.notifications-settings tr td.label, #buddypress table.profile-fields tr td.label, #buddypress table.wp-profile-fields tr td.label, .bp-nouveau .bpolls-html-container, .bpolls-polls-option-html, a.bpolls-cancel, .ui-tabs .ui-tabs-panel, #bptodo-dashboard ul li, #bptodo-tabs .ui-widget-header, #bptodo-tabs.ui-tabs .ui-tabs-nav li.ui-tabs-active, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .bprm_resume_form fieldset, .bprm_resume_form select, .bprm-container, #buddypress div#item-header .wbtm-cover-header-type-3 div#item-header-cover-image, .bp-nouveau #buddypress div#item-header .wbtm-cover-header-type-3 div#item-header-cover-image, .bp-messages-content #bp-message-thread-list .message-metadata, .bp-messages-content #bp-message-thread-list, .widget.woocommerce.widget_product_categories ul.product-categories li.cat-item, .woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce div.product .woocommerce-tabs .panel, .woocommerce div.product .woocommerce-tabs ul.tabs::before, .woocommerce table.shop_table, .woocommerce .woocommerce-checkout #payment.woocommerce-checkout-payment, #add_payment_method #payment ul.payment_methods, .woocommerce-cart #payment ul.payment_methods, .woocommerce-checkout #payment ul.payment_methods, #bbpress-forums fieldset.bbp-form, .edd_pagination a, .edd_pagination span, .edd-rvi-wrapper-single, .edd-rvi-wrapper-checkout, #edd-rp-single-wrapper, #edd-rp-checkout-wrapper, .edd-sd-share, #isa-related-downloads, .edd_review, .edd-reviews-form-inner, #edd_checkout_form_wrap fieldset, #edd_checkout_cart td, #edd_checkout_cart th, .entry-content ul.edd-cart, .entry-content ul#edd_discounts_list, .entry-content ul.edd-cart > li, .entry-content ul#edd_discounts_list > li, .edd-wish-list li, .edd-wl-create, .edd-wl-wish-lists, #edd_user_history td, #edd_user_history, #edd_user_history th, .fes-table, .edd-table, .fes-vendor-dashboard table, .fes-table, .edd-table, .fes-vendor-dashboard table, .fes-table th, .edd-table th, .fes-vendor-dashboard table th, .edd_form fieldset, .fes-form fieldset, .user-notifications ul#rg-notify li + li, .woocommerce .quantity .product_quantity_minus, .woocommerce .quantity .product_quantity_plus, .woocommerce .quantity .qty, .product_meta span+span, #add_payment_method table.cart td.actions .coupon .input-text, .woocommerce-cart table.cart td.actions .coupon .input-text, .woocommerce-checkout table.cart td.actions .coupon .input-text, .woocommerce .woocommerce-checkout #payment.woocommerce-checkout-payment ul li, .woocommerce .woocommerce-MyAccount-navigation, .woocommerce-account .woocommerce-MyAccount-navigation ul > li + li, .woocommerce-account .woocommerce .woocommerce-MyAccount-content, .fes-vendor-menu ul li.fes-vendor-menu-tab + li.fes-vendor-menu-tab, .buddypress-wrap .activity-comments ul li, .woocommerce nav.woocommerce-pagination ul, .woocommerce nav.woocommerce-pagination ul li, footer div#reign-copyright-text, .widget-area-inner .widget, .widget-area .widget, .bp-widget-area .widget, .bp-plugin-widgets, .ps-toolbar, #bbpress-forums ul.bbp-forums, #bbpress-forums li.bbp-body ul.forum, #bbpress-forums li.bbp-header ul.forum-titles, #bbpress-forums ul.bbp-lead-topic, #bbpress-forums ul.bbp-topics, #bbpress-forums ul.bbp-forums, #bbpress-forums ul.bbp-replies, #bbpress-forums ul.bbp-search-results, .bbp-topics .topic, .bbp-topic-form, #bbpress-forums .bbp-login-form fieldset.bbp-form, .bbp-reply-form, #bbpress-forums .rg-replies .bbp-body > div, .bbp-reply-footer .bbp-meta, #bbpress-forums .forums.bbp-search-results .bbp-body > div, #bbpress-forums .forums.bbp-search-results .bbp-reply-header, #bbpress-forums .forums.bbp-search-results .bbp-forum-header .bbp-meta, #bbpress-forums .forums.bbp-search-results .bbp-topic-top, #bbp-user-navigation ul, .activity-update-form #whats-new-avatar, #activity-form-submit-wrapper, .bp-nouveau .activity-list.bp-list .activity-item, .bb-activity-media-wrap .bb-activity-media-elem.document-activity, .bb-activity-media-wrap .bb-activity-media-elem.document-activity .document-preview-wrap, .buddypress-wrap .grid-filters, a.layout-grid-view, .bp-nouveau .bp-vertical-navs .rg-nouveau-sidebar-menu, .bp-nouveau .bp-vertical-navs .item-body-inner-wrapper, #group-invites-container, .bb-panel-head, .bb-groups-invites-left, #bp-message-thread-list, #bp-message-thread-list li, #item-body #group-invites-container .bp-invites-content .item-list>li, #item-body #group-invites-container .bp-invites-content .item-list>li:last-child, .groups-header .desc-wrap, #bbpress-forums .rg-replies > li > div, #bbpress-forums ul.bbp-threaded-replies, .ps-post, .ps-postbox, .ps-navbar, .ps-posts__filters-group, .ps-tabs.ps-members__tabs, .ps-member, .ps-members__header, .ps-group, .ps-focus, .ps-profile__edit, .ps-group__edit-fields, .ps-friends__tabs-inner, .ps-postbox__menu-item--type .ps-postbox__menu-item-link, .ps-postbox__footer, .ps-posts__filter-toggle, .ps-tabs__item, .ps-member__actions, .ps-member__actions .ps-member__action, .ps-members__search, .ps-group__actions, .ps-group__action, .ps-focus__menu, .ps-focus__menu-item, .ps-profile__about-header, .ps-profile__about-field, .ps-profile__preferences .ps-form__legend, .ps-profile__notification-header, .ps-profile__notifications-row, .ps-group__edit-field, .ps-friends__tab, .ps-member__buttons .ps-member__action, .ps-dropdown--menu .ps-dropdown__menu>a, .ps-comments__list, .ps-comments--nested .ps-comment, .ps-comments__input, .ps-comments__input:hover, .ps-comments__input:focus, .ps-reactions__dropdown, .ps-reactions__list-item:first-child, .ps-reactions__list-item--delete, .ps-reactions__likes, .ps-comments__reply, .ps-media--video .ps-media__body, .ps-navbar__menu-item--home, .ps-profile__progress, .ps-posts__filter-search .ps-input, .ps-posts__filter-search .ps-input:hover, .ps-posts__filter-search .ps-input:focus, .ps-posts__filter-select, .ps-profile__edit-tabs .ps-tabs__item>a, .ps-profile__account-row, .ps-input:disabled, .ps-input:read-only, .ps-input.ps-input--disabled, .ps-friends__list-title, .ps-friends__tab, .ps-modal__footer, .ps-checkbox__label:before, .ps-members__filters, #component .badgeos_achievement_main_container, #component .badgeos_ranks_main_container' . $selector_for_border_color,
                            'property' => 'border-color',
                        ),
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_site_hr_color',
                    'label' => esc_attr__('HR Color', 'reign'),
                    'description' => esc_attr__('The hr color of site.', 'reign'),
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_site_hr_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'function' => 'css',
                            'element' => 'hr',
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                // Footer Color Scheme
                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_widget_area_bg_color',
                    'label' => esc_attr__('Footer Background Color', 'reign'),
                    'description' => 'The background color of footer.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_widget_area_bg_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div.footer-wrap',
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_widget_title_color',
                    'label' => esc_attr__('Footer Widget Title Color', 'reign'),
                    'description' => 'The color of footer widget title.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_widget_title_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div.footer-wrap .widget-title',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_widget_text_color',
                    'label' => esc_attr__('Footer Text Color', 'reign'),
                    'description' => 'The color of footer text.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_widget_text_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div.footer-wrap .widget',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_widget_link_color',
                    'label' => esc_attr__('Footer Link Color', 'reign'),
                    'description' => 'The color of footer link.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_widget_link_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div.footer-wrap a, footer .widget-area .widget.buddypress div.item-options a',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_widget_link_hover_color',
                    'label' => esc_attr__('Footer Link Color [Hover]', 'reign'),
                    'description' => 'The background color of footer link hover.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_widget_link_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div.footer-wrap a:hover, footer .widget-area .widget.buddypress div.item-options a:hover, footer .widget-area .widget.buddypress div.item-options a.selected',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                // Footer Color Scheme: Copyright
                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_copyright_bg_color',
                    'label' => esc_attr__('Copyright Background Color', 'reign'),
                    'description' => 'The background color of copyright.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_copyright_bg_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div#reign-copyright-text',
                            'property' => 'background-color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_copyright_text_color',
                    'label' => esc_attr__('Copyright Text Color', 'reign'),
                    'description' => 'The color of copyright text.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_copyright_text_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div#reign-copyright-text',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_copyright_link_color',
                    'label' => esc_attr__('Copyright Link Color', 'reign'),
                    'description' => 'The color of copyright link.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_copyright_link_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div#reign-copyright-text a',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );

                $fields[] = array(
                    'type' => 'color',
                    'settings' => $color_scheme_key . '-' . 'reign_footer_copyright_link_hover_color',
                    'label' => esc_attr__('Copyright Link Color [Hover]', 'reign'),
                    'description' => 'The color of copyright link hover.',
                    'section' => 'colors',
                    'default' => $default_value_set[$color_scheme_key]['reign_footer_copyright_link_hover_color'],
                    'priority' => 10,
                    'choices' => array('alpha' => true),
                    'output' => array(
                        array(
                            'element' => 'footer div#reign-copyright-text a:hover',
                            'property' => 'color',
                        )
                    ),
                    'active_callback' => array(
                        array(
                            'setting' => 'reign_color_scheme',
                            'operator' => '===',
                            'value' => $color_scheme_key,
                        ),
                    )
                );
            }
            return $fields;
        }

        public function add_fields($fields) {

            $default_value_set = reign_get_customizer_default_value_set();

            $fields[] = array(
                'type' => 'color',
                'settings' => 'reign_colors_theme',
                'label' => esc_attr__('Theme Color', 'reign'),
                'description' => esc_attr__('The color of primary color, active color.', 'reign'),
                'section' => 'colors',
                'default' => $default_value_set['reign_colors_theme'],
                'priority' => 10,
                'choices' => array('alpha' => true),
            );

            $fields[] = array(
                'type' => 'color',
                'settings' => 'reign_site_link_hover_color',
                'label' => esc_attr__('Link Hover Color', 'reign'),
                'description' => '',
                'section' => 'colors',
                'default' => '#3b5998',
                'priority' => 10,
                'choices' => array('alpha' => true),
                'output' => array(
                    array(
                        'function' => 'css',
                        'element' => 'a:hover',
                        'property' => 'color',
                    )
                ),
            );

            $fields[] = array(
                'type' => 'color',
                'settings' => 'reign_colors_button_bg',
                'label' => esc_attr__('Button Background Color', 'reign'),
                'description' => esc_attr__('The background color of button.', 'reign'),
                'section' => 'colors',
                'default' => '#3b5998',
                'priority' => 10,
                'choices' => array('alpha' => true),
                'output' => array(
                    array(
                        'element' => 'button, input[type=button], input[type=reset], input[type=submit], a.rg-action.button, #buddypress .comment-reply-link, #buddypress .generic-button a, #buddypress .standard-form button, #buddypress a.button, #buddypress input[type=button], #buddypress input[type=reset], #buddypress input[type=submit], #buddypress ul.button-nav li a, a.bp-title-button, #buddypress form#whats-new-form #whats-new-submit input, #buddypress #profile-edit-form ul.button-nav li.current a, #buddypress div.generic-button a, #buddypress .item-list.rg-group-list div.action a, #buddypress div#item-header #item-header-content1 div.generic-button a, body #buddypress .activity-list li.load-more a, body #buddypress .activity-list li.load-newest a, .media .rtm-load-more a#rtMedia-galary-next, .rg-group-section .item-list.rg-group-list div.action a, .field-wrap button, .field-wrap input[type=button], .field-wrap input[type=submit], a.read-more.button, .form-submit #submit, form.woocommerce-product-search input[type="submit"], #buddypress form.woocommerce-product-search input[type="submit"], #buddypress .widget_search input[type="submit"], button#bbp_topic_submit, .nav-links > div, .woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .rg-woocommerce_mini_cart .button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit:disabled[disabled], .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button:disabled[disabled], .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled], .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button:disabled[disabled], .woocommerce .cart .button, .woocommerce .cart input.button, .woocommerce div.product form.cart .button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .buddypress .buddypress-wrap button, .buddypress .buddypress-wrap button:hover, .buddypress .buddypress-wrap .bp-list.grid .action button:hover',
                        'property' => 'background-color',
                    )
                ),
            );

            return $fields;
        }

        public function reign_map_color_scheme_values() {

            $color_scheme_key = 'reign_default';

            /* Background Color */
            $background_color = get_theme_mod('background_color');
            $new_background_color = get_theme_mod($color_scheme_key . '-' . 'reign_site_body_bg_color', false);
            if (!$new_background_color && $background_color) {
                set_theme_mod($color_scheme_key . '-' . 'reign_site_body_bg_color', $background_color);
            }

            /* Theme Color */
            $theme_color = get_theme_mod('reign_colors_theme');
            $new_theme_color = get_theme_mod($color_scheme_key . '-' . 'reign_colors_theme', false);
            if (!$new_theme_color && $theme_color) {
                set_theme_mod($color_scheme_key . '-' . 'reign_colors_theme', $theme_color);
            }

            /* Link Hover Color */
            $link_hover_color = get_theme_mod('reign_site_link_hover_color');
            $new_link_hover_color = get_theme_mod($color_scheme_key . '-' . 'reign_site_link_hover_color', false);
            if (!$new_link_hover_color && $link_hover_color) {
                set_theme_mod($color_scheme_key . '-' . 'reign_site_link_hover_color', $link_hover_color);
            }

            /* Button Background Color */
            $button_bg_color = get_theme_mod('reign_site_button_bg_color');
            $new_button_bg_color = get_theme_mod($color_scheme_key . '-' . 'reign_site_button_bg_color', false);
            if (!$new_button_bg_color && $button_bg_color) {
                set_theme_mod($color_scheme_key . '-' . 'reign_site_button_bg_color', $button_bg_color);
            }

            /* Accent Color */
            $accent_color = get_theme_mod('reign_accent_color');
            $new_accent_color = get_theme_mod($color_scheme_key . '-' . 'reign_accent_color', false);
            if (!$new_accent_color && $accent_color) {
                set_theme_mod($color_scheme_key . '-' . 'reign_accent_color', $accent_color);
            }

            /* Accent Hover Color */
            $accent_hover_color = get_theme_mod('reign_accent_hover_color');
            $new_accent_hover_color = get_theme_mod($color_scheme_key . '-' . 'reign_accent_hover_color', false);
            if (!$new_accent_hover_color && $accent_hover_color) {
                set_theme_mod($color_scheme_key . '-' . 'reign_accent_hover_color', $accent_hover_color);
            }
        }

    }

    endif;

/**
 * Main instance of Reign_Kirki_Colors.
 * @return Reign_Kirki_Colors
 */
Reign_Kirki_Colors::instance();
