<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Reign_LD_Theme_LD30_Hook' ) ) :

	/**
	 * @class Reign_LD_Theme_LD30_Hook
	 */
	class Reign_LD_Theme_LD30_Hook {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_LD_Theme_LD30_Hook
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_LD_Theme_LD30_Hook Instance.
		 *
		 * Ensures only one instance of Reign_LD_Theme_LD30_Hook is loaded or can be loaded.
		 *
		 * @return Reign_LD_Theme_LD30_Hook - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_LD_Theme_LD30_Hook Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_filter( 'learndash_30_template_routes', array( $this, 'filter_ld30_templates_path' ), 1001, 1 );
			add_filter( 'learndash_content_tabs', array( $this, 'reign_filter_learndash_content_tabs' ), 10, 4 );
			add_shortcode( 'reign_ld_pro_comments_tab_content', array( $this, 'reign_ld_pro_comments_tab_html' ) );
			add_shortcode( 'reign_ld_pro_instructor_tab_content', array( $this, 'reign_ld_pro_instructor_tab_html' ) );
			add_shortcode( 'reign_ld_pro_course_content_tab_content', array( $this, 'reign_ld_pro_course_content_tab_html' ) );
			add_action( 'learndash-course-before', array( $this, 'reign_course_thumb_for_has_access' ), 10, 3 );
			add_action( 'reign-course-ld30-before-tabs', array( $this, 'reign_course_thumb_for_not_has_access' ), 10, 2 );			
			add_action( 'learndash-course-infobar-noaccess-status-before', array( $this, 'reign_ld30_add_course_author' ), 10, 3 );
			add_action( 'body_class', array( $this, 'reign_ld30_body_class' ), 10, 1 );
		}

		/**
		 * Filter template routes for LD 3.0 course.php and course_list_template.php.
		 *
		 * @since 1.5.0
		 */
		public function filter_ld30_templates_path( $routes ) {
			if ( isset( $routes['core'] ) ) {
				foreach ( $routes['core'] as $key => $value ) {
					if ( 'course' === $value ) {
						unset( $routes['core'][ $key ] );
					}
				}
			}
			if ( isset( $routes['shortcodes'] ) ) {
				foreach ( $routes['shortcodes'] as $key => $value ) {
					if ( 'course_list_template' === $value ) {
						unset( $routes['shortcodes'][ $key ] );
					}
				}
			}
			return $routes;
		}

		/**
		 * Filter learndash tabs in single course page template of LD 3.0 template pack.
		 *
		 * @since 1.5.0
		 */
		public function reign_filter_learndash_content_tabs( $tabs, $context, $course_id, $user_id ) {
			global $wbtm_reign_settings;
			if ( is_singular( 'sfwd-courses' ) ) {
				if ( defined( 'LEARNDASH_LEGACY_THEME' ) && class_exists( 'LearnDash_Theme_Register' ) ) {
					if ( learndash_is_active_theme( 'ld30' ) ) {
						$has_access          = sfwd_lms_has_access( $course_id, $user_id );
						$course              = get_post( $course_id );
						$course_meta         = get_post_meta( $course_id, '_sfwd-courses', true );
						$show_course_content = ( ! $has_access && ( isset( $course_meta['sfwd-courses_course_disable_content_table'] ) && 'on' === $course_meta['sfwd-courses_course_disable_content_table'] ) ? false : true );

						$lessons            = learndash_get_course_lessons_list( $course, $user_id );
						$quizzes            = learndash_get_course_quiz_list( $course );
						$has_course_content = ( ! empty( $lessons ) || ! empty( $quizzes ) );
						
						if ( ! empty( $tabs ) ) {
							foreach ( $tabs as $tab_index => $tab_val ) {
								if ( 'content' === $tab_val['id'] ) {
									$tabs[ $tab_index ]['label']   = esc_html__( 'Description', 'reign-learndash-addon' );
									$content                       = '<div class="lm-tab-course-content-data"><div class="lm-tab-course-content">' . $tab_val['content'] . '</div>';
									$content                      .= $this->reign_ld30_get_course_features();
									$content                      .= '</div>';
									if ( isset($wbtm_reign_settings['learndash']['hide_course_content_tab']) ) {
										$content .=do_shortcode( "[reign_ld_pro_course_content_tab_content course_id=$course_id user_id=$user_id ]" );
									}
									$tabs[ $tab_index ]['content'] = $content;
								}

								if ( 'materials' === $tab_val['id'] ) {
									$material_content              = '<div class="materials-content">' . $tab_val['content'] . '</div>';
									$tabs[ $tab_index ]['content'] = $material_content;
								}
							}
						}
						
						if ( $has_course_content && $show_course_content && !isset($wbtm_reign_settings['learndash']['hide_course_content_tab']) ) {
							$curriculum_label    = sprintf( esc_html_x( '%s Content', 'Course Content Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'course' ) );
							 $course_content_tab = array(
								 'id'      => 'course_content',
								 'icon'    => 'ld-icon-course-outline',
								 'label'   => $curriculum_label,
								 'content' => do_shortcode( "[reign_ld_pro_course_content_tab_content course_id=$course_id user_id=$user_id ]" ),
							 );
							 array_splice( $tabs, 1, 0, array( $course_content_tab ) );
						}
						
						
						
						$instructor_tab = ( isset( $wbtm_reign_settings['learndash']['hide_instructor_tab'] ) ) ? $wbtm_reign_settings['learndash']['hide_instructor_tab'] : '';

						if ( ! $instructor_tab ) {
							$instructor_tab = array(
								'id'      => 'instructors',
								'icon'    => 'ld-icon-login',
								'label'   => esc_html__( 'Instructors', 'reign-learndash-addon' ),
								'content' => do_shortcode( '[reign_ld_pro_instructor_tab_content]' ),
							);
							array_push( $tabs, $instructor_tab );
						}

						$review_tab = ( isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) ) ? $wbtm_reign_settings['learndash']['hide_review_tab'] : '';
						if ( ! $has_access ) {
							$has_access = ( isset( $wbtm_reign_settings['learndash']['guest_reviews'] ) ) ? $wbtm_reign_settings['learndash']['guest_reviews'] : '';
						}
						if ( comments_open() && ! $review_tab && $has_access ) {
							$review_tab = array(
								'id'      => 'review',
								'icon'    => 'ld-icon-comments',
								'label'   => esc_html__( 'Review', 'reign-learndash-addon' ),
								'content' => do_shortcode( '[reign_ld_pro_comments_tab_content]' ),
							);
							array_push( $tabs, $review_tab );
						}
					}
				}
			}
			return $tabs;
		}

		/**
		 * Course feature list for single course page template of LD 3.0 template pack.
		 *
		 * @since 1.5.0
		 */
		public function reign_ld30_get_course_features() {
			global $wbtm_reign_settings;
			$course_layout = get_post_meta( get_the_id(), 'rla_course_layout', true );
			if ( $course_layout != '' ) {
				$wbtm_reign_settings['learndash']['course_layout'] = $course_layout;
			}
			
			if ( !isset($wbtm_reign_settings['learndash']['course_layout']) || isset($wbtm_reign_settings['learndash']['course_layout']) && $wbtm_reign_settings['learndash']['course_layout'] != 'default') {
				return;
			}
			$content     = '';
			$course_info = array();
			$course_id   = learndash_get_course_id( get_the_ID() );
			$course      = get_post( $course_id );
			$rla_ccf_enable = get_post_meta( $course_id, 'rla_ccf_enable', true );
			$rla_ccf_features = get_post_meta( $course_id, 'rla_ccf_features', true );
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
				$course_info['certificate'] = __( 'Yes', 'reign-learndash-addon' );
			} else {
				$course_info['certificate'] = __( 'No', 'reign-learndash-addon' );
			}

			$course_user_query = learndash_get_users_for_course( $course_id, array(), true );
			$course_user_ids   = array();
			if ( $course_user_query instanceof WP_User_Query ) {
				$user_ids = $course_user_query->get_results();
				if ( ! empty( $user_ids ) ) {
						$course_user_ids = array_merge( $course_user_ids, $user_ids );
				}
			}
			$course_info['students'] = count( $course_user_ids );

			$course_info['assignment'] = __( 'No', 'reign-learndash-addon' );
			foreach ( $lessons as $key => $lesson ) {
				$course_step_post = get_post( $lesson['post']->ID );
				$post_settings    = learndash_get_setting( $course_step_post );
				if ( isset( $post_settings['lesson_assignment_upload'] ) && ( 'on' === $post_settings['lesson_assignment_upload'] ) ) {
					$course_info['assignment'] = __( 'Yes', 'reign-learndash-addon' );
					break;
				}
			}
			$course_features_label = sprintf( esc_html_x( '%s Features', 'Course Features Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'course' ) );
			$content              .= '<div class="lm-tab-course-info">
						    <h3 class="title">' . $course_features_label . '</h3>';

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
								'label' => __( 'Students', 'reign-learndash-addon' ),
								'value' => $course_info['students'],
								'icon'  => 'fa fa-users',
							),
							'certificate' => array(
								'slug'  => 'certificate',
								'label' => __( 'Certificate', 'reign-learndash-addon' ),
								'value' => $course_info['certificate'],
								'icon'  => 'fa fa-graduation-cap',
							),
							'assignment'  => array(
								'slug'  => 'assignment',
								'label' => __( 'Assignment', 'reign-learndash-addon' ),
								'value' => $course_info['assignment'],
								'icon'  => 'fa fa-pencil-square-o',
							),
						);
						$course_features = apply_filters( 'learnmate_modify_course_features_in_tab', $course_features );
						$content        .= '<ul>';
						if ( $rla_ccf_enable == 'yes'){
							for($i=0; $i<sizeof($rla_ccf_features['icon']); $i++ ){
								
								$content .= '<li>
									<i class="'.$rla_ccf_features['icon'][$i].'"></i>					
									<span class="lm-course-feature-value">'.$rla_ccf_features['text'][$i].'</span>
								</li>';
							}

						} else {
							foreach ( $course_features as $course_feature ) {
								$content .= '<li class=' . $course_feature['slug'] . '>
									<i class="' . $course_feature['icon'] . ' learndash-wrapper ld-primary-color"></i>
									<span class="lm-course-feature-label">' . $course_feature['label'] . '</span>
									<span class="lm-course-feature-value">' . $course_feature['value'] . '</span>
								</li>';
							}
						}
						$content .= '</ul></div>';
						return $content;
		}

		/**
		 * Course content tab html in single course page template of LD 3.0 template pack.
		 *
		 * @since 1.5.0
		 */
		public function reign_ld_pro_course_content_tab_html( $atts ) {
			ob_start();
			global $course_pager_results;
			$atts                       = shortcode_atts(
				array(
					'course_id' => get_the_ID(),
					'user_id'   => get_current_user_id(),
				),
				$atts,
				'reign_ld_pro_course_content_tab_content'
			);
			$course_id                  = $atts['course_id'];
			$user_id                    = $atts['user_id'];
			$course                     = get_post( $course_id );
			$has_access                 = sfwd_lms_has_access( $course_id, $user_id );
			$lesson_progression_enabled = false;
			$lesson_progression_enabled = learndash_lesson_progression_enabled( $course_id );
			$lessons                    = learndash_get_course_lessons_list( $course, $user_id );
			$quizzes                    = learndash_get_course_quiz_list( $course );
			$has_course_content         = ( ! empty( $lessons ) || ! empty( $quizzes ) );

			$has_topics    = false;
			$lesson_topics = array();
			if ( ! empty( $lessons ) ) {
				foreach ( $lessons as $lesson ) {
					$lesson_topics[ $lesson['post']->ID ] = learndash_topic_dots( $lesson['post']->ID, false, 'array', null, $course_id );
					if ( ! empty( $lesson_topics[ $lesson['post']->ID ] ) ) {
						$has_topics = true;

						$topic_pager_args                     = array(
							'course_id' => $course_id,
							'lesson_id' => $lesson['post']->ID,
						);
						$lesson_topics[ $lesson['post']->ID ] = learndash_process_lesson_topics_pager( $lesson_topics[ $lesson['post']->ID ], $topic_pager_args );
					}
				}
			}
			$has_lesson_quizzes = learndash_30_has_lesson_quizzes( $course_id, $lessons );
			?>
			<div class="ld-item-list ld-lesson-list">
				<div class="ld-section-heading">

					<?php
					/**
					 * Action to add custom content before the course heading
					 *
					 * @since 3.0
					 */
					do_action( 'learndash-course-heading-before', $course_id, $user_id );
					?>

					<h2><?php printf( esc_html_x( '%s Content', 'Course Content Label', 'learndash' ), esc_attr( LearnDash_Custom_Label::get_label( 'course' ) ) ); ?></h2>

					<?php
					/**
					 * Action to add custom content after the course heading
					 *
					 * @since 3.0
					 */
					do_action( 'learndash-course-heading-after', $course_id, $user_id );
					?>

					<div class="ld-item-list-actions" data-ld-expand-list="true">

						<?php
						/**
						 * Action to add custom content after the course content progress bar
						 *
						 * @since 3.0
						 */
						do_action( 'learndash-course-expand-before', $course_id, $user_id );
						?>

						<?php
						// Only display if there is something to expand
						if ( $has_topics || $has_lesson_quizzes ) :
							?>
							<div class="ld-expand-button ld-primary-background" id="<?php echo esc_attr( 'ld-expand-button-' . $course_id ); ?>" data-ld-expands="<?php echo esc_attr( 'ld-item-list-' . $course_id ); ?>" data-ld-expand-text="<?php echo esc_attr_e( 'Expand All', 'learndash' ); ?>" data-ld-collapse-text="<?php echo esc_attr_e( 'Collapse All', 'learndash' ); ?>">
								<span class="ld-icon-arrow-down ld-icon"></span>
								<span class="ld-text"><?php echo esc_html_e( 'Expand All', 'learndash' ); ?></span>
							</div> <!--/.ld-expand-button-->
							<?php
							// TODO @37designs Need to test this
							if ( apply_filters( 'learndash_course_steps_expand_all', false, $course_id, 'course_lessons_listing_main' ) ) :
								?>
								<script>
									jQuery(document).ready(function(){
										jQuery("<?php echo '#ld-expand-button-' . $course_id; ?>").click();
									});
								</script>
								<?php
							endif;

						endif;

						/**
						 * Action to add custom content after the course content expand button
						 *
						 * @since 3.0
						 */
						do_action( 'learndash-course-expand-after', $course_id, $user_id );
						?>

					</div> <!--/.ld-item-list-actions-->
				</div> <!--/.ld-section-heading-->

				<?php
				/**
				 * Action to add custom content before the course content listing
				 *
				 * @since 3.0
				 */
				do_action( 'learndash-course-content-list-before', $course_id, $user_id );

				/**
				 * Content content listing
				 *
				 * @since 3.0
				 *
				 * ('listing.php');
				 */

				learndash_get_template_part(
					'course/listing.php',
					array(
						'course_id'                  => $course_id,
						'user_id'                    => $user_id,
						'lessons'                    => $lessons,
						'lesson_topics'              => @$lesson_topics,
						'quizzes'                    => $quizzes,
						'has_access'                 => $has_access,
						'course_pager_results'       => $course_pager_results,
						'lesson_progression_enabled' => $lesson_progression_enabled,
					),
					true
				);

				/**
				 * Action to add custom content before the course content listing
				 *
				 * @since 3.0
				 */
				do_action( 'learndash-course-content-list-after', $course_id, $user_id );
				?>

			</div> <!--/.ld-item-list-->
			<?php
			return ob_get_clean();
		}

		/**
		 * Displays comments tab content in single course page template of LD 3.0 template pack.
		 *
		 * @since 1.5.0
		 */
		public function reign_ld_pro_comments_tab_html() {
			ob_start();
			$reign_ld_tab_instance = Reign_LD_Course_Tabs_Data::instance();
			$reign_ld_tab_instance->learnmate_render_course_tab_data_for_review();
			return ob_get_clean();
		}

		/**
		 * Displays instructors tab content in single course page template of LD 3.0 template pack.
		 *
		 * @since 1.5.0
		 */
		public function reign_ld_pro_instructor_tab_html() {
			ob_start();
			$reign_ld_tab_instance = Reign_LD_Course_Tabs_Data::instance();
			$reign_ld_tab_instance->learnmate_render_course_tab_data_for_instructors();
			return ob_get_clean();
		}

		/**
		 * Add single course top bar in single course page template of LD 3.0 template pack.
		 *
		 * @since 1.5.0
		 */
		public function reign_ld_default_theme_product_header() {
			global $wbtm_reign_settings;
			if ( is_singular( 'sfwd-courses' ) ) {
				if ( defined( 'LEARNDASH_LEGACY_THEME' ) && class_exists( 'LearnDash_Theme_Register' ) ) {
					if ( LEARNDASH_DEFAULT_THEME === LearnDash_Theme_Register::get_active_theme_key() ) {
						$review_tab    = ( isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) ) ? $wbtm_reign_settings['learndash']['hide_review_tab'] : '';
						$course_id     = learndash_get_course_id( get_the_ID() );						
						if ( is_user_logged_in() ) {
							$user_id = get_current_user_id();
						} else {
							$user_id = 0;
						}

						$course_certficate_link = learndash_get_course_certificate_link( $course_id, $user_id );
						
						$author_name       = get_the_author_meta( 'first_name' ) . ' ' . get_the_author_meta( 'last_name' );
						$author_id         = get_the_author_meta( 'ID' );
						remove_filter( 'author_link', 'wpforo_change_author_default_page' );
						$author_url        = apply_filters( 'lm_filter_course_author_url', get_author_posts_url( $author_id ) );
						$author_avatar_url = get_avatar_url( $author_id );
						
						?>
						<div class="lm-ld-course-item-single lm-course-item-wrapper">
							<div class="lm-course-item">
								<div class="lm-course-content">
									<div class="lm-course-meta">
										<div class="lm-course-author lm-course-list-view-data" itemscope="" itemtype="http://schema.org/Person">
											<a href="<?php echo $author_url; ?>">
												<img alt="Admin bar avatar" src="<?php echo $author_avatar_url; ?>" class="lm-author-avatar" width="40" height="40">
											</a>
											<div class="author-contain">
												<label itemprop="jobTitle"><?php _e( 'Instructor', 'reign-learndash-addon' ); ?></label>
												<div class="lm-value" itemprop="name">
													<a href="<?php echo $author_url; ?>">
														<?php echo $author_name; ?>
													</a>
												</div>
											</div>
										</div>

										<?php
										$categories_string = '';
										$categories        = get_the_terms( $course_id, 'ld_course_category' );
										if ( ! empty( $categories ) && is_array( $categories ) ) {
											foreach ( $categories as $value ) {
												$categories_string .= '<a href="' . get_category_link( $value->term_id ) . '">' . $value->name . '</a>,';
												break;
											}
										}
										if ( ! empty( $categories_string ) ) {
											$categories_string = trim( $categories_string, ',' );
											?>
											<div class="lm-course-students">
												<label><?php _e( 'Categories', 'reign-learndash-addon' ); ?></label>
												<div class="lm-value">
													<?php echo $categories_string; ?>
												</div>
											</div>
											<?php
										}
										?>

										<?php
										if ( class_exists( 'LD_Course_Review_Manager' ) && ! $review_tab ) :
											$course_reviews_analytics = wb_ld_get_course_reviews_analytics();
											?>
											<div class="lm-course-review lm-course-list-view-data">
												<label><?php _e( 'Review', 'reign-learndash-addon' ); ?></label>
												<div class="lm-value">
													<div class="lm-review-stars-rated">
														<ul class="lm-review-stars">
															<li><span class="fa fa-star-o"></span></li>
															<li><span class="fa fa-star-o"></span></li>
															<li><span class="fa fa-star-o"></span></li>
															<li><span class="fa fa-star-o"></span></li>
															<li><span class="fa fa-star-o"></span></li>
														</ul>
														<ul class="lm-review-stars lm-filled" style="width: <?php echo $course_reviews_analytics['reviews_percentage']; ?>%">
															<li><span class="fa fa-star"></span></li>
															<li><span class="fa fa-star"></span></li>
															<li><span class="fa fa-star"></span></li>
															<li><span class="fa fa-star"></span></li>
															<li><span class="fa fa-star"></span></li>
														</ul>
													</div>
													<span>(<?php echo $course_reviews_analytics['total_reviews']; ?> <?php _e( 'reviews', 'reign-learndash-addon' ); ?>)</span>
												</div>
											</div>
										<?php endif; ?>
										<?php if ( empty( $course_certficate_link ) ) { ?>
												<div class="lm-course-price">
													<?php echo get_reign_ld_course_price( $course_id ); ?>
												</div>
												<?php
												echo do_shortcode( '[learndash_payment_buttons course_id="' . $course_id . '"]' );
										}
										?>
									</div>
								</div>
							</div>
						</div>
						<?php						
					}
				}
			}
		}

		/**
		 * Add course author name .
		 *
		 * @since 1.5.0
		 */
		public function reign_ld30_add_course_author( $posttype, $course_id, $user_id ) {
			remove_filter( 'author_link', 'wpforo_change_author_default_page' );
			$author_id   = get_the_author_meta( 'ID' );
			$first_name  = get_the_author_meta( 'user_firstname', $author_id );
			$last_name   = get_the_author_meta( 'user_lastname', $author_id );
			$author_name = get_the_author_meta( 'display_name', $author_id );
			if ( ! empty( $first_name ) ) {
				$author_name = $first_name . ' ' . $last_name;
			}
			$author_url        = apply_filters( 'lm_filter_course_author_url', get_author_posts_url( $author_id ) );
			$author_avatar_url = get_avatar_url( $author_id );
			?>
			<div class="ld-course-status-segment ld-course-status-seg-author">
				<div class="lm-course-author lm-course-list-view-data" itemscope="" itemtype="http://schema.org/Person">
					<a href="<?php echo $author_url; ?>">
						<img alt="Admin bar avatar" src="<?php echo $author_avatar_url; ?>" class="lm-author-avatar" width="40" height="40">
					</a>
					<div class="author-contain">
						<label itemprop="jobTitle"><?php esc_html_e( 'Instructor', 'reign-learndash-addon' ); ?></label>
						<div class="lm-value" itemprop="name">
							<a href="<?php echo $author_url; ?>">
								<?php echo $author_name; ?>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Get single course thumbnail for user those have access.
		 *
		 * @since 1.5.0
		 */
		public function reign_course_thumb_for_has_access( $id, $course_id, $user_id ) {
			$has_access = sfwd_lms_has_access( $course_id, $user_id );
			if ( is_user_logged_in() && isset( $has_access ) && $has_access ) {
				$this->reign_ld_single_course_thumb_display( $course_id, $user_id );
			}
		}

		/**
		 * Get single course thumbnail for non logged in user or for those users have not access.
		 *
		 * @since 1.5.0
		 */
		public function reign_course_thumb_for_not_has_access( $course_id, $user_id ) {
			$has_access = sfwd_lms_has_access( $course_id, $user_id );
			if ( ! is_user_logged_in() || ! $has_access ) {
				$this->reign_ld_single_course_thumb_display( $course_id, $user_id );
			}
		}

		/**
		 * Displays single course thumbnail.
		 *
		 * @since 1.5.0
		 */
		public function reign_ld_single_course_thumb_display( $course_id, $user_id ) {
			global $wbtm_reign_settings;
			$course_layout = get_post_meta( $course_id, 'rla_course_layout', true );
			if ( $course_layout != '' ) {
				$wbtm_reign_settings['learndash']['course_layout'] = $course_layout;
			}
			?>
			<div class="lm-course-image">
				<?php
				if ( !isset($wbtm_reign_settings['learndash']['course_layout']) || isset($wbtm_reign_settings['learndash']['course_layout']) && $wbtm_reign_settings['learndash']['course_layout'] != 'udemy') {
				
					if ( has_post_thumbnail() ) {
						the_post_thumbnail();
					} else {
						echo get_reign_ld_default_course_img_html();
					}
				}
				?>

				
			</div>
			<?php
		}

		public function reign_ld30_body_class( $classes ) {
			if ( defined( 'LEARNDASH_LEGACY_THEME' ) && class_exists( 'LearnDash_Theme_Register' ) ) {
				if ( learndash_is_active_theme( 'ld30' ) ) {
					$focus_mode = LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Theme_LD30', 'focus_mode_enabled' );

					if ( $focus_mode === 'yes' ) {
						$classes[] = 'ld-distraction-free-reading-active';
					}
				}
			}

			return $classes;
		}

	}

	endif;
/**
 * Main instance of Reign_LD_Theme_LD30_Hook.
 *
 * @return Reign_LD_Theme_LD30_Hook
 */
Reign_LD_Theme_LD30_Hook::instance();
