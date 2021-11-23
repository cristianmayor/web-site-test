<?php
/**
 * Widget API: Reign_LD_Widget_Course_Categories class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */
/**
 * Core class used to implement a Categories widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
if ( ! class_exists( 'Reign_LD_Widget_Course_Categories' ) ) :
	class Reign_LD_Widget_Course_Categories extends WP_Widget {

		protected $post_type = 'sfwd-courses';
		protected $post_name = 'Course';
		/**
		 * Sets up a new Categories widget instance.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->post_name = LearnDash_Custom_Label::get_label( 'course' );
			$widget_ops      = array(
				'classname'                   => 'widget_reign_ld_course_categories',
				'description'                 => sprintf( esc_html_x( 'A list of learndash %s categories.', 'LD course categories label', 'reign-learndash-addon' ), $this->post_name ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'reign_ld_course_categories', sprintf( esc_html__( 'Reign - LD %s categories.', 'reign-learndash-addon' ), $this->post_name ), $widget_ops );
		}

		/**
		 * Outputs the content for the current Categories widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the current Categories widget instance.
		 */
		public function widget( $args, $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : sprintf( esc_html__( '%s categories', 'reign-learndash-addon' ), $this->post_name );
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			$c     = ! empty( $instance['count'] ) ? '1' : '0';
			$h     = ! empty( $instance['hierarchical'] ) ? '1' : '0';
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			$cat_args = array(
				'orderby'      => 'name',
				'show_count'   => $c,
				'hierarchical' => $h,
			);
			/* setting argument to fetch learndash course category list */
			$cat_args['taxonomy'] = 'ld_course_category';
			?>
		<ul>
			<?php
			$cat_args['title_li'] = '';
			/**
			 * Filters the arguments for the Categories widget.
			 *
			 * @since 1.0.0
			 * @since 4.9.0 Added the `$instance` parameter.
			 *
			 * @param array $cat_args An array of Categories widget options.
			 * @param array $instance Array of settings for the current widget.
			 */
			wp_list_categories( apply_filters( 'widget_ld_course_categories_args', $cat_args, $instance ) );
			?>
		</ul>
			<?php
			echo $args['after_widget'];
		}

		/**
		 * Handles updating settings for the current Categories widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            WP_Widget::form().
		 * @param array $old_instance Old settings for this instance.
		 * @return array Updated settings to save.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance                 = $old_instance;
			$instance['title']        = sanitize_text_field( $new_instance['title'] );
			$instance['count']        = ! empty( $new_instance['count'] ) ? 1 : 0;
			$instance['hierarchical'] = ! empty( $new_instance['hierarchical'] ) ? 1 : 0;
			return $instance;
		}

		/**
		 * Outputs the settings form for the Categories widget.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		public function form( $instance ) {
			// Defaults
			$instance     = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title        = sanitize_text_field( $instance['title'] );
			$count        = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
			$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
			?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'reign-learndash-addon' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show post counts', 'reign-learndash-addon' ); ?></label><br />
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>"><?php _e( 'Show hierarchy', 'reign-learndash-addon' ); ?></label></p>
			<?php
		}
	}
endif;
