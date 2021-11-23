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
use Elementor\Group_Control_Typography;

class GroupGrid extends \Elementor\Widget_Base {
    
        public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'group-grid', WBCOM_ESSENTIAL_ELEMENTOR_URL . 'assets/css/group-grid.css', array(), '3.0.0' );
		// wp_register_style( 'style-handle', 'path/to/file.CSS' );
	}

	public function get_name() {
		return 'wbcom-groups-grid';
	}

	public function get_title() {
		return esc_html__( 'Groups Grid', 'wbcom-essential' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return array( 'wbcom-elements' );
	}
        
        public function get_style_depends() {
		return array( 'group-grid' );
	}

	protected function _register_controls() {

		do_action( 'wbcom_essential/widget/groups-grid/settings', $this );

		$this->start_controls_section(
			'section_groups_grid',
			array(
				'label' => __( 'Settings', 'wbcom-essential' ),
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
					'newest'       => esc_html__( 'Newest', 'wbcom-essential' ),
					'active'       => esc_html__( 'Most Active', 'wbcom-essential' ),
					'popular'      => esc_html__( 'Most Popular', 'wbcom-essential' ),
					'random'       => esc_html__( 'Random', 'wbcom-essential' ),
					'alphabetical' => esc_html__( 'Alphabetical', 'wbcom-essential' ),
				),
			)
		);

		$this->add_control(
			'total',
			array(
				'label'       => esc_html__( 'Total groups', 'wbcom-essential' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '12',
				'placeholder' => esc_html__( 'Total groups', 'wbcom-essential' ),
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => esc_html__( 'Columns', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'three',
				'options' => array(
					'three' => '3',
					'four'  => '4',
				),
			)
		);

		if ( _is_theme_active( 'REIGN' ) ) {

			$this->add_control(
				'rg-grp-grid-layout',
				array(
					'label'   => esc_html__( 'Layout', 'wbcom-essential' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'wbtm-group-directory-type-2',
					'options' => array(
						'wbtm-group-directory-type-1' => 'Layout 1',
						'wbtm-group-directory-type-2' => 'Layout 2',
						'wbtm-group-directory-type-3' => 'Layout 3',
						'wbtm-group-directory-type-4' => 'Layout 4',
					),
				)
			);
		}

		$this->end_controls_section();
	}

	protected function render() {
		parent::render();
		$settings             = $this->get_settings_for_display();
		$active_template      = get_option( '_bp_theme_package_id' );
		$group_directory_type = isset( $settings['rg-grp-grid-layout'] ) ? $settings['rg-grp-grid-layout'] : '';
		$addition_class       = $img_class = '';

		if ( $group_directory_type != 'wbtm-group-directory-type-1' ) {
			$addition_class = 'lg-wb-grid-1-' . $settings['columns'];
		}
		if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
			$img_class = 'img-card';
		}

		$query_string = '&type=' . $settings['type'] . '&per_page=' . $settings['total'] . '&max=' . $settings['total'];

		$current_component = static function () {
			return 'groups';
		};

		// add_filter( 'wbcom_essential/has_template/pre', '__return_true' );

		add_filter( 'bp_current_component', $current_component );

		apply_filters( 'wbcom_essential/groups-loop/before/template', $settings );

		add_filter( 'bp_get_groups_pagination_count', '__return_zero' );
		add_filter( 'bp_get_groups_pagination_links', '__return_zero' );

		?>

		<div id="buddypress" class="buddypress-wrap wbcom-groups-grid-widget">
		<?php if ( 'legacy' === $active_template ) : ?>
			<?php
			wbcom_essential_get_template(
				'groups/groups-loop.php',
				array(
					'query_string'         => $query_string,
					'column'               => $settings['columns'],
					'addition_class'       => $addition_class,
					'group_directory_type' => $group_directory_type,
				),
				'reign/buddypress/legacy'
			);
			?>
	<?php elseif ( 'nouveau' === $active_template ) : ?>
		<?php
		if ( _is_theme_active( 'BuddyX' ) ) {

			$loop_classes = static function () use ( $settings ) {
				return array(
					'item-list',
					'groups-list',
					'bp-list',
					'grid',
					_get_column_class( $settings['columns'] ),
					_get_column_class( $settings['columns'], 'tablet' ),
					_get_column_class( $settings['columns'], 'mobile' ),
				);
			};

			add_filter( 'bp_nouveau_get_loop_classes', $loop_classes );

			wbcom_essential_get_template(
				'groups/groups-loop.php',
				array(
					'query_string' => $query_string,
				),
				'buddyx/buddypress'
			);

			remove_filter( 'bp_nouveau_get_loop_classes', $loop_classes );

		} elseif ( _is_theme_active( 'REIGN' ) ) {
			$col_class = isset( $settings['columns'] ) ? $settings['columns'] : 'three';

			wbcom_essential_get_template(
				'groups/groups-loop.php',
				array(
					'query_string'         => $query_string,
					'column_class'         => $col_class,
					'addition_class'       => $addition_class,
					'group_directory_type' => $group_directory_type,
					'img_class'            => $img_class,
				),
				'reign/buddypress/nouveau'
			);
		} else {
			$loop_classes = static function () use ( $settings ) {
				return array(
					'item-list',
					'groups-list',
					'bp-list',
					'grid',
					_get_column_class( $settings['columns'] ),
					_get_column_class( $settings['columns'], 'tablet' ),
					_get_column_class( $settings['columns'], 'mobile' ),
				);
			};

			add_filter( 'bp_nouveau_get_loop_classes', $loop_classes );

			wbcom_essential_get_template(
				'groups/groups-loop.php',
				array(
					'query_string' => $query_string,
				),
				'buddypress'
			);

			remove_filter( 'bp_nouveau_get_loop_classes', $loop_classes );

		}

		?>
	  <?php endif; ?>
		</div>

			<?php
			remove_filter( 'bp_current_component', $current_component );

			apply_filters( 'wbcom_essential/groups-loop/after/template', $settings );

			remove_filter( 'bp_get_groups_pagination_count', '__return_zero' );
			remove_filter( 'bp_get_groups_pagination_links', '__return_zero' );
			// remove_filter( 'wbcom_essential/has_template/pre', '__return_true' );
	}

}
