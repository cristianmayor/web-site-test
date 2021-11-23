<?php

namespace WBCOM_ESSENTIAL\ELEMENTOR\Widgets\Buddypress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use WBCOM_ESSENTIAL\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;


class MemeberCarousel extends \Elementor\Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'member-carousel', WBCOM_ESSENTIAL_ELEMENTOR_URL . 'assets/css/member-carousel.css', array(), '3.0.0' );
		wp_register_script( 'member-carousel', WBCOM_ESSENTIAL_ELEMENTOR_URL . 'assets/js/member-carousel.js', array( 'elementor-frontend' ), '3.0.0', true );
		// wp_register_style( 'style-handle', 'path/to/file.CSS' );
	}

	public function get_name() {
		return 'wbcom-members-carousel';
	}

	public function get_title() {
		return esc_html__( 'Members Carousel', 'stax-buddy-builder' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_script_depends() {
		return array( 'member-carousel' );
	}
        
	public function get_style_depends() {
		return array( 'member-carousel' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 3.3.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'member', 'members', 'carousel', 'slider' );
	}

	public function get_categories() {
		return array( 'wbcom-elements' );
	}

	/**
	 * Register members carousel widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		do_action( 'wbcom_essential/widget/members-listing/settings', $this );

                        $this->start_controls_section(
                                'section_members_carousel',
                                array(
                                        'label' => __( 'Settings', 'stax-buddy-builder' ),
                                        'tab'   => Controls_Manager::TAB_CONTENT,
                                )
                        );

			$this->add_control(
				'type',
				array(
					'label'   => esc_html__( 'Sort', 'wbcom-essential' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'newest',
					'options' => array(
						'newest'  => esc_html__( 'Newest', 'wbcom-essential' ),
						'active'  => esc_html__( 'Most Active', 'wbcom-essential' ),
						'popular' => esc_html__( 'Most Popular', 'wbcom-essential' ),
					),
				)
			);

			$this->add_control(
				'total',
				array(
					'label'       => __( 'Total members', 'wbcom-essential' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => '12',
					'placeholder' => __( 'Total members', 'wbcom-essential' ),
				)
			);

			// $this->add_control(
			// 'scroll',
			// array(
			// 'label'       => __( 'Members to scroll', 'wbcom-essential' ),
			// 'type'        => Controls_Manager::NUMBER,
			// 'default'     => 2,
			// 'placeholder' => '',
			// )
			// );

		$this->end_controls_section();

		$this->start_controls_section(
			'members_carousel_additional_options',
			array(
				'label' => __( 'Additional Options', 'wbcom-essential' ),
			)
		);

		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'              => __( 'Members to Show', 'wbcom-essential' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'' => __( 'Default', 'wbcom-essential' ),
				) + $slides_to_show,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'              => __( 'Members to Scroll', 'wbcom-essential' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => __( 'Set how many slides are scrolled per swipe.', 'wbcom-essential' ),
				'options'            => array(
					'' => __( 'Default', 'wbcom-essential' ),
				) + $slides_to_show,
				'condition'          => array(
					'slides_to_show!' => '1',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'              => __( 'Navigation', 'wbcom-essential' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'both',
				'options'            => array(
					'both'   => __( 'Arrows and Dots', 'wbcom-essential' ),
					'arrows' => __( 'Arrows', 'wbcom-essential' ),
					'dots'   => __( 'Dots', 'wbcom-essential' ),
					'none'   => __( 'None', 'wbcom-essential' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => __( 'Autoplay', 'wbcom-essential' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'wbcom-essential' ),
					'no'  => __( 'No', 'wbcom-essential' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => __( 'Pause on Hover', 'wbcom-essential' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'wbcom-essential' ),
					'no'  => __( 'No', 'wbcom-essential' ),
				),
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'              => __( 'Pause on Interaction', 'wbcom-essential' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'wbcom-essential' ),
					'no'  => __( 'No', 'wbcom-essential' ),
				),
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'wbcom-essential' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'infinite',
			array(
				'label'              => __( 'Infinite Loop', 'wbcom-essential' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'wbcom-essential' ),
					'no'  => __( 'No', 'wbcom-essential' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'              => __( 'Effect', 'wbcom-essential' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => array(
					'slide' => __( 'Slide', 'wbcom-essential' ),
					'fade'  => __( 'Fade', 'wbcom-essential' ),
				),
				'condition'          => array(
					'slides_to_show' => '1',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => __( 'Animation Speed', 'wbcom-essential' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'   => __( 'Direction', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => array(
					'ltr' => __( 'Left', 'wbcom-essential' ),
					'rtl' => __( 'Right', 'wbcom-essential' ),
				),
			)
		);

			$this->end_controls_section();

		do_action( 'reign_wp_menu_elementor_controls', $this );

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$current_component = static function () {
			return 'members';
		};
		
		if ( isset($settings['slides_to_show']) && $settings['slides_to_show'] !='' ) {
			$swiper_options['slides_to_show'] = $settings['slides_to_show'];
		}
		if ( isset($settings['slides_to_show_tablet']) && $settings['slides_to_show_tablet'] !='' ) {
			$swiper_options['slides_to_show_tablet'] = $settings['slides_to_show_tablet'];
		}
		if ( isset($settings['slides_to_show_mobile']) && $settings['slides_to_show_mobile'] !='' ) {
			$swiper_options['slides_to_show_mobile'] = $settings['slides_to_show_mobile'];
		}
		if ( isset($settings['slides_to_scroll']) && $settings['slides_to_scroll'] !='' ) {
			$swiper_options['slides_to_scroll']	= $settings['slides_to_scroll'];
		}
		if ( isset($settings['slides_to_scroll_tablet']) && $settings['slides_to_scroll_tablet'] !='' ) {
			$swiper_options['slides_to_scroll_tablet'] = $settings['slides_to_scroll_tablet'];
		}
		if ( isset($settings['slides_to_scroll_mobile']) && $settings['slides_to_scroll_mobile'] !='' ) {
			$swiper_options['slides_to_scroll_mobile'] = $settings['slides_to_scroll_mobile'];
		}
		if ( isset($settings['navigation']) && $settings['navigation'] !='' ) {
			$swiper_options['navigation'] = $settings['navigation'];
		}
		if ( isset($settings['autoplay_speed']) && $settings['autoplay_speed'] !='' ) {
			$swiper_options['autoplay_speed'] = $settings['autoplay_speed'];
		}
		if ( isset($settings['autoplay']) && $settings['autoplay'] !='' ) {
			$swiper_options['autoplay'] = $settings['autoplay'];
		}
		if ( isset($settings['pause_on_hover']) && $settings['pause_on_hover'] !='' ) {
			$swiper_options['pause_on_hover'] = $settings['pause_on_hover'];
		}
		if ( isset($settings['pause_on_interaction']) && $settings['pause_on_interaction'] !='' ) {
			$swiper_options['pause_on_interaction'] = $settings['pause_on_interaction'];
		}
		if ( isset($settings['infinite']) && $settings['infinite'] !='' ) {
			$swiper_options['infinite'] = $settings['infinite'];
		}
		if ( isset($settings['speed']) && $settings['speed'] !='' ) {
			$swiper_options['speed'] = $settings['speed'];
		}

		$this->add_render_attribute(
			array(
				'carousel'         => array(
					'class' => 'elementor-member-carousel swiper-wrapper',
				),
				'carousel-wrapper' => array(
					'class' => 'member-carousel-container swiper-container',
					'dir'   => $settings['direction'],
					'data-settings' => wp_json_encode($swiper_options)
				),
			)
		);

		$query_string = '&type=' . $settings['type'] . '&per_page=' . $settings['total'] . '&max=' . $settings['total'];
		$slides_count = isset( $settings['total'] ) ? $settings['total'] : 0;
		$show_dots    = ( in_array( $settings['navigation'], array( 'dots', 'both' ) ) );
		$show_arrows  = ( in_array( $settings['navigation'], array( 'arrows', 'both' ) ) );
		?>
		<div <?php echo $this->get_render_attribute_string( 'carousel-wrapper' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'carousel' ); ?>>
					<?php
					if ( bp_has_members( bp_ajax_querystring( 'members' ) . $query_string ) ) {
						while ( bp_members() ) {
							bp_the_member();
							?>
							<div class="swiper-slide">
								<div <?php bp_member_class(); ?>>
									<div class="item-container">
										<div class="item-avatar">
                                                                                    <figure class="swiper-slide-inner">
                                                                                        <a class="member-avatar" href="<?php bp_member_permalink(); ?>">
                                                                                                <?php bp_member_avatar( array( 'width' => '150', 'height' => '150' , 'class' => 'swiper-slide-image' ) ); ?>
                                                                                        </a>
                                                                                    </figure>
										</div>
										<div class="item-card">
											<div class="item">
												<div class="item-meta">
													<h5 class="item-title">
														<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
													</h5>
                                                                                                        <span class="last-activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => false ) ) ); ?>">
														<?php bp_member_last_active(); ?>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $show_dots ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
					<?php if ( $show_arrows ) : ?>
					<div class="elementor-swiper-button elementor-swiper-button-prev">
						<i class="eicon-chevron-left" aria-hidden="true"></i>
						<span class="elementor-screen-only"><?php _e( 'Previous', 'elementor' ); ?></span>
					</div>
					<div class="elementor-swiper-button elementor-swiper-button-next">
						<i class="eicon-chevron-right" aria-hidden="true"></i>
						<span class="elementor-screen-only"><?php _e( 'Next', 'elementor' ); ?></span>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
		// remove_filter( 'bp_nouveau_get_loop_classes', $loop_classes );
		// remove_filter( 'bp_current_component', $current_component );
	}

}
