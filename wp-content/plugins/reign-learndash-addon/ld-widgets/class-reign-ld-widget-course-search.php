<?php
/**
 * Widget API: Reign_LD_Widget_Course_Search class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */
/**
 * Core class used to implement a Search widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
if ( ! class_exists( 'Reign_LD_Widget_Course_Search' ) ) :
	class Reign_LD_Widget_Course_Search extends WP_Widget {
		/**
		 * Sets up a new Search widget instance.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'                   => 'widget_search widget_reign_ld_course_search',
				'description'                 => __( 'A search form for learndash courses for your site.', 'reign-learndash-addon' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'reign_ld_course_search', _x( 'Reign- LD Course Search', 'Search widget', 'reign-learndash-addon' ), $widget_ops );
		}
		/**
		 * Outputs the content for the current Search widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Search widget instance.
		 */
		public function widget( $args, $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}?>
			<div class="courses-searching">
				<?php
				// Use current theme search form if it exists
				get_reign_ld_course_search_form();
				?>
			</div>
			<?php
			// $this->get_course_search_form();
			echo $args['after_widget'];
		}
		/**
		 * Outputs the settings form for the Search widget.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title    = $instance['title'];
			?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'reign-learndash-addon' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
			<?php
		}
		/**
		 * Handles updating settings for the current Search widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 * @return array Updated settings.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance          = $old_instance;
			$new_instance      = wp_parse_args( (array) $new_instance, array( 'title' => '' ) );
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			return $instance;
		}
	}
endif;
