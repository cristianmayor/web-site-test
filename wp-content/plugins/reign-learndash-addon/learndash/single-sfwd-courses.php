<?php
global $wbtm_reign_settings;
get_header();

?>

<div class="content-wrapper">
<?php
while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content', get_post_format() );

	$args = array(
		'prev_text' => '<span class="rg-next-prev">' . __( 'Previous', 'reign' ) . '	</span><span class="nav-title">%title</span>',
		'next_text' => '<span class="rg-next-prev">' . __( 'Next', 'reign' ) . '</span><span class="nav-title">%title</span>',
	);
	the_post_navigation( $args );

	// do_action( 'reign_single_post_comment_section' );


	endwhile; // End of the loop.
?>
</div>
<div id="reign-sidebar-right" class="widget-area learndash-course-widget" role="complementary">
	<div class="widget-area-inner">
			<div class="learndash-course-widget-wrap">
		<?php
		$course_info = array();
		$course_id   = learndash_get_course_id( get_the_ID() );
		$course      = get_post( $course_id );

		$rla_ccf_enable   = get_post_meta( $course_id, 'rla_ccf_enable', true );
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
			$course_info['certificate'] = esc_html__( 'Yes', 'reign-learndash-addon' );
		} else {
			$course_info['certificate'] = esc_html__( 'No', 'reign-learndash-addon' );
		}

		$students_enrolled = learndash_get_users_for_course( $course_id, array(), true );
		$query_args        = ! empty( $students_enrolled ) ? $students_enrolled->query_vars : array();
		$students          = array();
		if ( isset( $query_args['include'] ) && ! empty( $query_args['include'] ) ) {
			$students = $students_enrolled->get_results();
		}
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

		$_learndash_course_grid_video_embed_code = get_post_meta( $course_id, '_learndash_course_grid_video_embed_code', true );
		if ( $_learndash_course_grid_video_embed_code != '' ) :
			echo '<div class="lm-course-thumbnail">';
			echo wp_oembed_get( $_learndash_course_grid_video_embed_code );
			echo '</div>';
		else :
			if ( has_post_thumbnail( $course_id ) ) {
				echo '<div class="lm-course-thumbnail">';
				echo get_the_post_thumbnail( $course_id );
				echo '</div>';
			} else {
				echo '<div class="lm-course-thumbnail">';
				echo get_reign_ld_default_course_img_html();
				echo '</div>';
			}
		endif;
		if ( ! empty( $students ) ) :
			?>
			<div class="lm-course-students-wrap">
				<?php
				$i = 0;
				foreach ( $students as $student ) :
					if ( $i == 5 ) {
						break;
					}

					$student_avatar_url = get_avatar_url( $student );
					?>
					<img alt="student avatar" src="<?php echo $student_avatar_url; ?>" class="lm-student-avatar" width="40" height="40">
					<?php
					$i++;
				endforeach;
				if ( count( $students ) > 5 ) {
					echo '<span>+' . count( $students ) . '&nbsp;' . esc_html__( 'enrolled', 'reign-learndash-addon' ) . '</span>';
				}
				?>
			</div>
			<?php
		endif;

		/**
		 * Course info bar
		 */
		learndash_get_template_part(
			'modules/infobar.php',
			array(
				'context'       => 'course',
				'course_id'     => $course_id,
				'user_id'       => $user_id,
				'has_access'    => sfwd_lms_has_access( $course_id, $user_id ),
				'course_status' => learndash_course_status( $course_id, $user_id ),
				'post'          => $post,
			),
			true
		);

		echo do_shortcode( '[ld_course_resume course_id ="' . $course_id . '" user_id ="' . $user_id . '" label="' . esc_html__( 'Continue', 'reign-learndash-addon' ) . '"]' );


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

		if ( $rla_ccf_enable == 'yes' ) {
			for ( $i = 0; $i < sizeof( $rla_ccf_features['icon'] ); $i++ ) {
				?>
				<li class="<?php echo $course_feature['slug']; ?>">
					<i class="<?php echo $rla_ccf_features['icon'][ $i ]; ?>"></i>
					<span class="lm-course-feature-value"><?php echo $rla_ccf_features['text'][ $i ]; ?></span>
				</li>
				<?php
			}
		} else {
			foreach ( $course_features as $course_feature ) {
				?>
				<li class="<?php echo $course_feature['slug']; ?>">
					<i class="<?php echo $course_feature['icon']; ?>"></i>
					<span class="lm-course-feature-label"><?php echo $course_feature['label']; ?></span>
					<span class="lm-course-feature-value"><?php echo $course_feature['value']; ?></span>
				</li>
				<?php
			}
		}
		echo '</ul>';
		echo '</div>';
		?>
			</div>
	</div>
</div>
<?php
get_footer();
