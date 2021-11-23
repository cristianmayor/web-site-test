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



class NotificationArea extends \Elementor\Widget_Base {
    
        public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'notification-area', WBCOM_ESSENTIAL_ELEMENTOR_URL . 'assets/css/notification-area.css', array(), '3.0.0' );
		// wp_register_style( 'style-handle', 'path/to/file.CSS' );
	}

	public function get_name() {
		return 'wbcom-notification-area';
	}

	public function get_title() {
		return esc_html__( 'Header Notification Area', 'wbcom-essential' );
	}

	public function get_icon() {
		return 'eicon-alert';
	}

	public function get_categories() {
		return array( 'wbcom-elements' );
	}
        
        public function get_style_depends() {
		return array( 'notification-area' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_reign_notification_area',
			array(
				'label' => __( 'Notification Area', 'elementor' ),
			)
		);

		$this->add_control(
			'search_form_enabled',
			array(
				'label'        => __( 'Enable Search Form', 'elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'elementor' ),
				'label_off'    => __( 'No', 'elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		if ( class_exists( 'WooCommerce' ) || class_exists( 'Easy_Digital_Downloads' ) ) {
			$this->add_control(
				'rtm_cart_icon_enabled',
				array(
					'label'        => __( 'Enable Cart Icon', 'elementor' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => __( 'Yes', 'elementor' ),
					'label_off'    => __( 'No', 'elementor' ),
					'return_value' => 'yes',
					'separator'    => 'before',
				)
			);
		}

		if ( class_exists( 'BuddyPress' ) && bp_is_active( 'messages' ) ) {
			$this->add_control(
				'user_message_bell_enabled',
				array(
					'label'        => __( 'Enable User Message Icon', 'elementor' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => __( 'Yes', 'elementor' ),
					'label_off'    => __( 'No', 'elementor' ),
					'return_value' => 'yes',
					'separator'    => 'before',
				)
			);
		}

		if ( class_exists( 'BuddyPress' ) && bp_is_active( 'notifications' ) ) {
			$this->add_control(
				'notification_bell_enabled',
				array(
					'label'        => __( 'Enable Notification Bell Icon', 'elementor' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => __( 'Yes', 'elementor' ),
					'label_off'    => __( 'No', 'elementor' ),
					'return_value' => 'yes',
					'separator'    => 'before',
				)
			);
		}

		$this->add_control(
			'avatar_enabled',
			array(
				'label'        => __( 'Display User Avatar', 'elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'elementor' ),
				'label_off'    => __( 'No', 'elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'reign' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-search-icon.icon-search-interface-symbol, {{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-icon-wrap, {{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-icon-wrap a, {{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-icon-wrap span:before, {{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-search-icon:before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Icon Hover Color', 'reign' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-search-icon.icon-search-interface-symbol:hover, {{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-icon-wrap:hover, {{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-icon-wrap a:hover,
					{{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-icon-wrap span:hover:before, {{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .rg-search-icon:hover:before' => 'color: {{VALUE}};',
				),
			)
		);

				$this->add_control(
					'user_name_font_color',
					array(
						'label'     => __( 'User Name Font Color', 'reign' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#000',
						'selectors' => array(
							'{{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .user-link, #masthead .wbesntl-notification-area .user-link-wrap .user-link' => 'color: {{VALUE}};',
						),
					)
				);

		$this->add_control(
			'user_name_font_color_hover',
			array(
				'label'     => __( 'User Name Font Color (Hover)', 'reign' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .header-right.no-gutter.wb-grid-flex.grid-center .user-link:hover, #masthead .wbesntl-notification-area .user-link-wrap .user-link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'notification_height',
			array(
				'label'     => __( 'Line Height (px)', 'reign' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 90,
				'selectors' => array(
					'{{WRAPPER}} .header-right.wb-grid-flex.wbesntl-notification-area, {{WRAPPER}} .header-right.wb-grid-flex.wbesntl-notification-area .search-wrap, {{WRAPPER}} .header-right.wb-grid-flex.wbesntl-notification-area .woo-cart-wrap, {{WRAPPER}} .header-right.wb-grid-flex.wbesntl-notification-area .rg-icon-wrap, {{WRAPPER}} .header-right.wb-grid-flex.wbesntl-notification-area .woo-cart-wrap, {{WRAPPER}} .header-right.wb-grid-flex.wbesntl-notification-area .user-notifications, {{WRAPPER}} .header-right.wb-grid-flex.wbesntl-notification-area .user-link-wrap' => 'line-height: {{VALUE}}px;height: {{VALUE}}px;',
				),
			)
		);

		$this->add_control(
			'counter_top',
			array(
				'label'     => __( 'Counter Top Space (px)', 'reign' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 20,
				'selectors' => array(
					'{{WRAPPER}} .header-right.wb-grid-flex.wbesntl-notification-area .rg-count' => 'top: {{VALUE}}px;',
				),
			)
		);

		$this->end_controls_section();

		do_action( 'reign_wp_menu_elementor_controls', $this );
	}

	/**
	 * Render our custom menu onto the page.
	 */
	protected function render() {
		$settings = $this->get_settings();
		if ( ! isset( $settings['user_message_bell_enabled'] ) ) {
			$settings['user_message_bell_enabled'] = 'no';
		}
		if ( ! isset( $settings['notification_bell_enabled'] ) ) {
			$settings['notification_bell_enabled'] = 'no';
		}
		if ( ! isset( $settings['rtm_cart_icon_enabled'] ) ) {
			$settings['rtm_cart_icon_enabled'] = 'no';
		}
		if ( ! isset( $settings['avatar_enabled'] ) ) {
			$settings['avatar_enabled'] = 'no';
		}

		$notification_height = isset( $settings['notification_height'] ) ? $settings['notification_height'] : 90;

		ob_start();
		?>

		<style type="text/css">
			.header-right.wb-grid-flex.wbesntl-notification-area,
			.header-right.wb-grid-flex.wbesntl-notification-area .search-wrap,
			.header-right.wb-grid-flex.wbesntl-notification-area .woo-cart-wrap,
			.header-right.wb-grid-flex.wbesntl-notification-area .rg-icon-wrap,
			.header-right.wb-grid-flex.wbesntl-notification-area .woo-cart-wrap,
			.header-right.wb-grid-flex.wbesntl-notification-area .user-notifications,
			.header-right.wb-grid-flex.wbesntl-notification-area .user-link-wrap {
				min-height: auto;
			}

			.header-right.wb-grid-flex.wbesntl-notification-area .user-link-wrap .user-profile-menu,
			.header-right.wb-grid-flex.wbesntl-notification-area .rg-header-submenu.rg-dropdown {
				top: <?php echo $notification_height + 5; ?>px;
			}
		</style>

		<div class="header-right no-gutter wb-grid-flex grid-center wbesntl-notification-area">
			<?php
			if ( 'yes' == $settings['search_form_enabled'] ) {
				?>
				<div class="search-wrap rg-icon-wrap">
					<span class="rg-search-icon far fa-search"></span>
					<div class="rg-search-form-wrap">
						<span class="rg-search-close far fa-times-circle"></span>
						<?php get_search_form(); ?>
					</div>
				</div>
				<?php
			}
			?>

			<?php
			if ( 'yes' == $settings['rtm_cart_icon_enabled'] ) {
				if ( function_exists( 'my_wc_cart_count' ) ) {
					my_wc_cart_count();
				}
			}
			?>

			<?php
			if ( is_user_logged_in() ) {

				if ( 'yes' == $settings['user_message_bell_enabled'] ) {
					// get_template_part( 'template-parts/user-messages' );

					if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'messages' ) ) {
						?>
						<div class="rg-msg">
							<a class="rg-icon-wrap" href="<?php echo bp_loggedin_user_domain() . bp_get_messages_slug(); ?>">
								<span class="far fa-envelope"></span>
								<?php
								if ( function_exists( 'bp_total_unread_messages_count' ) ) {
									$count = bp_get_total_unread_messages_count();

									if ( $count > 0 ) {
										?>
										<span class="rg-count"><?php bp_total_unread_messages_count(); ?></span>
																									   <?php
									} else {
										echo '<span class="rg-count">0</span>';
									}
								}
								?>
							</a>
						</div>
						<?php
					}
				}

				if ( 'yes' == $settings['notification_bell_enabled'] ) {
					// get_template_part( 'template-parts/user-notifications' );

					if ( class_exists( 'BuddyPress' ) && is_user_logged_in() && bp_is_active( 'notifications' ) ) {
						global $bp;
						?>
						<div class="user-notifications">
							<a class="rg-icon-wrap" href="<?php echo esc_url( bp_loggedin_user_domain() . $bp->notifications->slug ); ?>" title="<?php _e( esc_attr( 'Notifications' ), 'reign' ); ?>">
								<span class="far fa-bell"></span>
								<?php
								if ( function_exists( 'bp_notifications_get_unread_notification_count' ) ) {
									$count = bp_notifications_get_unread_notification_count( get_current_user_id() );

									// if ( $count > 0 ) {
									?>
									<span class="rg-count"> <?php echo esc_html( $count ); ?></span>
																	   <?php
																		// }
								}
								?>
							</a>
							<?php
							$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id() );
							if ( $notifications ) {
								?>
								<ul id="rg-notify" class="rg-header-submenu rg-dropdown">
									<?php
									rsort( $notifications );
									foreach ( $notifications as $notification ) {
										?>
										<li><?php echo $notification; ?></li>
													   <?php
									}
									?>
									<li class="rg-view-all">
										<a href="<?php echo esc_url( bp_loggedin_user_domain() . $bp->notifications->slug ); ?>"><?php _e( 'View all notifications', 'reign' ); ?></a>
									</li>
								</ul>
								<?php
							}
							?>
						</div>
						<?php
					}
				}

				$current_user = wp_get_current_user();

				if ( $current_user || ( $current_user instanceof WP_User ) ) {
					$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
					echo '<div class="user-link-wrap">';
					echo '<a class="user-link" href="' . $user_link . '">';
					?>
					<?php if ( 'yes' == $settings['avatar_enabled'] ) { ?>
						<span class="rg-user"><?php echo $current_user->display_name; ?></span>
						<?php
						echo get_avatar( $current_user->user_email, 200 );
					}
					echo '</a>';
					wp_nav_menu(
						array(
							'theme_location' => 'menu-2',
							'menu_id'        => 'user-profile-menu',
							'fallback_cb'    => '',
							'container'      => false,
							'menu_class'     => 'user-profile-menu',
						)
					);
					echo '</div>';
				}
			} else {
				$wbcom_ele_login_url    = apply_filters( 'wbcom_ele_notification_login_url', wp_login_url() );
				$wbcom_ele_register_url = apply_filters( 'wbcom_ele_notification_registration_url', wp_registration_url() );
				?>
				<div class="rg-icon-wrap">
					<a href="<?php echo $wbcom_ele_login_url; ?>" class="btn-login" title="Login">	<span class="far fa-sign-in-alt"></span>
					</a>
				</div>
				<?php
				if ( get_option( 'users_can_register' ) ) {
					?>
					<span class="sep">|</span>
					<div class="rg-icon-wrap">
						<a href="<?php echo $wbcom_ele_register_url; ?>" class="btn-register" title="Register">
							<span class="far fa-address-book"></span>
						</a>
					</div>

					<?php
				}
			}
			?>
		</div>

		<?php
		echo apply_filters( 'reign_notification_area_output', ob_get_clean(), $settings['notification_bell_enabled'], $settings['notification_bell_enabled'], $settings );
	}

}
