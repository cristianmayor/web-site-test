<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Reign_LD_Course_Tabs_Data' ) ) :
	/**
	 * @class Reign_LD_Course_Tabs_Data
	 */
	class Reign_LD_Course_Tabs_Data {
		/**
		 * The single instance of the class.
		 *
		 * @var Reign_LD_Course_Tabs_Data
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_LD_Course_Tabs_Data Instance.
		 *
		 * Ensures only one instance of Reign_LD_Course_Tabs_Data is loaded or can be loaded.
		 *
		 * @return Reign_LD_Course_Tabs_Data - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_LD_Course_Tabs_Data Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'learnmate_render_course_tab_data_for_description', array( $this, 'learnmate_render_course_tab_data_for_description' ) );
			add_action( 'learnmate_render_course_tab_data_for_curriculum', array( $this, 'learnmate_render_course_tab_data_for_curriculum' ) );
			add_action( 'learnmate_render_course_tab_data_for_instructors', array( $this, 'learnmate_render_course_tab_data_for_instructors' ) );
			add_action( 'learnmate_render_course_tab_data_for_review', array( $this, 'learnmate_render_course_tab_data_for_review' ) );
		}

		public function learnmate_render_course_tab_data_for_description() {
			$course_info = array();
			$course_id   = learndash_get_course_id( get_the_ID() );
			$course      = get_post( $course_id );

			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();
			} else {
				$user_id = 0;
			}

			$lessons                = learndash_get_course_lessons_list( $course );
			$course_info['lessons'] = count( $lessons );

			$topics_count  = 0;
			$quizzes_count = 0;
			foreach ( $lessons as $lesson_index => $lesson ) {
				$topics = learndash_get_topic_list( $lesson['post']->ID, $course_id );
				if ( $topics ) {
					$topics_count += count( $topics );
				}
				$lesson_quiz_list = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id, $course_id );
				$quizzes_count   += count( $lesson_quiz_list );
			}
			$course_info['topics'] = $topics_count;

			$quizzes                = learndash_get_course_quiz_list( $course );
			$course_info['quizzes'] = $quizzes_count + count( $quizzes );
			$lms_veriosn            = version_compare( Leandash_Version, '2.6.4' );
			if ( $lms_veriosn >= 0 ) {
				$certificate = learndash_get_course_meta_setting( $course_id, 'certificate' );
			} else {
				 $certificate = get_course_meta_setting( $course_id, 'certificate' );
			}
			if ( $certificate ) {
				$course_info['certificate'] = esc_html__( 'Yes', 'reign-learndash-addon' );
			} else {
				$course_info['certificate'] = esc_html__( 'No', 'reign-learndash-addon' );
			}

			$students_enrolled = learndash_get_users_for_course( $course_id, array(), true );
			if ( empty( $students_enrolled ) ) {
				$students_enrolled = array();
			} else {
				$query_args        = $students_enrolled->query_vars;
				$students_enrolled = $query_args['include'];
			}
			$course_info['students'] = count( $students_enrolled );

			$course_info['assignment'] = esc_html__( 'No', 'reign-learndash-addon' );
			foreach ( $lessons as $key => $lesson ) {
				$course_step_post = get_post( $lesson['post']->ID );
				$post_settings    = learndash_get_setting( $course_step_post );
				if ( isset( $post_settings['lesson_assignment_upload'] ) && ( 'on' === $post_settings['lesson_assignment_upload'] ) ) {
					$course_info['assignment'] = esc_html__( 'Yes', 'reign-learndash-addon' );
					break;
				}
			}

			echo '<div class="lm-tab-course-content">';
			the_content();
			echo '</div>';
			$course_features_label = sprintf( esc_html_x( '%s Features', 'Course Features  Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'course' ) );
			echo '<div class="lm-tab-course-info">';
			echo '<h3 class="title">' . $course_features_label . '</h3>';
			$course_features = array(
				'lessons'     => array(
					'slug'  => 'lessons',
					'label' => LearnDash_Custom_Label::get_label( 'lessons' ),
					'value' => $course_info['lessons'],
					'icon'  => 'fa fa-files-o',
				),
				'topics'      => array(
					'slug'  => 'lessons',
					'label' => LearnDash_Custom_Label::get_label( 'topics' ),
					'value' => $course_info['topics'],
					'icon'  => 'fa fa-bookmark-o',
				),
				'quizzes'     => array(
					'slug'  => 'quizzes',
					'label' => LearnDash_Custom_Label::get_label( 'quizzes' ),
					'value' => $course_info['quizzes'],
					'icon'  => 'fa fa-puzzle-piece',
				),
				'students'    => array(
					'slug'  => 'students',
					'label' => esc_html__( 'Students', 'reign-learndash-addon' ),
					'value' => $course_info['students'],
					'icon'  => 'fa fa-users',
				),
				'certificate' => array(
					'slug'  => 'certificate',
					'label' => esc_html__( 'Certificate', 'reign-learndash-addon' ),
					'value' => $course_info['certificate'],
					'icon'  => 'fa fa-graduation-cap',
				),
				'assignment'  => array(
					'slug'  => 'assignment',
					'label' => esc_html__( 'Assignment', 'reign-learndash-addon' ),
					'value' => $course_info['assignment'],
					'icon'  => 'fa fa-pencil-square-o',
				),
			);
			$course_features = apply_filters( 'learnmate_modify_course_features_in_tab', $course_features );
			echo '<ul>';
			foreach ( $course_features as $course_feature ) {
				?>
				<li class="<?php echo $course_feature['slug']; ?>">
					<i class="<?php echo $course_feature['icon']; ?>"></i>
					<span class="lm-course-feature-label"><?php echo $course_feature['label']; ?></span>
					<span class="lm-course-feature-value"><?php echo $course_feature['value']; ?></span>
				</li>
				<?php
			}
			echo '</ul>';
			echo '</div>';
		}

		public function learnmate_render_course_tab_data_for_curriculum() {
			global $post;
			$user_has_access = true;
			echo '<div class="learndash learndash_post_' . 'sfwd-courses' . ' ' . $user_has_access . '"  id="learndash_post_' . $post->ID . '">';
			learnmate_get_template( 'ld-template-parts/course-curriculum.php' );
			global $rtm_ld_course_curriculum;
			echo $rtm_ld_course_curriculum['lessons_list_html'];
			echo $rtm_ld_course_curriculum['quizzes_list_html'];
			echo '</div>';
		}

		public function learnmate_render_course_tab_data_for_instructors() {
			remove_filter( 'author_link', 'wpforo_change_author_default_page' );
			$course_id          = learndash_get_course_id( get_the_ID() );
			$author_id          = get_the_author_meta( 'ID' );
			$first_name         = get_the_author_meta( 'user_firstname', $author_id );
			$last_name          = get_the_author_meta( 'user_lastname', $author_id );
			$author_name        = get_the_author_meta( 'display_name', $author_id );
			$_ld_instructor_ids = get_post_meta( $course_id, '_ld_instructor_ids', true );
			if ( empty($_ld_instructor_ids) ){
				$_ld_instructor_ids = array();
			}
			$ir_shared_instructor_ids = get_post_meta( $course_id, 'ir_shared_instructor_ids', true );
			if ( $ir_shared_instructor_ids != '' ){
				$ir_shared_instructor_ids = explode(',',$ir_shared_instructor_ids);
			} else {
				$ir_shared_instructor_ids = array();
			}
			$_ld_instructor_ids	= array_merge( $_ld_instructor_ids, $ir_shared_instructor_ids);	
			$_ld_instructor_ids = array_unique($_ld_instructor_ids);

			if ( ! empty( $first_name ) ) {
				$author_name = $first_name . ' ' . $last_name;
			}
			$author_url        = apply_filters( 'lm_filter_course_author_url', get_author_posts_url( $author_id ) );
			$author_avatar_url = get_avatar_url( $author_id );

			$author_description = get_the_author_meta( 'description' );

			$social_links_list = array();
			$email             = get_the_author_meta( 'email' );
			if ( ! empty( $email ) ) {
				$social_links_list[] = array(
					'title'      => esc_html__( 'Email', 'reign-learndash-addon' ),
					'link'       => 'mailto:' . $email,
					'icon_class' => 'fa fa-envelope',
				);
			}

			$url = get_the_author_meta( 'url' );
			if ( ! empty( $url ) ) {
				$social_links_list[] = array(
					'title'      => esc_html__( 'Website', 'reign-learndash-addon' ),
					'link'       => $url,
					'icon_class' => 'fa fa-link',
				);
			}

			if ( defined( 'WPSEO_VERSION' ) ) {
				$googleplus = get_the_author_meta( 'googleplus' );
				if ( ! empty( $googleplus ) ) {
					$social_links_list[] = array(
						'title'      => esc_html__( 'Google+', 'reign-learndash-addon' ),
						'link'       => $googleplus,
						'icon_class' => 'fa fa-google-plus',
					);
				}

				$twitter = get_the_author_meta( 'twitter' );
				if ( ! empty( $twitter ) ) {
					$social_links_list[] = array(
						'title'      => esc_html__( 'Twitter', 'reign-learndash-addon' ),
						'link'       => $twitter,
						'icon_class' => 'fa fa-twitter',
					);
				}

				$facebook = get_the_author_meta( 'facebook' );
				if ( ! empty( $facebook ) ) {
					$social_links_list[] = array(
						'title'      => esc_html__( 'Facebook', 'reign-learndash-addon' ),
						'link'       => $facebook,
						'icon_class' => 'fa fa-facebook',
					);
				}
			}
			?>
		<div class="lm-course-author-info-tab">
			<div class="lm-course-author lm-course-author-avatar" itemscope="" itemtype="http://schema.org/Person">
				<img alt="Admin bar avatar" src="<?php echo $author_avatar_url; ?>" class="lm-author-avatar" width="40" height="40">
			</div>
			<div class="lm-author-bio">
				<div class="lm-author-top">
					<a href="<?php echo $author_url; ?>">
						<?php echo $author_name; ?>
					</a>
					<span><?php esc_html_e( 'Instructor', 'reign-learndash-addon' ); ?></span>
				</div>
				<ul class="lm-author-social">
					<?php
					foreach ( $social_links_list as $key => $social_link ) {
						?>
						<li>
							<a href="<?php echo $social_link['link']; ?>"><i class="<?php echo $social_link['icon_class']; ?>"></i></a>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			<div class="lm-author-description">
					<?php echo $author_description; ?>
			</div>
		</div>
			<?php

			if ( ! empty( $_ld_instructor_ids ) ) {
				foreach ( $_ld_instructor_ids as $insttuctor_id ) {
					$author_id          = $insttuctor_id;
					$first_name         = get_the_author_meta( 'user_firstname', $author_id );
					$last_name          = get_the_author_meta( 'user_lastname', $author_id );
					$author_name        = get_the_author_meta( 'display_name', $author_id );
					$author_description = get_the_author_meta( 'description', $author_id );
					$email              = get_the_author_meta( 'email', $author_id );
					$url                = get_the_author_meta( 'url', $author_id );
					if ( ! empty( $first_name ) ) {
						$author_name = $first_name . ' ' . $last_name;
					}
					$author_url        = apply_filters( 'lm_filter_course_author_url', get_author_posts_url( $author_id ), $author_id );
					$author_avatar_url = get_avatar_url( $author_id );

					$social_links_list = array();
					if ( ! empty( $email ) ) {
						$social_links_list[] = array(
							'title'      => esc_html__( 'Email', 'reign-learndash-addon' ),
							'link'       => 'mailto:' . $email,
							'icon_class' => 'fa fa-envelope',
						);
					}

					if ( ! empty( $url ) ) {
						$social_links_list[] = array(
							'title'      => esc_html__( 'Website', 'reign-learndash-addon' ),
							'link'       => $url,
							'icon_class' => 'fa fa-link',
						);
					}

					if ( defined( 'WPSEO_VERSION' ) ) {
						$twitter = get_the_author_meta( 'twitter', $author_id );
						if ( ! empty( $twitter ) ) {
							$social_links_list[] = array(
								'title'      => esc_html__( 'Twitter', 'reign-learndash-addon' ),
								'link'       => $twitter,
								'icon_class' => 'fa fa-twitter',
							);
						}

						$facebook = get_the_author_meta( 'facebook', $author_id );
						if ( ! empty( $facebook ) ) {
							$social_links_list[] = array(
								'title'      => esc_html__( 'Facebook', 'reign-learndash-addon' ),
								'link'       => $facebook,
								'icon_class' => 'fa fa-facebook',
							);
						}
					}
					?>
				<div class="lm-course-author-info-tab">
					<div class="lm-course-author lm-course-author-avatar" itemscope="" itemtype="http://schema.org/Person">
						<img alt="Admin bar avatar" src="<?php echo $author_avatar_url; ?>" class="lm-author-avatar" width="40" height="40">
					</div>
					<div class="lm-author-bio">
						<div class="lm-author-top">
							<a href="<?php echo $author_url; ?>">
								<?php echo $author_name; ?>
							</a>
							<span><?php esc_html_e( 'Instructor', 'reign-learndash-addon' ); ?></span>
						</div>
						<ul class="lm-author-social">
							<?php
							foreach ( $social_links_list as $key => $social_link ) {
								?>
								<li>
									<a href="<?php echo $social_link['link']; ?>"><i class="<?php echo $social_link['icon_class']; ?>"></i></a>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
					<div class="lm-author-description">
							<?php echo $author_description; ?>
					</div>
				</div>
						<?php
				}
			}
		}

		public function learnmate_render_course_tab_data_for_review() {
			global $post;
			$user_has_access = true;
			echo '<div class="learndash learndash_post_' . 'sfwd-courses' . ' ' . $user_has_access . '"  id="learndash_post_' . $post->ID . '">';
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
				endif;
			echo '</div>';
		}

	}
endif;
/**
 * Main instance of Reign_LD_Course_Tabs_Data.
 *
 * @return Reign_LD_Course_Tabs_Data
 */
Reign_LD_Course_Tabs_Data::instance();
