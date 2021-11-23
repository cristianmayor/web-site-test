<?php
/**
 * BuddyPress Groups Widget.
 *
 * @package BuddyPress
 * @subpackage GroupsWidgets
 * @since 1.0.0
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Groups widget.
 *
 * @since 1.0.3
 */
class BP_REIGN_Groups_Carousel_Widget extends WP_Widget {

	/**
	 * Working as a group, we get things done better.
	 *
	 * @since 1.0.3
	 */
	public function __construct() {
		$widget_ops = array(
			'description'                 => esc_html__( 'A slider for list of recently active, popular, newest, or alphabetical groups', 'buddypress' ),
			'classname'                   => 'widget_rign_groups_carousel_widget buddypress widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( false, esc_html_x( 'REIGN Groups Carousel', 'widget name', 'buddypress' ), $widget_ops );
	}

	/**
	 * Extends our front-end output method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $args     Array of arguments for the widget.
	 * @param array $instance Widget instance data.
	 */
	public function widget( $args, $instance ) {
		global $groups_template;

		/**
		 * Filters the user ID to use with the widget instance.
		 *
		 * @since BuddyPress 1.5.0
		 *
		 * @param string $value Empty user ID.
		 */
		$user_id = apply_filters( 'bp_group_widget_user_id', '0' );

		if ( isset( $args ) ) {
			extract( $args );
		}

		if ( empty( $instance['group_default'] ) ) {
			$instance['group_default'] = 'popular';
		}

		if ( empty( $instance['title'] ) ) {
			$instance['title'] = __( 'Groups Carousel', 'buddypress' );
		}

		/**
		 * Filters the title of the Groups widget.
		 *
		 * @since 1.8.0
		 * @since 2.3.0 Added 'instance' and 'id_base' to arguments passed to filter.
		 *
		 * @param string $title    The widget title.
		 * @param array  $instance The settings for the particular instance of the widget.
		 * @param string $id_base  Root ID for all widgets of this type.
		 */
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		/**
		 * Filters the separator of the group widget links.
		 *
		 * @since 2.4.0
		 *
		 * @param string $separator Separator string. Default '|'.
		 */
		$separator = apply_filters( 'bp_groups_widget_carousel_separator', '|' );

		echo isset( $before_widget ) ? $before_widget : '';

		$title = ! empty( $instance['title'] ) ? '<a href="' . bp_get_groups_directory_permalink() . '">' . $title . '</a>' : $title;

		$max_groups = ! empty( $instance['max_groups'] ) ? (int) $instance['max_groups'] : 5;

		$group_args = array(
			'user_id'  => $user_id,
			'type'     => $instance['group_default'],
			'per_page' => $max_groups,
			'max'      => $max_groups,
		);

		// Back up the global.
		$old_groups_template = $groups_template;
		?>
		<div id="rg-group-carousel-section" class="rg-group-carousel-section rg-group">
					<div class="rg-group-heading aligncenter rg-heading">
							<?php
							if ( isset( $before_title ) && isset( $after_title ) ) {
									echo $before_title . $title . $after_title;
							} else {
									echo $title;
							}
							?>
					</div>
					<?php if ( bp_has_groups( $group_args ) ) : ?>
							<ul id="groups-carousel-list-widget" class="groups-carousel-container container" aria-live="assertive" aria-atomic="true" aria-relevant="all">
								<?php
								while ( bp_groups() ) :
										bp_the_group();
									?>

									<li <?php bp_group_class(); ?> >
										<div class="bp-group-inner-wrap">
											<?php $this->reign_get_bp_group_cover(); ?>	
												<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
														<a class="item-avatar-group" href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( '' ); ?></a>
												<?php endif; ?>

												<div class="group-content-wrap">
													<div class="item">
															<h3 class="item-title"><?php bp_group_link(); ?></h3>

															<?php
															/**
															 * Fires inside the listing of an individual group listing item.
															 *
															 * @since 1.1.0
															 */
															do_action( 'bp_directory_groups_item' );
															?>
													</div>
												</div>
										</div>
									</li>
								<?php endwhile; ?>
							</ul>
					<?php else : ?>
							<div class="widget-error">
									<?php esc_html_e( 'There are no groups to display.', 'buddypress' ); ?>
							</div>
					<?php endif; ?>
		</div>
		<?php
		echo isset( $after_widget ) ? $after_widget : '';
		// Restore the global.
		$groups_template = $old_groups_template;
	}

	/**
	 * Extends our update method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $new_instance New instance data.
	 * @param array $old_instance Original instance data.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']         = wp_strip_all_tags( $new_instance['title'] );
		$instance['max_groups']    = wp_strip_all_tags( $new_instance['max_groups'] );
		$instance['group_default'] = wp_strip_all_tags( $new_instance['group_default'] );

		return $instance;
	}

	/**
	 * Extends our form method.
	 *
	 * @since 1.0.3
	 *
	 * @param array $instance Current instance.
	 * @return mixed
	 */
	public function form( $instance ) {
		$defaults = array(
			'title'         => esc_html__( 'Groups Carousel', 'buddypress' ),
			'max_groups'    => 4,
			'group_default' => 'active',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title         = wp_strip_all_tags( $instance['title'] );
		$max_groups    = wp_strip_all_tags( $instance['max_groups'] );
		$group_default = wp_strip_all_tags( $instance['group_default'] );
		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'buddypress' ); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 100%" /></label></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'max_groups' ) ); ?>"><?php esc_html_e( 'Max groups to show:', 'buddypress' ); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'max_groups' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max_groups' ) ); ?>" type="text" value="<?php echo esc_attr( $max_groups ); ?>" style="width: 30%" /></label></p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'group_default' ) ); ?>"><?php esc_html_e( 'Default groups to show:', 'buddypress' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'group_default' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'group_default' ) ); ?>">
				<option value="newest" <?php selected( $group_default, 'newest' ); ?>><?php esc_html_e( 'Newest', 'buddypress' ); ?></option>
				<option value="active" <?php selected( $group_default, 'active' ); ?>><?php esc_html_e( 'Active', 'buddypress' ); ?></option>
				<option value="popular"  <?php selected( $group_default, 'popular' ); ?>><?php esc_html_e( 'Popular', 'buddypress' ); ?></option>
				<option value="alphabetical" <?php selected( $group_default, 'alphabetical' ); ?>><?php esc_html_e( 'Alphabetical', 'buddypress' ); ?></option>
			</select>
		</p>

		<?php
	}

	public function reign_get_bp_group_cover() {
		global $wbtm_reign_settings;

		$args = array(
			'object_dir' => 'groups',
			'item_id'    => $group_id = bp_get_group_id(),
			'type'       => 'cover-image',
		);

		$cover_img_url = bp_attachments_get_attachment( 'url', $args );

		if ( empty( $cover_img_url ) ) {
			global $wbtm_reign_settings;
			$cover_img_url = isset( $wbtm_reign_settings['reign_buddyextender']['default_group_cover_image_url'] ) ? $wbtm_reign_settings['reign_buddyextender']['default_group_cover_image_url'] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-cover.jpg';
			if ( empty( $cover_img_url ) ) {
				$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-cover.jpg';
			}
		}
		echo '<div class="wbtm-group-cover-img"><img src="' . $cover_img_url . '" /></div>';
	}

}

/**
 * Register the widget
 */
function reign_register_groups_carousel_widget() {
	if ( bp_is_active( 'groups' ) ) {
		register_widget( 'BP_REIGN_Groups_Carousel_Widget' );
	}
}

add_action( 'bp_widgets_init', 'reign_register_groups_carousel_widget' );
