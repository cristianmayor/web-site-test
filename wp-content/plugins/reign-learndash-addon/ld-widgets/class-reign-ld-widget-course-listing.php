<?php
/**
 * Widget API: Reign_LD_Widget_Course_Listing class
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
if ( ! class_exists( 'Reign_LD_Widget_Course_Listing' ) ) :
	class Reign_LD_Widget_Course_Listing extends WP_Widget {
		/**
		 * Sets up a new Search widget instance.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'                   => 'widget_reign_ld_course_listing',
				'description'                 => sprintf( esc_html_x( 'A list of learndash %s.', 'LD list courses label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'courses' ) ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'reign_ld_course_listing', sprintf( esc_html_x( 'Reign - LD %s Listing', 'LD list course listing label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'course' ) ), $widget_ops );
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
			$title          = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$posts_per_page = ! empty( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : 5;
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			$course_args = array(
				'posts_per_page' => $posts_per_page,
				'post_type'      => 'sfwd-courses',
				'post_status'    => 'publish',
			);
			$course_args = apply_filters( 'wb_ld_widget_filter_course_args', $course_args );
			$ld_courses  = get_posts( $course_args );
			?>
		<div class="reign-ld-course-listing">
			<?php foreach ( $ld_courses as $course ) : ?>
				<div class='reign-ld-course-info ld-course-info-my-courses'>
					<div class="reign-ld-course-thumbnail">
						<a href="<?php echo get_permalink( $course->ID ); ?>"  rel="bookmark">
							<?php
								$thumbnail_html = get_the_post_thumbnail( $course->ID, array( 80, 80 ) );
							if ( empty( $thumbnail_html ) ) {
								$thumbnail_html = get_reign_ld_default_course_img_html();
							}
								echo $thumbnail_html;
							?>
								<div class="thumb-overlay"></div>
								<div class="thumb-overlay-cross"></div>
						</a>
					</div>
					<div class="reign-ld-course-info-details">
						<?php echo '<h2 class="ld-entry-title entry-title"><a href="' . get_permalink( $course->ID ) . '"  rel="bookmark">' . get_the_title( $course->ID ) . '</a></h2>'; ?>
							<?php do_action( 'reign_ld_show_course_price', $course->ID, $ld_courses ); ?>
						<span class="course-meta lm-course-price"><?php echo get_reign_ld_course_price( $course->ID ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
			<?php
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
			$instance       = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title          = $instance['title'];
			$posts_per_page = isset( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : 5;
			?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'reign-learndash-addon' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e( 'Number of courses to show:', 'reign-learndash-addon' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="number" value="<?php echo esc_attr( $posts_per_page ); ?>" /></label></p>
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
			$instance                   = $old_instance;
			$new_instance               = wp_parse_args( (array) $new_instance, array( 'title' => '' ) );
			$instance['title']          = sanitize_text_field( $new_instance['title'] );
			$instance['posts_per_page'] = sanitize_text_field( $new_instance['posts_per_page'] );
			return $instance;
		}
	}
endif;
