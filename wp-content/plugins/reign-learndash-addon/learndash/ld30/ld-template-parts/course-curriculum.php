<?php

global $wbtm_reign_settings, $post;
$curriculum_layout	 = isset( $wbtm_reign_settings[ 'learndash' ][ 'curriculum_layout' ] ) ? $wbtm_reign_settings[ 'learndash' ][ 'curriculum_layout' ] : 'collapsed';
if( 'collapsed' === $curriculum_layout ) {
	$topic_list_inline_style = 'display:none;';
	$lesson_title_icon_class = 'fa fa-chevron-down';
}
else {
	$topic_list_inline_style = 'display:block;';
	$lesson_title_icon_class = 'fa fa-chevron-up';
}

$lessons_list_html = '';
$quizzes_list_html = '';
$sidebar_dropdown_html = '';

if ( is_user_logged_in() )
	$user_id = get_current_user_id();
else
	$user_id = 0;
$course_id = learndash_get_course_id();

if ( ! empty( $course_id ) ) {
	$course = get_post( $course_id );
	$course_meta = get_post_meta( $course_id, '_sfwd-courses', true );
	$has_access = sfwd_lms_has_access( $course_id, $user_id );
}
else {
	return;
}
$lessons = learndash_get_course_lessons_list( $course_id );

// For now no paginiation on the course quizzes. Can't think of a scenario where there will be more 
// than the pager count. 
$quizzes = learndash_get_course_quiz_list( $course );

$has_course_content = ( ! empty( $lessons) || ! empty( $quizzes ) );

$has_topics = false;

if ( ! empty( $lessons ) ) {
	foreach ( $lessons as $lesson ) {
		$lesson_topics[ $lesson['post']->ID ] = learndash_topic_dots( $lesson['post']->ID, false, 'array', null, $course_id );
		if ( ! empty( $lesson_topics[ $lesson['post']->ID ] ) ) {
			$has_topics = true;
		}
	}
}

$current_lesson_id = 0;
if ( $post->post_type == 'sfwd-lessons' ) {
	$current_lesson_id = $post->ID;
} else if ( in_array( $post->post_type, array('sfwd-topic', 'sfwd-quiz') ) ) {
	$current_lesson_id = learndash_course_get_single_parent_step( $course_id, $post->ID, 'sfwd-lessons' );
}


?>

<?php if ( $has_course_content ) : ?>
	<?php
		$show_course_content = true;
	if ( ! $has_access ) :
		if ( isset( $course_meta['sfwd-courses_course_disable_content_table'] ) && 'on' === $course_meta['sfwd-courses_course_disable_content_table'] ) :
			$show_course_content = false;
			endif;
		endif;

	if ( $show_course_content ) {
	ob_start();
	?>
	<div id="learndash_course_content" class="learndash_course_content">
		<!-- <h4 id="learndash_course_content_title">
			<?php
			// translators: Course Content Label.
			printf( esc_html_x( '%s Content', 'Course Content Label', 'learndash' ), LearnDash_Custom_Label::get_label( 'course' ) );
			?>
		</h4> -->
		<?php $course_settings = learndash_get_setting( $course );
				$materials = '';
				if ( ! empty( $course_settings['course_materials'] ) ) {
					$materials = wp_specialchars_decode( $course_settings['course_materials'], ENT_QUOTES );
					if ( ( isset( $materials ) ) && ( ! empty( $materials ) ) ) : ?>
					<div class="lm_learndash_course_materials">	
						<div id="learndash_course_materials" class="learndash_course_materials">
							<span>
								<?php
									// translators: Course Materials Label.
									printf( esc_html_x( '%s Materials', 'Course Materials Label', 'learndash' ), LearnDash_Custom_Label::get_label( 'course' ) );
								?>
							</span>
						</div>	
						<div class="materials-content"><?php echo $materials; ?></div>	
					</div>						
				<?php endif;
				}
		?>

		<div id="lm-lesson-heading">
			<span><?php echo LearnDash_Custom_Label::get_label( 'lessons' ); ?></span>
		</div>

		<?php
		echo '<ul class="lm-course-hierarchy">';
		foreach ( $lessons as $lesson_index => $lesson ) {
			$topics = @$lesson_topics[ $lesson['post']->ID ];
			$is_current_lesson = ( $lesson['post']->ID == $current_lesson_id ) ? 'learnmate-current-menu-item' : '';
			
			$lesson_settings = learndash_get_setting( $lesson['post']->ID );
			if( isset( $lesson_settings['sample_lesson'] ) && ( 'on' === $lesson_settings['sample_lesson'] ) ) {
				$is_sample_lesson = '<i class="is_sample_lesson fa fa-unlock" aria-hidden="true"></i>';
			}
			else {
				$is_sample_lesson = '';
			}
			
			?>
			<li class="lm-lesson <?php echo $is_current_lesson; ?>">
				<h4 class="lm-lesson-section-header">
					<?php if ( ! empty( $topics ) ) : ?>
						<span class="lm-topics-toggle"><i class="<?php echo $lesson_title_icon_class; ?>"></i></span>
					<?php else: ?>
						<span class="lm-topics-toggle"><i class="fa fa-circle"></i></span>
					<?php endif; ?>

					<?php
					$status_element = '<i class="fa fa-pencil-square-o"></i>';
					if( 'completed' === esc_attr( $lesson['status'] ) ) {
						$status_element = '<i class="fa fa-check-square-o"></i>';
					}
					elseif( 'notavailable' === esc_attr( $lesson['status'] ) ) {
						$status_element = '<i class="fa fa-check-square-o"></i>';
					}
					$status_element = '';
					?>
					<?php
					/**
					 * Not available message for drip feeding lessons
					 */
					?>
					<?php if ( ! empty( $lesson['lesson_access_from'] ) ) : ?>
						<a class='<?php echo esc_attr( $lesson['status'] ); ?>' href='<?php echo esc_attr( learndash_get_step_permalink( $lesson['post']->ID, $course_id ) ); ?>' data-object-id="<?php echo $lesson['post']->ID; ?>"><?php echo $lesson['post']->post_title; ?></a>
						<?php
							SFWD_LMS::get_template(
								'learndash_course_lesson_not_available',
								array(
									'user_id' => $user_id,
									'course_id' => learndash_get_course_id( $lesson['post']->ID ),
									'lesson_id' => $lesson['post']->ID,
									'lesson_access_from_int' => $lesson['lesson_access_from'],
									'lesson_access_from_date' => learndash_adjust_date_time_display( $lesson['lesson_access_from'] ),
									'context' => 'course',
								), true
							);
						?>
					<?php else : ?>
						<a class='<?php echo esc_attr( $lesson['status'] ); ?>' href='<?php echo esc_attr( learndash_get_step_permalink( $lesson['post']->ID, $course_id ) ); ?>' data-object-id="<?php echo $lesson['post']->ID; ?>"><?php echo $lesson['post']->post_title; ?>&nbsp;<?php echo $is_sample_lesson; ?></a>	
					<?php endif; ?>
					<?php //echo $status_element; ?>
				</h4>
				<?php
				$sidebar_dropdown_html .= '<option value="' . esc_attr( learndash_get_step_permalink( $lesson['post']->ID, $course_id ) ) . '"' . selected( $lesson['post']->ID , $post->ID, false ) . '>' . $lesson['post']->post_title . '</option>';
				
				if ( ! empty( $topics ) ) {
					echo '<ul class="lm-topics-list" style="' . $topic_list_inline_style . '">';
					$odd_class = '';
					foreach ( $topics as $key => $topic ) {
						$topic_index = $key+1;
						$odd_class = empty( $odd_class ) ? 'nth-of-type-odd' : '';
						$completed_class = empty( $topic->completed ) ? 'topic-notcompleted' : 'topic-completed';
						$is_current_lesson = ( $topic->ID == $post->ID ) ? 'learnmate-current-menu-item-inner' : '';
						?>
						<li class='lm-topic <?php echo esc_attr( $odd_class ); ?> <?php echo esc_attr( $is_current_lesson ); ?>'>
							<div class="topic_item">
								<div class="lm-topic-meta-left">
									<i class="fa fa-bookmark-o"></i>
									<!-- <i class="fa fa-play-circle"></i> -->
									<!-- <div>
										<span class="label">Lecture</span>
										<span><?php echo $lesson_index . '.' . $topic_index; ?></span>
									</div> -->
								</div>
								<div class="lm-topic-name">
									<?php
									$status_element = '<i class="fa fa-pencil-square-o"></i>';
									if( 'topic-completed' === esc_attr( $completed_class ) ) {
										$status_element = '<i class="fa fa-check-square-o"></i>';
									}
									$status_element = '';
									?>
									<a class='<?php echo esc_attr( $completed_class ); ?>' href='<?php echo esc_attr( learndash_get_step_permalink( $topic->ID, $course_id ) ); ?>' title='<?php echo esc_attr( $topic->post_title ); ?>' data-object-id="<?php echo $topic->ID; ?>">
										<span><?php echo $topic->post_title; ?></span>
									</a>
									<?php echo $status_element; ?>
								</div>
								<div class="lm-topic-meta-right <?php echo $completed_class; ?>">
									<?php
									if( 'topic-completed' === esc_attr( $completed_class ) ) {
										$status_element = '<i class="fa fa-check-circle-o"></i>';
									}
									else {
										$status_element = '<i class="fa fa-circle-o"></i>';
									}
									echo $status_element;
									?>
								</div>
							</div>
						</li>
						<?php
						$sidebar_dropdown_html .= '<option value="' . esc_attr( learndash_get_step_permalink( $topic->ID, $course_id ) ) . '"' . selected( $topic->ID, $post->ID, false ) . '>' . $topic->post_title . '</option>';
					}

					/** show quizes :: start **/
					$lesson_quiz_list = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id, $course_id );
					if ( !empty( $lesson_quiz_list ) ) {
						foreach ( $lesson_quiz_list as $quiz ) { 
							$quiz_completed = learndash_is_quiz_complete( $user_id, $quiz['post']->ID, $course_id );
							$completed_class = empty( $quiz_completed ) ? 'topic-notcompleted' : 'topic-completed';
							$current_topic_class = ( $quiz['post']->ID == $post->ID ) ? 'learnmate-current-menu-item-inner' : '';
							if ( !empty( $current_topic_class ) ) {
								$lesson_topic_child_item_active = true;
							}
							?>
							<li class="lm-topic quiz-item <?php echo $current_topic_class ?>">
								<div class="topic_item">
									<div class="lm-topic-meta-left">
										<i class="fa fa-puzzle-piece"></i>
									</div>
									<div class="lm-topic-name">
										<a class='<?php echo esc_attr( $completed_class ); ?>' href='<?php echo esc_attr( learndash_get_step_permalink( $quiz['post']->ID, $course_id ) ); ?>' title='<?php echo esc_attr( $quiz['post']->post_title ); ?>'>
											<span><?php echo $quiz['post']->post_title; ?></span>
										</a>
									</div>
									<div class="lm-topic-meta-right <?php echo $completed_class; ?>">
										<?php
										if( $quiz_completed ) {
											$status_element = '<i class="fa fa-check-circle-o"></i>';
										}
										else {
											$status_element = '<i class="fa fa-circle-o"></i>';
										}
										echo $status_element;
										?>
									</div>
								</div>
							</li>
							<?php
						} 
					}
					/** show quizes :: end **/
					echo '</ul>';
				}
				?>
			</li>
			<?php
		}
		echo '</ul>';
	echo '</div>';

	$lessons_list_html = ob_get_clean();

	$sidebar_dropdown_html = '<select class="reign-select2-element" id="rtm-ld-lesson-selector">' . $sidebar_dropdown_html . '</select>';

	?>

	<?php
	global $course_lessons_results;
	if ( isset( $course_lessons_results['pager'] ) ) {
		echo SFWD_LMS::get_template( 
			'learndash_pager.php', 
			array(
			'pager_results' => $course_lessons_results['pager'], 
			'pager_context' => 'course_lessons'
			) 
		);
	}

	if ( ( isset( $course_lessons_results['pager'] ) ) && ( !empty( $course_lessons_results['pager'] ) ) ) {
		if ( $course_lessons_results['pager']['paged'] == $course_lessons_results['pager']['total_pages'] ) {
			$show_course_quizzes = true;
		} else {
			$show_course_quizzes = false;
		}
	} else {
		$show_course_quizzes = true;
	}
	/**
	 * Display quiz list
	 */
	if ( $show_course_quizzes == true ) {
		if ( ! empty( $quizzes ) ) {
			ob_start();
			?>
			<div id="lm-learndash-quizzes" class="lm-learndash-quizzes">
				<div id="lm-quiz-heading">
					<span><?php echo LearnDash_Custom_Label::get_label( 'quizzes' ); ?></span>
				</div>
				<div id="lm-quiz-list-wrap" class="lm-quiz-list-wrap">
					<ul class="lm-quiz-list">
						<?php foreach ( $quizzes as $quiz ) : ?>
							<li>
								<i class="fa fa-puzzle-piece"></i>
								<a class='<?php //echo esc_attr( $quiz['status'] ); ?>' href='<?php echo esc_attr( learndash_get_step_permalink( $quiz['post']->ID, $course_id ) ); ?>'><?php echo $quiz['post']->post_title; ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<?php
			$quizzes_list_html = ob_get_clean();
		}
	}

	// echo $lessons_list_html;
	// echo $quizzes_list_html;
	// echo $sidebar_dropdown_html;

	global $rtm_ld_course_curriculum;
	$rtm_ld_course_curriculum = array(
		'lessons_list_html' => $lessons_list_html,
		'quizzes_list_html' => $quizzes_list_html,
		'sidebar_dropdown_html' => $sidebar_dropdown_html,
	);

	
	return;



		$lessons = array();
		?>

		<?php
		/**
		 * Display lesson list
		 */
		?>
		<?php if ( ! empty( $lessons ) ) : ?>

			<?php if ( $has_topics ) : ?>
				<div class="expand_collapse">
					<a href="#" onClick='jQuery("#learndash_post_<?php echo $course_id; ?> .learndash_topic_dots").slideDown(); return false;'><?php esc_html_e( 'Expand All', 'learndash' ); ?></a> | <a href="#" onClick='jQuery("#learndash_post_<?php echo esc_attr( $course_id ); ?> .learndash_topic_dots").slideUp(); return false;'><?php esc_html_e( 'Collapse All', 'learndash' ); ?></a>
				</div>
				<?php if ( apply_filters( 'learndash_course_steps_expand_all', false, $course_id, 'course_lessons_listing_main' ) ) { ?>
					<script>
						jQuery(document).ready(function(){
							jQuery("#learndash_post_<?php echo $course_id; ?> .learndash_topic_dots").slideDown();
						});
					</script>	
				<?php } ?>
			<?php endif; ?>

			<div id="learndash_lessons" class="learndash_lessons">

				<div id="lesson_heading">
						<span><?php echo LearnDash_Custom_Label::get_label( 'lessons' ); ?></span>
					<span class="right"><?php esc_html_e( 'Status', 'learndash' ); ?></span>
				</div>

				<div id="lessons_list" class="lessons_list">

					<?php foreach ( $lessons as $lesson ) : ?>
						<div class='post-<?php echo esc_attr( $lesson['post']->ID ); ?> <?php echo esc_attr( $lesson['sample'] ); ?>'>

							<div class="list-count list_arrow collapse lesson_completed">
								<?php //echo $lesson['sno']; ?>
								<!-- <i class="fa fa-facebook"></i> -->
							</div>

							<h4>
								<a class='<?php echo esc_attr( $lesson['status'] ); ?>' href='<?php echo esc_attr( learndash_get_step_permalink( $lesson['post']->ID, $course_id ) ); ?>' data-object-id="<?php echo $lesson['post']->ID; ?>"><?php echo $lesson['post']->post_title; ?></a>


								<?php
								/**
								 * Not available message for drip feeding lessons
								 */
								?>
								<?php if ( ! empty( $lesson['lesson_access_from'] ) ) : ?>
									<?php
										SFWD_LMS::get_template(
											'learndash_course_lesson_not_available',
											array(
												'user_id' => $user_id,
												'course_id' => learndash_get_course_id( $lesson['post']->ID ),
												'lesson_id' => $lesson['post']->ID,
												'lesson_access_from_int' => $lesson['lesson_access_from'],
												'lesson_access_from_date' => learndash_adjust_date_time_display( $lesson['lesson_access_from'] ),
												'context' => 'course',
											), true
										);
									?>
								<?php endif; ?>


								<?php
								/**
								 * Lesson Topics
								 */
								?>
								<?php $topics = @$lesson_topics[ $lesson['post']->ID ]; ?>

								<?php if ( ! empty( $topics ) ) : ?>
									<div id='learndash_topic_dots-<?php echo esc_attr( $lesson['post']->ID ); ?>' class="learndash_topic_dots type-list">
										<ul>
											<?php $odd_class = ''; ?>
											<?php foreach ( $topics as $key => $topic ) : ?>
												<?php $odd_class       = empty( $odd_class ) ? 'nth-of-type-odd' : ''; ?>
												<?php $completed_class = empty( $topic->completed ) ? 'topic-notcompleted' : 'topic-completed'; ?>												
												<li class='<?php echo esc_attr( $odd_class ); ?>'>
													<span class="topic_item">
														<a class='<?php echo esc_attr( $completed_class ); ?>' href='<?php echo esc_attr( learndash_get_step_permalink( $topic->ID, $course_id ) ); ?>' title='<?php echo esc_attr( $topic->post_title ); ?>' data-object-id="<?php echo $topic->ID; ?>">
															<span><?php echo $topic->post_title; ?></span>
														</a>
													</span>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php endif; ?>

							</h4>
						</div>
					<?php endforeach; ?>

				</div>
			</div>
			<?php
				global $course_lessons_results;
				if ( isset( $course_lessons_results['pager'] ) ) {
					echo SFWD_LMS::get_template( 
						'learndash_pager.php', 
						array(
						'pager_results' => $course_lessons_results['pager'], 
						'pager_context' => 'course_lessons'
						) 
					);
				}
			?>
		<?php endif; ?>
		
		<?php
			if ( ( isset( $course_lessons_results['pager'] ) ) && ( !empty( $course_lessons_results['pager'] ) ) ) {
				if ( $course_lessons_results['pager']['paged'] == $course_lessons_results['pager']['total_pages'] ) {
					$show_course_quizzes = true;
				} else {
					$show_course_quizzes = false;
				}
			} else {
				$show_course_quizzes = true;
			}
		?>
		<?php
		/**
		 * Display quiz list
		 */
		?>
		<?php 
			if ( $show_course_quizzes == true ) {
				if ( ! empty( $quizzes ) ) { ?>
					<div id="learndash_quizzes" class="learndash_quizzes">
						<div id="quiz_heading">
								<span><?php echo LearnDash_Custom_Label::get_label( 'quizzes' ); ?></span><span class="right"><?php esc_html_e( 'Status', 'learndash' ); ?></span>
						</div>
						<div id="quiz_list" class=“quiz_list”>

							<?php foreach ( $quizzes as $quiz ) : ?>
								<div id='post-<?php echo esc_attr( $quiz['post']->ID ); ?>' class='<?php echo esc_attr( $quiz['sample'] ); ?>'>
									<div class="list-count"><?php echo $quiz['sno']; ?></div>
									<h4>
										<a class='<?php echo esc_attr( $quiz['status'] ); ?>' href='<?php echo esc_attr( learndash_get_step_permalink( $quiz['post']->ID, $course_id ) ); ?>'><?php echo $quiz['post']->post_title; ?></a>
									</h4>
								</div>						
							<?php endforeach; ?>

						</div>
					</div>
				<?php }
			} 
		?>
	</div>
	<?php } else {
		echo '<div class="lm-tab-content-curriculum-info">';
			esc_html_e( 'Course content is private, only for logged in users. Please login to check course content.', 'reign-learndash-addon' );
		echo '</div>';
	} ?>
<?php endif; ?>
