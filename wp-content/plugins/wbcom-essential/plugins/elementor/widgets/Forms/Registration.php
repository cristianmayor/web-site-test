<?php

namespace WBCOM_ESSENTIAL\ELEMENTOR\Widgets\Forms;

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



class Registration  extends \Elementor\Widget_Base {

	public function get_name() {
		return 'wbcom-registration';
	}

	public function get_title() {
		return esc_html__( 'Registration', 'wbcom-essential' );
	}

	public function get_icon() {
		return 'eicon-lock-user';
	}

	public function get_categories() {
		return array( 'wbcom-elements' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_fields_content',
			array(
				'label' => __( 'Form Fields', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'     => __( 'Label', 'wbcom-essential' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'label_on'  => __( 'Show', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'input_size',
			array(
				'label'   => __( 'Input Size', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'xs' => __( 'Extra Small', 'wbcom-essential' ),
					'sm' => __( 'Small', 'wbcom-essential' ),
					'md' => __( 'Medium', 'wbcom-essential' ),
					'lg' => __( 'Large', 'wbcom-essential' ),
					'xl' => __( 'Extra Large', 'wbcom-essential' ),
				),
				'default' => 'sm',
			)
		);

		  $this->end_controls_section();

		$this->start_controls_section(
			'section_button_content',
			array(
				'label' => __( 'Register Button', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Text', 'wbcom-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Register', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'   => __( 'Size', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'xs' => __( 'Extra Small', 'wbcom-essential' ),
					'sm' => __( 'Small', 'wbcom-essential' ),
					'md' => __( 'Medium', 'wbcom-essential' ),
					'lg' => __( 'Large', 'wbcom-essential' ),
					'xl' => __( 'Extra Large', 'wbcom-essential' ),
				),
				'default' => 'sm',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'wbcom-essential' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'start'   => array(
						'title' => __( 'Left', 'wbcom-essential' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'wbcom-essential' ),
						'icon'  => 'fa fa-align-center',
					),
					'end'     => array(
						'title' => __( 'Right', 'wbcom-essential' ),
						'icon'  => 'fa fa-align-right',
					),
					'stretch' => array(
						'title' => __( 'Justified', 'wbcom-essential' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'prefix_class' => 'elementor%s-button-align-',
				'default'      => '',
			)
		);

		  $this->end_controls_section();

		$this->start_controls_section(
			'section_login_content',
			array(
				'label' => __( 'Additional Options', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'redirect_after_registration',
			array(
				'label'     => __( 'Redirect After Registration', 'wbcom-essential' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_off' => __( 'Off', 'wbcom-essential' ),
				'label_on'  => __( 'On', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'redirect_url',
			array(
				'type'          => Controls_Manager::URL,
				'show_label'    => false,
				'show_external' => false,
				'separator'     => false,
				'placeholder'   => 'http://your-link.com/',
				'description'   => __( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'wbcom-essential' ),
				'condition'     => array(
					'redirect_after_registration' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_logged_in_message',
			array(
				'label'     => __( 'Logged in Message', 'wbcom-essential' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'label_on'  => __( 'Show', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'custom_labels',
			array(
				'label'     => __( 'Custom Label', 'wbcom-essential' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'show_labels' => 'yes',
				),
			)
		);

		$this->add_control(
			'user_label',
			array(
				'label'     => __( 'Username Label', 'wbcom-essential' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( ' Username', 'wbcom-essential' ),
				'condition' => array(
					'show_labels'   => 'yes',
					'custom_labels' => 'yes',
				),
			)
		);

		$this->add_control(
			'user_placeholder',
			array(
				'label'     => __( 'Username Placeholder', 'wbcom-essential' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( ' Username', 'wbcom-essential' ),
				'condition' => array(
					'show_labels'   => 'yes',
					'custom_labels' => 'yes',
				),
			)
		);

		$this->add_control(
			'email_label',
			array(
				'label'     => __( 'Email Label', 'wbcom-essential' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( ' Email', 'wbcom-essential' ),
				'condition' => array(
					'show_labels'   => 'yes',
					'custom_labels' => 'yes',
				),
			)
		);

		$this->add_control(
			'email_placeholder',
			array(
				'label'     => __( 'Email Placeholder', 'wbcom-essential' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( ' Email', 'wbcom-essential' ),
				'condition' => array(
					'show_labels'   => 'yes',
					'custom_labels' => 'yes',
				),
			)
		);

		  $this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Form', 'wbcom-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_gap',
			array(
				'label'     => __( 'Rows Gap', 'wbcom-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '10',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'links_color',
			array(
				'label'     => __( 'Links Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group > a' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_3,
				),
			)
		);

		$this->add_control(
			'links_hover_color',
			array(
				'label'     => __( 'Links Hover Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group > a:hover' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_4,
				),
			)
		);

		  $this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			array(
				'label'     => __( 'Label', 'wbcom-essential' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_labels!' => '',
				),
			)
		);

		$this->add_control(
			'label_spacing',
			array(
				'label'     => __( 'Spacing', 'wbcom-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '0',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'body {{WRAPPER}} .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Text Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_3,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-form-fields-wrapper',
				'scheme'   => Typography::TYPOGRAPHY_3,
			)
		);

		  $this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			array(
				'label' => __( 'Fields', 'wbcom-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'field_text_color',
			array(
				'label'     => __( 'Text Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_3,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'field_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field, {{WRAPPER}} .elementor-field-subgroup label',
				'scheme'   => Typography::TYPOGRAPHY_3,
			)
		);

		$this->add_control(
			'field_background_color',
			array(
				'label'     => __( 'Background Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'field_border_color',
			array(
				'label'     => __( 'Border Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'field_border_width',
			array(
				'label'       => __( 'Border Width', 'wbcom-essential' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units'  => array( 'px' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'field_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wbcom-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Button', 'wbcom-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		  $this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'wbcom-essential' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-button',
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_4,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => __( 'Border', 'wbcom-essential' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .elementor-button',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wbcom-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_text_padding',
			array(
				'label'      => __( 'Text Padding', 'wbcom-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		  $this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => __( 'Text Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_hover_color',
			array(
				'label'     => __( 'Background Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'wbcom-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'button_border_border!' => '',
				),
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label' => __( 'Animation', 'wbcom-essential' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		  $this->end_controls_tab();

		  $this->end_controls_tabs();

		  $this->end_controls_section();
	}

	private function form_fields_render_attributes() {
		$settings = $this->get_settings();

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute(
			array(
				'wrapper'      => array(
					'class' => array(
						'elementor-form-fields-wrapper',
					),
				),
				'field-group'  => array(
					'class' => array(
						'elementor-field-type-text',
						'elementor-field-group',
						'elementor-column',
						'elementor-col-100',
					),
				),
				'submit-group' => array(
					'class' => array(
						'elementor-field-group',
						'elementor-column',
						'elementor-field-type-submit',
						'elementor-col-100',
					),
				),

				'button'       => array(
					'class' => array(
						'elementor-button',
					),
					'name'  => 'wp-submit',
				),
				'user_label'   => array(
					'for' => 'user',
				),
				'email_label'  => array(
					'for' => 'email_addr',
				),
				'user_input'   => array(
					'type'        => 'text',
					'name'        => 'log',
					'id'          => 'user',
					'placeholder' => $settings['user_placeholder'],
					'class'       => array(
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					),
				),
				'email_input'  => array(
					'type'        => 'text',
					'name'        => 'email_addr',
					'id'          => 'email_addr',
					'placeholder' => $settings['email_placeholder'],
					'class'       => array(
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					),
				),
				// TODO: add unique ID
				'label_user'   => array(
					'for'   => 'user',
					'class' => 'elementor-field-label',
				),
				'label_email'  => array(
					'for'   => 'user',
					'class' => 'elementor-field-label',
				),
			)
		);

		if ( ! $settings['show_labels'] ) {
			$this->add_render_attribute( 'label', 'class', 'elementor-screen-only' );
		}

		$this->add_render_attribute( 'field-group', 'class', 'elementor-field-required' )
			->add_render_attribute( 'input', 'required', true )
			->add_render_attribute( 'input', 'aria-required', 'true' );

	}

	protected function render() {
		$settings    = $this->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );

		if ( 'yes' === $settings['redirect_after_registration'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}

		if ( is_user_logged_in() && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			if ( 'yes' === $settings['show_logged_in_message'] ) {
				$current_user = wp_get_current_user();

				echo '<div class="elementor-login">' .
					sprintf( __( 'You are Logged in as %1$s (<a href="%2$s">Logout</a>)', 'wbcom-essential' ), $current_user->display_name, wp_logout_url( $current_url ) ) .
					'</div>';
			}

			return;
		}

		$this->form_fields_render_attributes();
		?>
		<style type="text/css">
			/* The alert message box */
			.wbcom-elementor-registration-form .alert {
				padding: 20px;
				background-color: #f44336; /* Red */
				color: white;
				margin-bottom: 15px;
			}
			/* The close button */
			.wbcom-elementor-registration-form .closebtn {
				margin-left: 15px;
				color: white;
				font-weight: bold;
				float: right;
				font-size: 22px;
				line-height: 20px;
				cursor: pointer;
				transition: 0.3s;
			}
			/* When moving the mouse over the close button */
			.wbcom-elementor-registration-form .closebtn:hover {
				color: black;
			}
		</style>
		<form class="elementor-login elementor-form wbcom-elementor-form wbcom-elementor-registration-form" method="post" action="<?php echo wp_login_url(); ?>">
			<input type="hidden" name="wbcom_action_to_to" value="registration">
			<div class="alert" style="display: none;">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
				<span class="error-message">This is an alert box.</span>
			</div>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_url ); ?>">
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
					<?php
					if ( $settings['show_labels'] ) {
						echo '<label ' . $this->get_render_attribute_string( 'user_label' ) . '>' . $settings['user_label'] . '</label>';
					}

					echo '<input size="1" ' . $this->get_render_attribute_string( 'user_input' ) . '>';

					?>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
					<?php
					if ( $settings['show_labels'] ) {
						echo '<label ' . $this->get_render_attribute_string( 'email_label' ) . '>' . $settings['email_label'] . '</label>';
					}

					echo '<input size="1" ' . $this->get_render_attribute_string( 'email_input' ) . '>';

					?>
				</div>

				<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
							<?php if ( ! empty( $settings['button_text'] ) ) : ?>
								<span class="elementor-button-text"><?php echo $settings['button_text']; ?></span>
							<?php endif; ?>
					</button>					
				</div>				
			</div>
		</form>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="elementor-login elementor-form">
			<div class="elementor-form-fields-wrapper">
				<#
					fieldGroupClasses = 'elementor-field-group elementor-column elementor-col-100 elementor-field-type-text';
				#>
				<div class="{{ fieldGroupClasses }}">
					<# if ( settings.show_labels ) { #>
						<label class="elementor-field-label" for="user" >{{{ settings.user_label }}}</label>
						<# } #>
							<input size="1" type="text" id="user" placeholder="{{ settings.user_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" />
				</div>
				<div class="{{ fieldGroupClasses }}">
					<# if ( settings.show_labels ) { #>
						<label class="elementor-field-label" for="user" >{{{ settings.email_label }}}</label>
						<# } #>
							<input size="1" type="text" id="user" placeholder="{{ settings.email_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" />
				</div>

				<div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100">
					<button type="submit" class="elementor-button elementor-size-{{ settings.button_size }}">
						<# if ( settings.button_text ) { #>
							<span class="elementor-button-text">{{ settings.button_text }}</span>
						<# } #>
					</button>
				</div>

			</div>
		</div>
		<?php
	}
}
