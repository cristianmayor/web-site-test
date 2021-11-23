<?php
/**
 * Displays a course
 *
 * Available Variables:
 * $course_id       : (int) ID of the course
 * $course      : (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id         : Current User ID
 * $logged_in       : User is logged in
 * $current_user    : (object) Currently logged in user object
 *
 * $course_status   : Course Status
 * $has_access  : User has access to course or is enrolled.
 * $materials       : Course Materials
 * $has_course_content      : Course has course content
 * $lessons         : Lessons Array
 * $quizzes         : Quizzes Array
 * $lesson_progression_enabled  : (true/false)
 * $has_topics      : (true/false)
 * $lesson_topics   : (array) lessons topics
 *
 * @since 3.0
 *
 * @package LearnDash\Course
 */

$has_lesson_quizzes = learndash_30_has_lesson_quizzes( $course_id, $lessons ); 

?>    
 <div class="<?php echo esc_attr( learndash_the_wrapper_class() ); ?>">

    <?php
    global $course_pager_results, $wbtm_reign_settings;
	/**
	 * Action to add custom content before the topic
	 *
	 * @since 3.0
	 */
	do_action( 'learndash-course-before', get_the_ID(), $course_id, $user_id );

	/**
	 * Action to add custom content before the course certificate link
	 *
	 * @since 3.0
	 */
	do_action( 'learndash-course-certificate-link-before', $course_id, $user_id );

    

	/**
	 * Certificate link
	 *
	 *
	 */

	if( $course_certficate_link && !empty($course_certficate_link) ):
		learndash_get_template_part( 'modules/alert.php', array(
			'type'      =>  'success ld-alert-certificate',
			'icon'      =>  'certificate',
			'message'   =>  __( 'You\'ve earned a certificate!', 'learndash' ),
			'button'    =>  array(
				'url'   =>  $course_certficate_link,
				'icon'  =>  'download',
				'label' =>  __( 'Download Certificate', 'learndash' ),
				'target' => '_new',
			)
		), true );

	endif;

	/**
	 * Action to add custom content after the course certificate link
	*
	* @since 3.0
	*/
	do_action( 'learndash-course-certificate-link-after', $course_id, $user_id );
	$course_layout = get_post_meta( $course_id, 'rla_course_layout', true );
	if ( $course_layout != '' ) {
		$wbtm_reign_settings['learndash']['course_layout'] = $course_layout;
	}
	
	if ( ( !isset($wbtm_reign_settings['learndash']['course_layout']) || isset($wbtm_reign_settings['learndash']['course_layout']) && $wbtm_reign_settings['learndash']['course_layout'] == 'default' ) || $has_access) {

		/**
		 * Course info bar
		 *
		 */
		learndash_get_template_part( 'modules/infobar.php', array(
				'context'       => 'course',
				'course_id'     => $course_id,
				'user_id'       => $user_id,
				'has_access'    => $has_access,
				'course_status' => $course_status,
				'post'          => $post
			), true );
		
	}

	/**
	 * Filter to add custom content after the Course Status section of the Course template output.
	 *
	 * @since 2.3
	 * See https://bitbucket.org/snippets/learndash/7oe9K for example use of this filter.
	 */
	echo apply_filters( 'ld_after_course_status_template_container', '', learndash_course_status_idx( $course_status ), $course_id, $user_id );
    

    do_action( 'reign-course-ld30-before-tabs', $course_id, $user_id );  

    

	/**
	 * Content tabs
	 *
	 */
	learndash_get_template_part( 'modules/tabs.php', array(
		 'course_id' => $course_id,
		 'post_id'   => get_the_ID(),
		 'user_id'   => $user_id,
		 'content'   => $content,
		 'materials' => $materials,
		 'context'   => 'course'
	 ), true );
    

    /**
     * Action to add custom content before the topic
     *
     * @since 3.0
     */
    do_action( 'learndash-course-after', get_the_ID(), $course_id, $user_id );
    learndash_load_login_modal_html();
    //learndash_get_template_part( 'modules/login-modal.php', array(), true ); 
    ?>

</div>
<?php do_action( 'reign_learndash_after_course_content' ); ?>
