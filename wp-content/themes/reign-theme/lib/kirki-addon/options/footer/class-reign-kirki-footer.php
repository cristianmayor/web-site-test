<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Footer' ) ) :

	/**
	 * @class Reign_Kirki_Footer
	 */
	class Reign_Kirki_Footer {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Footer
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Footer Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Footer is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Footer - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Footer Constructor.
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
			add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
			add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		}

		public function add_panels_and_sections( $wp_customize ) {

			$wp_customize->add_panel(
			'reign_footer_panel', array(
				'priority'		 => 200,
				'title'			 => __( 'Footer', 'reign' ),
				'description'	 => '',
			)
			);

			$wp_customize->add_section(
			'reign_footer_settings', array(
				'title'			 => __( 'Settings', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_footer_panel',
				'description'	 => '',
			)
			);

			$wp_customize->add_section(
			'reign_footer_copyright', array(
				'title'			 => __( 'Copyright', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_footer_panel',
				'description'	 => '',
			)
			);

			$wp_customize->add_section(
			'reign_footer_typography', array(
				'title'			 => __( 'Footer Typography', 'reign' ),
				'priority'		 => 10,
				'panel'			 => 'reign_footer_panel',
				'description'	 => '',
			)
			);
		}

		public function add_fields( $fields ) {

			$default_value_set = reign_get_customizer_default_value_set();

			$fields[] = array(
				'type'			 => 'switch',
				'settings'		 => 'reign_footer_copyright_enable',
				'label'			 => esc_attr__( 'Enable Footer Copyright', 'reign' ),
				'description'	 => '',
				'section'		 => 'reign_footer_copyright',
				'default'		 => 1,
				'priority'		 => 10,
				'choices'		 => array(
					'on'	 => esc_attr__( 'Enable', 'reign' ),
					'off'	 => esc_attr__( 'Disable', 'reign' ),
				),
			);

			$fields[] = array(
				'type'				 => 'textarea',
				'settings'			 => 'reign_footer_copyright_text',
				'label'				 => esc_attr__( 'Copyright Text', 'reign' ),
				'description'		 => esc_attr__( 'Enter the text that displays in the copyright bar. HTML markup can be used.', 'reign' ),
				'section'			 => 'reign_footer_copyright',
				'default'			 => '&copy; ' . date( 'Y' ) . ' - Reign | Theme by <a href="' . esc_url( 'https://wbcomdesigns.com/' ) . '" target="_blank">Wbcom Designs</a>',
				'priority'			 => 10,
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_footer_copyright_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
				),
			);

			$fields[] = array(
				'type'				 => 'select',
				'settings'			 => 'reign_footer_copyright_alignment',
				'label'				 => esc_attr__( 'Alignment', 'reign' ),
				'description'		 => '',
				'section'			 => 'reign_footer_copyright',
				'default'			 => 'center',
				'priority'			 => 10,
				'choices'			 => array(
					'left'	 => esc_attr__( 'Left', 'reign' ),
					'right'	 => esc_attr__( 'Right', 'reign' ),
					'center' => esc_attr__( 'Center', 'reign' ),
				),
				'output'			 => array(
					array(
						'function'	 => 'css',
						'element'	 => 'footer div#reign-copyright-text .container',
						'property'	 => 'text-align',
					),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_footer_copyright_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
				),
			);


			$fields[] = array(
				'type'				 => 'spacing',
				'settings'			 => 'reign_footer_copyright_spacing',
				'label'				 => esc_attr__( 'Padding (px)', 'reign' ),
				'description'		 => '',
				'section'			 => 'reign_footer_copyright',
				'default'			 => array(
					'top'	 => '20px',
					'right'	 => '0px',
					'bottom' => '20px',
					'left'	 => '0px',
				),
				'priority'			 => 10,
				'output'			 => array(
					array(
						'function'	 => 'css',
						'element'	 => 'footer div#reign-copyright-text',
						'property'	 => 'padding',
					),
				),
				'active_callback'	 => array(
					array(
						'setting'	 => 'reign_footer_copyright_enable',
						'operator'	 => '===',
						'value'		 => true,
					),
				),
			);

			/**
			 *  Footer Typography
			 */
			$fields[] = array(
				'type'		 => 'switch',
				'settings'	 => 'reign_footer_customize_typography',
				'label'		 => esc_html__( 'Customize Typography ?', 'reign' ),
				'section'	 => 'reign_footer_typography',
				'default'	 => 'off',
				'choices'	 => array(
					'on'	 => esc_attr__( 'Yes', 'reign' ),
					'off'	 => esc_attr__( 'No', 'reign' )
				),
			);

			$fields[] = array(
				'type'				 => 'typography',
				'settings'			 => 'reign_footer_widget_title',
				'label'				 => esc_attr__( 'Widget Title Typography', 'reign' ),
				'section'			 => 'reign_footer_typography',
				'default'			 => array(
					'font-family'	 => '',
					'variant'		 => '',
					'font-size'		 => '20px',
					'line-height'	 => '',
					'letter-spacing' => '',
					'text-transform' => 'none',
				),
				'priority'			 => 10,
				'output'			 => array(
					array(
						'element' => '#footer-area .widget-title span',
					),
				),
				'active_callback'	 => array(
					array( 'setting' => 'reign_footer_customize_typography', 'operator' => '==', 'value' => '1' )
				)
			);

			$fields[] = array(
				'type'				 => 'typography',
				'settings'			 => 'reign_footer_content_typo',
				'label'				 => esc_attr__( 'Content Typography', 'reign' ),
				'section'			 => 'reign_footer_typography',
				'default'			 => array(
					'font-family'	 => '',
					'variant'		 => '',
					'font-size'		 => '16px',
					'line-height'	 => '',
					'letter-spacing' => '',
					'text-transform' => 'none',
				),
				'priority'			 => 10,
				'output'			 => array(
					array(
						'element' => '#footer-area, #footer-area p, #footer-area a, .widget-area a:not(.button), footer div#reign-copyright-text',
					),
				),
				'active_callback'	 => array(
					array( 'setting' => 'reign_footer_customize_typography', 'operator' => '==', 'value' => '1' )
				)
			);

			$fields[] = array(
				'type'		 => 'color',
				'settings'	 => 'reign_footer_copyright_border',
				'label'		 => esc_html__( 'Copyright Border Color', 'reign' ),
				'section'	 => 'reign_footer_typography',
				'default'	 => '#e3e3e3',
                                'choices' => array('alpha' => true),
				'output'	 => array(
					array(
						'element'	 => 'footer div#reign-copyright-text',
						'property'	 => 'border-color',
					),
				),
			);

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Footer.
 * @return Reign_Kirki_Footer
 */
Reign_Kirki_Footer::instance();
