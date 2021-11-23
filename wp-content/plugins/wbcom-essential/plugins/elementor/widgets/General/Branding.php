<?php

namespace WBCOM_ESSENTIAL\ELEMENTOR\Widgets\General;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use WBCOM_ESSENTIAL\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;



class Branding extends \Elementor\Widget_Base {

	public function get_name() {
		return 'wbcom-branding';
	}

	public function get_title() {
		return esc_html__( 'Branding', 'wbcom-essential' );
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_categories() {
		return array( 'wbcom-elements' );
	}

	protected function _register_controls() {

		do_action( 'wbcom_essential/widget/branding/settings', $this );

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Branding', 'reign' ),
			)
		);

		$this->add_control(
			'el_site_branding',
			array(
				'label'       => __( 'Branding Type', 'reign' ),
				'description' => __( 'Your theme must declare the "add_theme_support( \'custom-logo\')" for the logo to work', 'reign' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'title' => __( 'Title', 'elementor' ),
					'logo'  => __( 'Logo', 'elementor' ),
				),
				'default'     => 'title',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'reign' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'reign' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'reign' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'reign' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'prefix_class' => 'elementor%s-align-',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => __( 'Brand', 'reign' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'branding_title_color',
			array(
				'label'     => __( 'Title Color', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'el_site_branding' => 'title',
				),
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'default'   => '#333333',
				'selectors' => array(
					'{{WRAPPER}} .elementor-branding .site-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'branding_title_hover',
			array(
				'label'     => __( 'Hover', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'el_site_branding' => 'title',
				),
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-branding .site-title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_padding',
			array(
				'label'      => __( 'Title Padding - Default 1em', 'reign' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'condition'  => array(
					'el_site_branding' => 'title',
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-branding .site-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'reign' ),
				'condition' => array(
					'el_site_branding' => 'title',
				),
				'scheme'    => Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .elementor-branding .site-title',
			)
		);

		$this->add_control(
			'logo_padding',
			array(
				'label'      => __( 'Title Padding - Default 1em', 'reign' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'condition'  => array(
					'el_site_branding' => 'logo',
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-branding .custom-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc_style',
			array(
				'label'     => __( 'Description Options', 'reign' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'el_site_branding' => 'title',
				),
			)
		);

		$this->add_control(
			'branding_description_color',
			array(
				'label'     => __( 'Description Color', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'el_site_branding' => 'title',
				),
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-branding .site-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'desc_padding',
			array(
				'label'      => __( 'Description Padding - Default 1em', 'reign' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'condition'  => array(
					'el_site_branding' => 'title',
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-branding .site-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'desc_typography',
				'label'     => __( 'Typography', 'reign' ),
				'condition' => array(
					'el_site_branding' => 'title',
				),
				'scheme'    => Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .elementor-branding .site-description',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_branding_borders',
			array(
				'label' => __( 'Branding Border', 'reign' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border',
				'label'    => __( 'Border', 'reign' ),
				'default'  => '1px',
				'selector' => '{{WRAPPER}} .elementor-branding',
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'reign' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-branding' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		do_action( 'wbcom_essential_branding_elementor_controls', $this );

	}

	protected function branding_output() {
		$settings = $this->get_settings();

		if ( $settings['el_site_branding'] == 'title' ) {
			$this->render_title();
		} elseif ( $settings['el_site_branding'] == 'logo' ) {
			$this->elementor_the_site_logo();
		}
	}

	protected function elementor_the_site_logo() {
		if ( function_exists( 'the_custom_logo' ) ) {
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				$this->render_title();
			}
		} else {
			$this->render_title();
		}
	}

	protected function render_title() {
		?>
  <span class="site-title">
		<?php
		$title = get_bloginfo( 'name' );
		?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( $title ); /* WPCS: xss ok. */ ?>" alt="<?php echo esc_attr( $title ); ?>">
		<?php bloginfo( 'name' ); ?>
	</a>
  </span>
		<?php
		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) :
			?>
	<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
	endif;
	}

	protected function render() {

		$settings = $this->get_settings();
		?>

		<div id="elementor-branding" class="elementor-branding">
			<div class="header-title">
				<?php
				$this->branding_output();
				?>
			</div>
		</div>
		  <?php
	}

}
