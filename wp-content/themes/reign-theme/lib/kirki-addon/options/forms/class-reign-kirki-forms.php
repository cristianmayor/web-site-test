<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Forms' ) ) :

	/**
	 * @class Reign_Kirki_Forms
	 */
	class Reign_Kirki_Forms {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Forms
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Forms Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Forms is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Forms - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Forms Constructor.
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

			$wp_customize->add_panel(
			'reign_forms_panel', array(
				'priority'		 => 80,
				'title'			 => __( 'Forms', 'reign' ),
				'description'	 => '',
			)
			);

			$wp_customize->add_section(
			'reign_forms_style', array(
				'title'			 => __( 'Active Color Setting', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_forms_panel',
				'description'	 => '',
			)
			);

			$wp_customize->add_section(
			'reign_forms_focus_style', array(
				'title'			 => __( 'Focus Color Setting', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_forms_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_forms_color_scheme_default_set();

			foreach ( $default_value_set as $color_scheme_key => $default_set ) {

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_form_text_color',
					'label'				 => esc_attr__( 'Form Text Color', 'reign' ),
					'description'		 => esc_attr__( 'The color of form text.', 'reign' ),
					'section'			 => 'reign_forms_style',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_form_text_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'output'			 => array(
						array(
							'element'	 => 'input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], input[type="tel"], input[type="number"], textarea, select, .cfm-form select, div.fes-form .fes-el .fes-fields select, .select2-container--default .select2-selection--single, .buddypress-wrap .standard-form .groups-members-search input[type=search], .buddypress-wrap .standard-form .groups-members-search input[type=text], .buddypress-wrap .standard-form [data-bp-search] input[type=search], .buddypress-wrap .standard-form [data-bp-search] input[type=text], .buddypress-wrap .standard-form input[type=color], .buddypress-wrap .standard-form input[type=date], .buddypress-wrap .standard-form input[type=datetime-local], .buddypress-wrap .standard-form input[type=datetime], .buddypress-wrap .standard-form input[type=email], .buddypress-wrap .standard-form input[type=month], .buddypress-wrap .standard-form input[type=number], .buddypress-wrap .standard-form input[type=password], .buddypress-wrap .standard-form input[type=range], .buddypress-wrap .standard-form input[type=search], .buddypress-wrap .standard-form input[type=tel], .buddypress-wrap .standard-form input[type=text], .buddypress-wrap .standard-form input[type=time], .buddypress-wrap .standard-form input[type=url], .buddypress-wrap .standard-form input[type=week], .buddypress-wrap .standard-form select, .buddypress-wrap .standard-form textarea, #buddypress .dir-search input[type=search], #buddypress .component-filters select, #buddypress #whats-new-form #whats-new-post-in-box select, #buddypress .dir-search input[type=text], #buddypress .groups-members-search input[type=search], #buddypress .groups-members-search input[type=text], #buddypress .standard-form input[type=color], #buddypress .standard-form input[type=date], #buddypress .standard-form input[type=datetime-local], #buddypress .standard-form input[type=datetime], #buddypress .standard-form input[type=email], #buddypress .standard-form input[type=month], #buddypress .standard-form input[type=number], #buddypress .standard-form input[type=password], #buddypress .standard-form input[type=range], #buddypress .standard-form input[type=search], #buddypress .standard-form input[type=tel], #buddypress .standard-form input[type=text], #buddypress .standard-form input[type=time], #buddypress .standard-form input[type=url], #buddypress .standard-form input[type=week], #buddypress .standard-form select, #buddypress .standard-form textarea, div.fes-form .fes-el.fes-el .fes-fields input[type=email], div.fes-form .fes-el.fes-el .fes-fields input[type=number], div.fes-form .fes-el.fes-el .fes-fields input[type=password], div.fes-form .fes-el.fes-el .fes-fields input[type=text], div.fes-form .fes-el.fes-el .fes-fields input[type=url], div.fes-form .fes-el.fes-el .fes-fields textarea, .dokan-form-control, .select2-container--default .select2-selection--multiple, .buddypress-wrap .select-wrap select, body #buddypress.buddypress-wrap form#whats-new-form #whats-new-options select, .buddypress-wrap #notification-select,  #bbpress-forums fieldset.bbp-form select, select.dokan-form-control, .activity-update-form #whats-new-textarea textarea, form.ac-form .ac-reply-content .ac-textarea textarea,'
                                                    . '                      .medium-editor-element,'
                                                    . '.peepso .ps-input, .ps-lightbox .ps-input, .ps-landing .ps-input, .ps-chat__wrapper .ps-input, .ps-modal__wrapper .ps-input, .psw-profile .ps-input, .gc-widget .ps-input, .widget .ps-input, .ps-widget__wrapper--external .ps-input,'
                                                    . '.peepso .ps-input.ps-input--select:read-only, .ps-lightbox .ps-input.ps-input--select:read-only, .ps-landing .ps-input.ps-input--select:read-only, .ps-chat__wrapper .ps-input.ps-input--select:read-only, .ps-modal__wrapper .ps-input.ps-input--select:read-only, .psw-profile .ps-input.ps-input--select:read-only, .gc-widget .ps-input.ps-input--select:read-only, .widget .ps-input.ps-input--select:read-only, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_form_background_color',
					'label'				 => esc_attr__( 'Form Background Color', 'reign' ),
					'description'		 => esc_attr__( 'The background color of form.', 'reign' ),
					'section'			 => 'reign_forms_style',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_form_background_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'output'			 => array(
						array(
							'element'	 => 'input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], input[type="tel"], input[type="number"], textarea, select, .cfm-form select, div.fes-form .fes-el .fes-fields select, .select2-container--default .select2-selection--single, .buddypress-wrap .standard-form .groups-members-search input[type=search], .buddypress-wrap .standard-form .groups-members-search input[type=text], .buddypress-wrap .standard-form [data-bp-search] input[type=search], .buddypress-wrap .standard-form [data-bp-search] input[type=text], .buddypress-wrap .standard-form input[type=color], .buddypress-wrap .standard-form input[type=date], .buddypress-wrap .standard-form input[type=datetime-local], .buddypress-wrap .standard-form input[type=datetime], .buddypress-wrap .standard-form input[type=email], .buddypress-wrap .standard-form input[type=month], .buddypress-wrap .standard-form input[type=number], .buddypress-wrap .standard-form input[type=password], .buddypress-wrap .standard-form input[type=range], .buddypress-wrap .standard-form input[type=search], .buddypress-wrap .standard-form input[type=tel], .buddypress-wrap .standard-form input[type=text], .buddypress-wrap .standard-form input[type=time], .buddypress-wrap .standard-form input[type=url], .buddypress-wrap .standard-form input[type=week], .buddypress-wrap .standard-form select, .buddypress-wrap .standard-form textarea, #buddypress .dir-search input[type=search], #buddypress .component-filters select, #buddypress #whats-new-form #whats-new-post-in-box select, #buddypress .dir-search input[type=text], #buddypress .groups-members-search input[type=search], #buddypress .groups-members-search input[type=text], #buddypress .standard-form input[type=color], #buddypress .standard-form input[type=date], #buddypress .standard-form input[type=datetime-local], #buddypress .standard-form input[type=datetime], #buddypress .standard-form input[type=email], #buddypress .standard-form input[type=month], #buddypress .standard-form input[type=number], #buddypress .standard-form input[type=password], #buddypress .standard-form input[type=range], #buddypress .standard-form input[type=search], #buddypress .standard-form input[type=tel], #buddypress .standard-form input[type=text], #buddypress .standard-form input[type=time], #buddypress .standard-form input[type=url], #buddypress .standard-form input[type=week], #buddypress .standard-form select, #buddypress .standard-form textarea, div.fes-form .fes-el.fes-el .fes-fields input[type=email], div.fes-form .fes-el.fes-el .fes-fields input[type=number], div.fes-form .fes-el.fes-el .fes-fields input[type=password], div.fes-form .fes-el.fes-el .fes-fields input[type=text], div.fes-form .fes-el.fes-el .fes-fields input[type=url], div.fes-form .fes-el.fes-el .fes-fields textarea, .dokan-form-control, .select2-container--default .select2-selection--multiple, .buddypress-wrap .select-wrap select, body #buddypress.buddypress-wrap form#whats-new-form #whats-new-options select, .buddypress-wrap #notification-select,  #bbpress-forums fieldset.bbp-form select, select.dokan-form-control, .activity-update-form #whats-new-textarea textarea, form.ac-form .ac-reply-content .ac-textarea textarea,'
                                                    . '                     .medium-editor-element,'
                                                    . '.peepso .ps-input, .ps-lightbox .ps-input, .ps-landing .ps-input, .ps-chat__wrapper .ps-input, .ps-modal__wrapper .ps-input, .psw-profile .ps-input, .gc-widget .ps-input, .widget .ps-input, .ps-widget__wrapper--external .ps-input,'
                                                    . '.peepso .ps-input.ps-input--select:read-only, .ps-lightbox .ps-input.ps-input--select:read-only, .ps-landing .ps-input.ps-input--select:read-only, .ps-chat__wrapper .ps-input.ps-input--select:read-only, .ps-modal__wrapper .ps-input.ps-input--select:read-only, .psw-profile .ps-input.ps-input--select:read-only, .gc-widget .ps-input.ps-input--select:read-only, .widget .ps-input.ps-input--select:read-only, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_form_border_color',
					'label'				 => esc_attr__( 'Form Border Color', 'reign' ),
					'description'		 => esc_attr__( 'The border color of form.', 'reign' ),
					'section'			 => 'reign_forms_style',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_form_border_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'output'			 => array(
						array(
							'element'	 => 'input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], input[type="tel"], input[type="number"], textarea, select, .cfm-form select, div.fes-form .fes-el .fes-fields select, .select2-container--default .select2-selection--single, .buddypress-wrap .standard-form .groups-members-search input[type=search], .buddypress-wrap .standard-form .groups-members-search input[type=text], .buddypress-wrap .standard-form [data-bp-search] input[type=search], .buddypress-wrap .standard-form [data-bp-search] input[type=text], .buddypress-wrap .standard-form input[type=color], .buddypress-wrap .standard-form input[type=date], .buddypress-wrap .standard-form input[type=datetime-local], .buddypress-wrap .standard-form input[type=datetime], .buddypress-wrap .standard-form input[type=email], .buddypress-wrap .standard-form input[type=month], .buddypress-wrap .standard-form input[type=number], .buddypress-wrap .standard-form input[type=password], .buddypress-wrap .standard-form input[type=range], .buddypress-wrap .standard-form input[type=search], .buddypress-wrap .standard-form input[type=tel], .buddypress-wrap .standard-form input[type=text], .buddypress-wrap .standard-form input[type=time], .buddypress-wrap .standard-form input[type=url], .buddypress-wrap .standard-form input[type=week], .buddypress-wrap .standard-form select, .buddypress-wrap .standard-form textarea, #buddypress .dir-search input[type=search], #buddypress .component-filters select, #buddypress #whats-new-form #whats-new-post-in-box select, #buddypress .dir-search input[type=text], #buddypress .groups-members-search input[type=search], #buddypress .groups-members-search input[type=text], #buddypress .standard-form input[type=color], #buddypress .standard-form input[type=date], #buddypress .standard-form input[type=datetime-local], #buddypress .standard-form input[type=datetime], #buddypress .standard-form input[type=email], #buddypress .standard-form input[type=month], #buddypress .standard-form input[type=number], #buddypress .standard-form input[type=password], #buddypress .standard-form input[type=range], #buddypress .standard-form input[type=search], #buddypress .standard-form input[type=tel], #buddypress .standard-form input[type=text], #buddypress .standard-form input[type=time], #buddypress .standard-form input[type=url], #buddypress .standard-form input[type=week], #buddypress .standard-form select, #buddypress .standard-form textarea, div.fes-form .fes-el.fes-el .fes-fields input[type=email], div.fes-form .fes-el.fes-el .fes-fields input[type=number], div.fes-form .fes-el.fes-el .fes-fields input[type=password], div.fes-form .fes-el.fes-el .fes-fields input[type=text], div.fes-form .fes-el.fes-el .fes-fields input[type=url], div.fes-form .fes-el.fes-el .fes-fields textarea, .dokan-form-control, .select2-container--default .select2-selection--multiple, .buddypress-wrap .select-wrap select, body #buddypress.buddypress-wrap form#whats-new-form #whats-new-options select, .buddypress-wrap #notification-select,  #bbpress-forums fieldset.bbp-form select, select.dokan-form-control, .activity-update-form #whats-new-textarea textarea, form.ac-form .ac-reply-content .ac-textarea textarea,'
                                                    . '                     .medium-editor-element,'
                                                    . '.peepso .ps-input, .ps-lightbox .ps-input, .ps-landing .ps-input, .ps-chat__wrapper .ps-input, .ps-modal__wrapper .ps-input, .psw-profile .ps-input, .gc-widget .ps-input, .widget .ps-input, .ps-widget__wrapper--external .ps-input,'
                                                    . '.peepso .ps-input.ps-input--select:read-only, .ps-lightbox .ps-input.ps-input--select:read-only, .ps-landing .ps-input.ps-input--select:read-only, .ps-chat__wrapper .ps-input.ps-input--select:read-only, .ps-modal__wrapper .ps-input.ps-input--select:read-only, .psw-profile .ps-input.ps-input--select:read-only, .gc-widget .ps-input.ps-input--select:read-only, .widget .ps-input.ps-input--select:read-only, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only',
							'property'	 => 'border-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_form_placeholder_color',
					'label'				 => esc_attr__( 'Form Placeholder Color', 'reign' ),
					'description'		 => esc_attr__( 'The placeholder color of form.', 'reign' ),
					'section'			 => 'reign_forms_style',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_form_placeholder_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'output'			 => array(
						array(
							'element'	 => 'input::-webkit-input-placeholder, textarea::-webkit-input-placeholder',
							'property'	 => 'color',
						),
						array(
							'element'	 => 'input:-moz-placeholder, input::-moz-placeholder, textarea:-moz-placeholder, textarea::-moz-placeholder',
							'property'	 => 'color',
						),
						array(
							'element'	 => 'input:-ms-input-placeholder, textarea:-ms-input-placeholder',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_form_focus_text_color',
					'label'				 => esc_attr__( 'Form Focus Text Color', 'reign' ),
					'description'		 => esc_attr__( 'The focus color of form text.', 'reign' ),
					'section'			 => 'reign_forms_focus_style',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_form_focus_text_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'output'			 => array(
						array(
							'element'	 => 'input[type="text"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="tel"]:focus, input[type="number"]:focus, textarea:focus, select:focus, .cfm-form select:focus, div.fes-form .fes-el .fes-fields select:focus, .select2-container--default .select2-selection--single:focus, .buddypress-wrap .standard-form .groups-members-search input[type=search]:focus, .buddypress-wrap .standard-form .groups-members-search input[type=text]:focus, .buddypress-wrap .standard-form [data-bp-search] input[type=search]:focus, .buddypress-wrap .standard-form [data-bp-search] input[type=text]:focus, .buddypress-wrap .standard-form input[type=color]:focus, .buddypress-wrap .standard-form input[type=date]:focus, .buddypress-wrap .standard-form input[type=datetime-local]:focus, .buddypress-wrap .standard-form input[type=datetime]:focus, .buddypress-wrap .standard-form input[type=email]:focus, .buddypress-wrap .standard-form input[type=month]:focus, .buddypress-wrap .standard-form input[type=number]:focus, .buddypress-wrap .standard-form input[type=password]:focus, .buddypress-wrap .standard-form input[type=range]:focus, .buddypress-wrap .standard-form input[type=search]:focus, .buddypress-wrap .standard-form input[type=tel]:focus:focus, .buddypress-wrap .standard-form input[type=text]:focus, #buddypress .dir-search input[type=search]:focus, #buddypress .component-filters select:focus, #buddypress #whats-new-form #whats-new-post-in-box select:focus, #buddypress .dir-search input[type=text]:focus, #buddypress .groups-members-search input[type=search]:focus, #buddypress .groups-members-search input[type=text]:focus, #buddypress .standard-form input[type=color]:focus, #buddypress .standard-form input[type=date]:focus, #buddypress .standard-form input[type=datetime-local]:focus, #buddypress .standard-form input[type=datetime]:focus, #buddypress .standard-form input[type=email]:focus, #buddypress .standard-form input[type=month]:focus, #buddypress .standard-form input[type=number]:focus, #buddypress .standard-form input[type=password]:focus, #buddypress .standard-form input[type=range]:focus, #buddypress .standard-form input[type=search]:focus, #buddypress .standard-form input[type=tel]:focus, #buddypress .standard-form input[type=text]:focus, #buddypress .standard-form input[type=time]:focus, #buddypress .standard-form input[type=url]:focus, #buddypress .standard-form input[type=week]:focus, #buddypress .standard-form select:focus, #buddypress .standard-form textarea:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=email]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=number]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=password]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=text]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=url]:focus, div.fes-form .fes-el.fes-el .fes-fields textarea:focus, .buddypress-wrap .select-wrap select:focus, body #buddypress.buddypress-wrap form#whats-new-form #whats-new-options select:focus, .buddypress-wrap #notification-select:focus,  #bbpress-forums fieldset.bbp-form select:focus, select.dokan-form-control:focus, .activity-update-form #whats-new-textarea textarea:focus, form.ac-form .ac-reply-content .ac-textarea textarea:focus,'
                                                    . '                     .medium-editor-element:focus,'
                                                    . '.peepso .ps-input:hover, .ps-lightbox .ps-input:hover, .ps-landing .ps-input:hover, .ps-chat__wrapper .ps-input:hover, .ps-modal__wrapper .ps-input:hover, .psw-profile .ps-input:hover, .gc-widget .ps-input:hover, .widget .ps-input:hover, .ps-widget__wrapper--external .ps-input:hover, .peepso .ps-input:focus, .ps-lightbox .ps-input:focus, .ps-landing .ps-input:focus, .ps-chat__wrapper .ps-input:focus, .ps-modal__wrapper .ps-input:focus, .psw-profile .ps-input:focus, .gc-widget .ps-input:focus, .widget .ps-input:focus, .ps-widget__wrapper--external .ps-input:focus,'
                                                    . '.peepso .ps-input.ps-input--select:read-only:hover, .ps-lightbox .ps-input.ps-input--select:read-only:hover, .ps-landing .ps-input.ps-input--select:read-only:hover, .ps-chat__wrapper .ps-input.ps-input--select:read-only:hover, .ps-modal__wrapper .ps-input.ps-input--select:read-only:hover, .psw-profile .ps-input.ps-input--select:read-only:hover, .gc-widget .ps-input.ps-input--select:read-only:hover, .widget .ps-input.ps-input--select:read-only:hover, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only:hover, .peepso .ps-input.ps-input--select:read-only:focus, .ps-lightbox .ps-input.ps-input--select:read-only:focus, .ps-landing .ps-input.ps-input--select:read-only:focus, .ps-chat__wrapper .ps-input.ps-input--select:read-only:focus, .ps-modal__wrapper .ps-input.ps-input--select:read-only:focus, .psw-profile .ps-input.ps-input--select:read-only:focus, .gc-widget .ps-input.ps-input--select:read-only:focus, .widget .ps-input.ps-input--select:read-only:focus, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only:focus',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_form_focus_background_color',
					'label'				 => esc_attr__( 'Form Focus Background Color', 'reign' ),
					'description'		 => esc_attr__( 'The focus background color of form.', 'reign' ),
					'section'			 => 'reign_forms_focus_style',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_form_focus_background_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'output'			 => array(
						array(
							'element'	 => 'input[type="text"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="tel"]:focus, input[type="number"]:focus, textarea:focus, select:focus, .cfm-form select:focus, div.fes-form .fes-el .fes-fields select:focus, .select2-container--default .select2-selection--single:focus, .buddypress-wrap .standard-form .groups-members-search input[type=search]:focus, .buddypress-wrap .standard-form .groups-members-search input[type=text]:focus, .buddypress-wrap .standard-form [data-bp-search] input[type=search]:focus, .buddypress-wrap .standard-form [data-bp-search] input[type=text]:focus, .buddypress-wrap .standard-form input[type=color]:focus, .buddypress-wrap .standard-form input[type=date]:focus, .buddypress-wrap .standard-form input[type=datetime-local]:focus, .buddypress-wrap .standard-form input[type=datetime]:focus, .buddypress-wrap .standard-form input[type=email]:focus, .buddypress-wrap .standard-form input[type=month]:focus, .buddypress-wrap .standard-form input[type=number]:focus, .buddypress-wrap .standard-form input[type=password]:focus, .buddypress-wrap .standard-form input[type=range]:focus, .buddypress-wrap .standard-form input[type=search]:focus, .buddypress-wrap .standard-form input[type=tel]:focus:focus, .buddypress-wrap .standard-form input[type=text]:focus, #buddypress .dir-search input[type=search]:focus, #buddypress .component-filters select:focus, #buddypress #whats-new-form #whats-new-post-in-box select:focus, #buddypress .dir-search input[type=text]:focus, #buddypress .groups-members-search input[type=search]:focus, #buddypress .groups-members-search input[type=text]:focus, #buddypress .standard-form input[type=color]:focus, #buddypress .standard-form input[type=date]:focus, #buddypress .standard-form input[type=datetime-local]:focus, #buddypress .standard-form input[type=datetime]:focus, #buddypress .standard-form input[type=email]:focus, #buddypress .standard-form input[type=month]:focus, #buddypress .standard-form input[type=number]:focus, #buddypress .standard-form input[type=password]:focus, #buddypress .standard-form input[type=range]:focus, #buddypress .standard-form input[type=search]:focus, #buddypress .standard-form input[type=tel]:focus, #buddypress .standard-form input[type=text]:focus, #buddypress .standard-form input[type=time]:focus, #buddypress .standard-form input[type=url]:focus, #buddypress .standard-form input[type=week]:focus, #buddypress .standard-form select:focus, #buddypress .standard-form textarea:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=email]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=number]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=password]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=text]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=url]:focus, div.fes-form .fes-el.fes-el .fes-fields textarea:focus, .buddypress-wrap .select-wrap select:focus, body #buddypress.buddypress-wrap form#whats-new-form #whats-new-options select:focus, .buddypress-wrap #notification-select:focus,  #bbpress-forums fieldset.bbp-form select:focus, select.dokan-form-control:focus, .activity-update-form #whats-new-textarea textarea:focus, form.ac-form .ac-reply-content .ac-textarea textarea:focus,'
                                                    . '                     .medium-editor-element:focus,'
                                                    . '.peepso .ps-input:hover, .ps-lightbox .ps-input:hover, .ps-landing .ps-input:hover, .ps-chat__wrapper .ps-input:hover, .ps-modal__wrapper .ps-input:hover, .psw-profile .ps-input:hover, .gc-widget .ps-input:hover, .widget .ps-input:hover, .ps-widget__wrapper--external .ps-input:hover, .peepso .ps-input:focus, .ps-lightbox .ps-input:focus, .ps-landing .ps-input:focus, .ps-chat__wrapper .ps-input:focus, .ps-modal__wrapper .ps-input:focus, .psw-profile .ps-input:focus, .gc-widget .ps-input:focus, .widget .ps-input:focus, .ps-widget__wrapper--external .ps-input:focus,'
                                                    . '.peepso .ps-input.ps-input--select:read-only:hover, .ps-lightbox .ps-input.ps-input--select:read-only:hover, .ps-landing .ps-input.ps-input--select:read-only:hover, .ps-chat__wrapper .ps-input.ps-input--select:read-only:hover, .ps-modal__wrapper .ps-input.ps-input--select:read-only:hover, .psw-profile .ps-input.ps-input--select:read-only:hover, .gc-widget .ps-input.ps-input--select:read-only:hover, .widget .ps-input.ps-input--select:read-only:hover, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only:hover, .peepso .ps-input.ps-input--select:read-only:focus, .ps-lightbox .ps-input.ps-input--select:read-only:focus, .ps-landing .ps-input.ps-input--select:read-only:focus, .ps-chat__wrapper .ps-input.ps-input--select:read-only:focus, .ps-modal__wrapper .ps-input.ps-input--select:read-only:focus, .psw-profile .ps-input.ps-input--select:read-only:focus, .gc-widget .ps-input.ps-input--select:read-only:focus, .widget .ps-input.ps-input--select:read-only:focus, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only:focus',
							'property'	 => 'background-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_form_focus_border_color',
					'label'				 => esc_attr__( 'Form Focus Border Color', 'reign' ),
					'description'		 => esc_attr__( 'The focus border color of form.', 'reign' ),
					'section'			 => 'reign_forms_focus_style',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_form_focus_border_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'output'			 => array(
						array(
							'element'	 => 'input[type="text"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="tel"]:focus, input[type="number"]:focus, textarea:focus, select:focus, .cfm-form select:focus, div.fes-form .fes-el .fes-fields select:focus, .select2-container--default .select2-selection--single:focus, .buddypress-wrap .standard-form .groups-members-search input[type=search]:focus, .buddypress-wrap .standard-form .groups-members-search input[type=text]:focus, .buddypress-wrap .standard-form [data-bp-search] input[type=search]:focus, .buddypress-wrap .standard-form [data-bp-search] input[type=text]:focus, .buddypress-wrap .standard-form input[type=color]:focus, .buddypress-wrap .standard-form input[type=date]:focus, .buddypress-wrap .standard-form input[type=datetime-local]:focus, .buddypress-wrap .standard-form input[type=datetime]:focus, .buddypress-wrap .standard-form input[type=email]:focus, .buddypress-wrap .standard-form input[type=month]:focus, .buddypress-wrap .standard-form input[type=number]:focus, .buddypress-wrap .standard-form input[type=password]:focus, .buddypress-wrap .standard-form input[type=range]:focus, .buddypress-wrap .standard-form input[type=search]:focus, .buddypress-wrap .standard-form input[type=tel]:focus:focus, .buddypress-wrap .standard-form input[type=text]:focus, #buddypress .dir-search input[type=search]:focus, #buddypress .component-filters select:focus, #buddypress #whats-new-form #whats-new-post-in-box select:focus, #buddypress .dir-search input[type=text]:focus, #buddypress .groups-members-search input[type=search]:focus, #buddypress .groups-members-search input[type=text]:focus, #buddypress .standard-form input[type=color]:focus, #buddypress .standard-form input[type=date]:focus, #buddypress .standard-form input[type=datetime-local]:focus, #buddypress .standard-form input[type=datetime]:focus, #buddypress .standard-form input[type=email]:focus, #buddypress .standard-form input[type=month]:focus, #buddypress .standard-form input[type=number]:focus, #buddypress .standard-form input[type=password]:focus, #buddypress .standard-form input[type=range]:focus, #buddypress .standard-form input[type=search]:focus, #buddypress .standard-form input[type=tel]:focus, #buddypress .standard-form input[type=text]:focus, #buddypress .standard-form input[type=time]:focus, #buddypress .standard-form input[type=url]:focus, #buddypress .standard-form input[type=week]:focus, #buddypress .standard-form select:focus, #buddypress .standard-form textarea:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=email]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=number]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=password]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=text]:focus, div.fes-form .fes-el.fes-el .fes-fields input[type=url]:focus, div.fes-form .fes-el.fes-el .fes-fields textarea:focus, .buddypress-wrap .select-wrap select:focus, body #buddypress.buddypress-wrap form#whats-new-form #whats-new-options select:focus, .buddypress-wrap #notification-select:focus,  #bbpress-forums fieldset.bbp-form select:focus, select.dokan-form-control:focus, .activity-update-form #whats-new-textarea textarea:focus, form.ac-form .ac-reply-content .ac-textarea textarea:focus,'
                                                    . '                     .medium-editor-element:focus,'
                                                    . '.peepso .ps-input:hover, .ps-lightbox .ps-input:hover, .ps-landing .ps-input:hover, .ps-chat__wrapper .ps-input:hover, .ps-modal__wrapper .ps-input:hover, .psw-profile .ps-input:hover, .gc-widget .ps-input:hover, .widget .ps-input:hover, .ps-widget__wrapper--external .ps-input:hover, .peepso .ps-input:focus, .ps-lightbox .ps-input:focus, .ps-landing .ps-input:focus, .ps-chat__wrapper .ps-input:focus, .ps-modal__wrapper .ps-input:focus, .psw-profile .ps-input:focus, .gc-widget .ps-input:focus, .widget .ps-input:focus, .ps-widget__wrapper--external .ps-input:focus,'
                                                    . '.peepso .ps-input.ps-input--select:read-only:hover, .ps-lightbox .ps-input.ps-input--select:read-only:hover, .ps-landing .ps-input.ps-input--select:read-only:hover, .ps-chat__wrapper .ps-input.ps-input--select:read-only:hover, .ps-modal__wrapper .ps-input.ps-input--select:read-only:hover, .psw-profile .ps-input.ps-input--select:read-only:hover, .gc-widget .ps-input.ps-input--select:read-only:hover, .widget .ps-input.ps-input--select:read-only:hover, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only:hover, .peepso .ps-input.ps-input--select:read-only:focus, .ps-lightbox .ps-input.ps-input--select:read-only:focus, .ps-landing .ps-input.ps-input--select:read-only:focus, .ps-chat__wrapper .ps-input.ps-input--select:read-only:focus, .ps-modal__wrapper .ps-input.ps-input--select:read-only:focus, .psw-profile .ps-input.ps-input--select:read-only:focus, .gc-widget .ps-input.ps-input--select:read-only:focus, .widget .ps-input.ps-input--select:read-only:focus, .ps-widget__wrapper--external .ps-input.ps-input--select:read-only:focus',
							'property'	 => 'border-color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'color',
					'settings'			 => $color_scheme_key . '-' . 'reign_form_focus_placeholder_color',
					'label'				 => esc_attr__( 'Form Focus Placeholder Color', 'reign' ),
					'description'		 => esc_attr__( 'The focus placeholder color of form.', 'reign' ),
					'section'			 => 'reign_forms_focus_style',
					'default'			 => $default_value_set[ $color_scheme_key ][ 'reign_form_focus_placeholder_color' ],
					'priority'			 => 10,
					'choices'			 => array( 'alpha' => true ),
					'output'			 => array(
						array(
							'element'	 => 'input:focus::-webkit-input-placeholder, textarea:focus::-webkit-input-placeholder',
							'property'	 => 'color',
						),
						array(
							'element'	 => 'input:focus:-moz-placeholder, input:focus::-moz-placeholder, textarea:focus:-moz-placeholder, textarea:focus::-moz-placeholder',
							'property'	 => 'color',
						),
						array(
							'element'	 => 'nput:focus:-ms-input-placeholder, textarea:focus:-ms-input-placeholder',
							'property'	 => 'color',
						)
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_color_scheme',
							'operator'	 => '===',
							'value'		 => $color_scheme_key,
						),
					),
				);
			}

			return $fields;
		}

	}

	endif;


/**
 * Main instance of Reign_Kirki_Forms.
 * @return Reign_Kirki_Forms
 */
Reign_Kirki_Forms::instance();