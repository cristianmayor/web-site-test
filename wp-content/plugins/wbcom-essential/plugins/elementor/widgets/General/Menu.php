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



class Menu extends \Elementor\Widget_Base {

	public function get_name() {
		return 'wbcom-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu', 'wbcom-essential' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	public function get_categories() {
		return array( 'wbcom-elements' );
	}

	/**
	 * We always show this item in the panel.
	 *
	 * @return bool
	 */
	public function show_in_panel() {
		return true;
	}

	/**
	 * This registers our controls for the widget. Currently there are none but we may add options down the track.
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_reign_wp_menu',
			array(
				'label' => __( 'WordPress Menu', 'reign' ),
			)
		);

		$this->add_control(
			'desc',
			array(
				'label' => sprintf( __( 'Choose the WordPress menu to output below. To change menu items please go to the <a href="%s" target="_blank">WordPress Menu Editor</a> page.', 'reign' ), admin_url( 'nav-menus.php' ) ),
				'type'  => Controls_Manager::RAW_HTML,
			)
		);

		$menu_select = array(
			'0' => esc_html__( ' - choose - ', 'reign' ),
		);

		// we also show a list of users menues.
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			$menu_select[ $menu->term_id ] = $menu->name;
		}

		$this->add_control(
			'menu_location',
			array(
				'label'   => esc_html__( 'Choose Menu', 'reign' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $menu_select,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_reign_menu_style',
			array(
				'label' => __( 'Menu Style', 'reign' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'menu_align',
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
				'prefix_class' => 'elementor-align-',
				'selectors'    => array(
					'{{WRAPPER}} .main-navigation ul' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'menu_background',
			array(
				'label'     => __( 'Background', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'menu_background_hover',
			array(
				'label'     => __( 'Background (hover)', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'menu_background_active',
			array(
				'label'     => __( 'Background (active)', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li.current-menu-item a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'font_color',
			array(
				'label'     => __( 'Font Color', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation .reign-menu-toggle, {{WRAPPER}} .main-navigation ul li a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'font_color_hover',
			array(
				'label'     => __( 'Font Color (Hover)', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'font_color_active',
			array(
				'label'     => __( 'Font Color (Active)', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#399dff',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li.current-menu-item a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typography',
				'scheme'   => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .main-navigation ul.primary-menu > li a, .wbesntl-notification-area .user-link, #masthead .wbesntl-notification-area .user-link-wrap .user-link',
			)
		);

		$this->end_controls_section();

		/* Sub Menu Section */
		$this->start_controls_section(
			'section_reign_submenu_style',
			array(
				'label' => __( 'Sub Menu Style', 'reign' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'submenu_align',
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
				'prefix_class' => 'elementor-align-',
				'selectors'    => array(
					'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'submenu_background',
			array(
				'label'     => __( 'Background', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li a' => 'background-color: {{VALUE}} !important;',
				),
			)
		);
		$this->add_control(
			'submenu_background_hover',
			array(
				'label'     => __( 'Background (hover)', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li a:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'submenu_font_color',
			array(
				'label'     => __( 'Font Color', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation .reign-menu-toggle, {{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'submenu_font_color_hover',
			array(
				'label'     => __( 'Font Color (Hover)', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'submenu_font_color_active',
			array(
				'label'     => __( 'Font Color (Active)', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#399dff',
				'selectors' => array(
					'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li.current-menu-item a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'submenu_typography',
				'scheme'   => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .main-navigation ul.primary-menu ul.sub-menu > li a',
			)
		);

		$this->end_controls_section();

		/* Height Management Section */
		$this->start_controls_section(
			'section_reign_menu_height',
			array(
				'label' => __( 'Height Management', 'reign' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'menu_height',
			array(
				'label'   => __( 'Menu Height (px)', 'reign' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 30,
			)
		);

		$this->add_control(
			'submenu_width',
			array(
				'label'   => __( 'Submenu Width', 'reign' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 170,
			)
		);

		$this->end_controls_section();

		do_action( 'reign_wp_submenu_elementor_controls', $this );
	}

	/**
	 * Render our custom menu onto the page.
	 */
	protected function render() {
		$settings = $this->get_settings();

		/* setting default menu in elementor based header */
		if ( empty( $settings['menu_location'] ) ) {
			$menus = wp_get_nav_menus();
			foreach ( $menus as $menu ) {
				$settings['menu_location'] = $menu->term_id;
				break;
			}
		} elseif ( is_numeric( $settings['menu_location'] ) ) {
			$nav_menu = wp_get_nav_menu_object( $settings['menu_location'] );
			if ( ! $nav_menu ) {
				$menus = wp_get_nav_menus();
				foreach ( $menus as $menu ) {
					$settings['menu_location'] = $menu->term_id;
					break;
				}
			}
		}
		/* setting default menu in elementor based header */

		if ( ! empty( $settings['menu_location'] ) ) {

			$menu_height    = isset( $settings['menu_height'] ) ? $settings['menu_height'] : 90;
			$submenu_height = isset( $settings['submenu_height'] ) ? $settings['submenu_height'] : 90;
			$submenu_width  = isset( $settings['submenu_width'] ) ? $settings['submenu_width'] : 170;

			$identifier = time() + rand( 10, 1000 );

			ob_start();
			?>
			<style type="text/css">
				#site-navigation-<?php echo $identifier; ?> .primary-menu > li > a,
				#site-navigation-<?php echo $identifier; ?> .header-right .search-wrap,
				#site-navigation-<?php echo $identifier; ?> .rg-icon-wrap,
				#site-navigation-<?php echo $identifier; ?> .elementor-branding,
				#site-navigation-<?php echo $identifier; ?> .user-link-wrap {
					height: <?php echo $menu_height; ?>px;
					line-height: <?php echo $menu_height; ?>px;
				}
				#site-navigation-<?php echo $identifier; ?> .primary-menu .children, .primary-menu .sub-menu {
					top: <?php echo $menu_height; ?>px;
				}

				@media screen and (min-width:768px) {
					#site-navigation-<?php echo $identifier; ?> ul#primary-menu li > ul.sub-menu {
						width: <?php echo $submenu_width; ?>px !important;
					}
				}

				/*.header-right .rg-search-form-wrap,
				.user-profile-menu,
				.primary-menu .children, .primary-menu .sub-menu,
				.user-profile-menu,
				.user-notifications .rg-dropdown, .user-notifications:hover .rg-dropdown {
					top: <?php // echo $submenu_height; ?>px;
				}*/
			</style>

			<nav id="site-navigation-<?php echo $identifier; ?>" class="main-navigation" role="navigation">
				<span class="menu-toggle wbcom-nav-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span></span>
					<span></span>
					<span></span>
				</span>
				<?php
				if ( is_numeric( $settings['menu_location'] ) ) {
					$nav_menu = wp_get_nav_menu_object( $settings['menu_location'] );
					if ( $nav_menu ) {
						wp_nav_menu(
							array(
								'menu'        => $nav_menu,
								'fallback_cb' => '',
								'container'   => false,
								'menu_class'  => 'primary-menu',
								'menu_id'     => 'primary-menu',
							)
						);
					} else {
						echo 'Menu Configuration Issue';
					}
				} else {
					wp_nav_menu(
						array(
							'theme_location'  => $settings['menu_location'],
							'container'       => 'div',
							'container_class' => 'main-nav',
							'container_id'    => 'primary-menu',
							'menu_class'      => '',
							'items_wrap'      => '<ul id="%1$s" class="%2$s ' . '">%3$s</ul>',
						)
					);
				}

				// if ( 'yes' == $settings[ 'searchbox_enabled' ] ) {
				?>

					<!-- <div class="search-wrap">
						<span class="rg-search-icon rg-header-icon"></span>
						<div class="search-content"> -->
							<?php // get_search_form(); ?>
						<!-- </div>
					</div> -->

				<?php // } ?>
			</nav><!-- #site-navigation -->
			<?php
			echo apply_filters( 'reign_menu_output', ob_get_clean(), $settings['menu_location'], $settings );
		} else {
			$this->content_template();
		}
	}

	/**
	 * This is outputted while rending the page.
	 */
	protected function content_template() {
		?>
		<div class="reign-wp-menu-content-area">
			WordPress Menu Will Appear Here
		</div>
		<?php
	}




}
