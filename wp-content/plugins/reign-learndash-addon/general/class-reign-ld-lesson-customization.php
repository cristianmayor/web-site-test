<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Reign_LD_Lesson_Customization' ) ) :

/**
 * @class Reign_LD_Lesson_Customization
 */
class Reign_LD_Lesson_Customization {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Reign_LD_Lesson_Customization
	 */
	protected static $_instance = null;
	
	/**
	 * Main Reign_LD_Lesson_Customization Instance.
	 *
	 * Ensures only one instance of Reign_LD_Lesson_Customization is loaded or can be loaded.
	 *
	 * @return Reign_LD_Lesson_Customization - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Reign_LD_Lesson_Customization Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'reign_render_additional_post_meta', array( $this, 'render_tags_n_cats' ) );

		// add_action( 'reign_extra_info_on_single_post_start', array( $this, 'render_distraction_free_button' ) );
		// add_action( 'wp_footer', array( $this, 'add_distraction_free_reading_section' ) );
	}

	// public function render_distraction_free_button() {
	// 	global $post;
	// 	if( ( 'sfwd-lessons' === $post->post_type ) || ( 'sfwd-topic' === $post->post_type ) || ( 'sfwd-quiz' === $post->post_type ) ) {
	// 		$html = '<button class="button lm-distraction-free-toggle"><i class="fa fa-expand"></i></button>';
	// 		echo $html;
	// 	}
	// }

	public function add_distraction_free_reading_section() {
		global $post;
		if( ( 'sfwd-lessons' === $post->post_type ) || ( 'sfwd-topic' === $post->post_type ) || ( 'sfwd-quiz' === $post->post_type ) ) {
			$distraction_free_reading = isset( $_COOKIE['learnmate_distraction_free_reading'] ) ? $_COOKIE['learnmate_distraction_free_reading'] : 'disable';
			$custom_css = '';
			if( 'disable' === $distraction_free_reading ) {
				$custom_css = 'display: none;';
			}
			ob_start();
			?>
			<div class="lm-distraction-free-reading" style="<?php echo $custom_css; ?>">
				<div class="lm-board-popup-header">
					<div class="lm-board-mav-toggle">
						<i class="fa fa-bars"></i>
					</div>
					<div class="lm-board-popup-searchicon">
						<i class="fa fa-search"></i>
					</div>
					<div class="lm-board-popup-searchform">
						<?php get_search_form(); ?>
					</div>
					<div class="lm-board-popup-close">
						<i class="fa fa-close"></i>
					</div>
				</div>
				<div class="lm-board-popup-content-section">
					<div class="lm-board-popup-sidebar">
						<div class="lm-board-sidebar-sticky">
							<?php
							$ld_course_progress = learndash_course_progress( array( 'course_id' => '75', 'array' => true ) );
							?>
							<div class="lm-board-popup-course-progress-bar">
								<div class="lm-value">
									<?php
									echo do_shortcode( '[learndash_course_progress]' );
									?>
								</div>
								<label><?php echo $ld_course_progress['percentage']; ?>%</label>
							</div>
						</div>
						

						<?php //learnmate_get_template( 'ld-template-parts/search-by-lesson.php' ); ?>


						<!-- <div class="lm-breadcrumbs-wrapper lm-board-popup-breadcrumb">
							<?php custom_breadcrumbs(); ?>
						</div> -->
						<?php learnmate_get_template( 'ld-template-parts/course-curriculum.php' ); ?>
					</div>
					<div class="lm-board-popup-main">
						<div class="lm-board-main-bar">
							<div class="lm-board-expand">
								<i class="fa fa-facebook"></i>
							</div>
							<div class="lm-board-title">
								<?php the_title(); ?>
							</div>
						</div>
						<div class="lm-board-main-content">
							<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			echo $html = ob_get_clean();
		}
	}

	public function render_tags_n_cats() {
		global $post;
		if( ( 'sfwd-lessons' === $post->post_type ) ) {
			$categories_list  = get_the_term_list( get_the_ID(), 'ld_lesson_category', $before		 = '', $sep		 = ', ', $after		 = '' );
			if ( $categories_list ) {
				$categories_list = '<span class="cat-links"><i class="rg-category"></i>' . $categories_list . '</span>';
			}
			echo apply_filters( 'reign_ld_lesson_category_meta', $categories_list );

			/* list of tags assigned to post */
			$tags_list	 = get_the_term_list( get_the_ID(), 'ld_lesson_tag', $before		 = '', $sep		 = ', ', $after		 = '' );
			if ( $tags_list ) {
				$tags_list = '<span class="tag-links"><i class="rg-tag"></i>' . $tags_list . '</span>';
			}
			echo apply_filters( 'reign_ld_lesson_tag_meta', $tags_list );
		}
	}

}

endif;

/**
 * Main instance of Reign_LD_Lesson_Customization.
 * @return Reign_LD_Lesson_Customization
 */
Reign_LD_Lesson_Customization::instance();