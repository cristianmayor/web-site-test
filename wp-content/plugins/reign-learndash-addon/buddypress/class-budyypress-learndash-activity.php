<?php
/**
 * BuddyPress Learndash Activity Functions.
 *
 * These functions handle the recording, deleting and formatting of activity
 * for the user and for this specific component.
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Register activity actions for the Groups component.
 *
 * @return false|null False on failure.
 */
function bp_learndash_register_activity_actions() {
	$bp = buddypress();
	bp_activity_set_action(
		$bp->groups->id,
		'user_enrolled_course',
		__( 'Started a course', 'reign-learndash-addon' ),
		'reign_bp_ld_format_course_enrolled_avtivity',
		__( 'Started Course', 'reign-learndash-addon' ),
		array( 'activity', 'member', 'member_groups', 'group' )
	);

	// bp_activity_set_action(
	// $bp->groups->id,
	// 'user_created_lesson',
	// __( 'Created a lesson', 'reign-learndash-addon' ),
	// 'reign_bp_ld_format_created_lesson',
	// __( 'New Lessons', 'reign-learndash-addon' ),
	// array( 'activity', 'member', 'member_groups', 'group' )
	// );

	bp_activity_set_action(
		$bp->groups->id,
		'user_completed_lesson',
		__( 'Completed a lesson', 'reign-learndash-addon' ),
		'reign_bp_ld_format_completed_lesson',
		__( 'Lesson Complete', 'reign-learndash-addon' ),
		array( 'activity', 'member', 'member_groups', 'group' )
	);

	bp_activity_set_action(
		$bp->groups->id,
		'user_completed_topic',
		__( 'Completed a topic', 'reign-learndash-addon' ),
		'reign_bp_ld_format_completed_topic',
		__( 'Topic Complete', 'reign-learndash-addon' ),
		array( 'activity', 'member', 'member_groups', 'group' )
	);

	bp_activity_set_action(
		$bp->groups->id,
		'user_completed_course',
		__( 'Completed a course', 'reign-learndash-addon' ),
		'reign_bp_ld_format_completed_course',
		__( 'Course Complete', 'reign-learndash-addon' ),
		array( 'activity', 'member', 'member_groups', 'group' )
	);

	bp_activity_set_action(
		$bp->groups->id,
		'user_commented_on_lesson',
		__( 'Lesson comment', 'reign-learndash-addon' ),
		'reign_bp_ld_format_lesson_comment',
		__( 'Lesson Comment', 'reign-learndash-addon' ),
		array( 'activity', 'member', 'member_groups', 'group' )
	);

	bp_activity_set_action(
		$bp->groups->id,
		'user_commented_on_course',
		__( 'Course comment', 'reign-learndash-addon' ),
		'reign_bp_ld_action_course_comment',
		__( 'Course Comment', 'reign-learndash-addon' ),
		array( 'activity', 'member', 'member_groups', 'group' )
	);

	bp_activity_set_action(
		$bp->groups->id,
		'user_completed_quiz',
		__( 'Completed a quiz', 'reign-learndash-addon' ),
		'reign_bp_ld_format_completed_quiz',
		__( 'Quiz Completed', 'reign-learndash-addon' ),
		array( 'activity', 'member', 'member_groups', 'group' )
	);

}

add_action( 'groups_register_activity_actions', 'bp_learndash_register_activity_actions' );

/**
 * Format 'user_created_lesson' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
// function reign_bp_ld_format_created_lesson( $action, $activity ) {
//
// $user_link        = bp_core_get_userlink( $activity->user_id );
// $lesson_id        = $activity->secondary_item_id;
// $lesson_title     = get_the_title( $lesson_id );
// $lesson_link      = get_permalink( $lesson_id );
// $course_id        = learndash_get_course_id( $lesson_id );
// $lesson_link_html = '<a href="' . esc_url( $lesson_link ) . '">' . $lesson_title . '</a>';
// $course_title     = get_the_title( $course_id );
// $course_link      = get_permalink( $course_id );
// $course_link_html = '<a href="' . esc_url( $course_link ) . '">' . $course_title . '</a>';
//
// $action = sprintf( __( '%1$s added the %2$s %3$s to the %4$s %5$s', 'reign-learndash-addon' ), $user_link, LearnDash_Custom_Label::label_to_lower( 'lesson' ), $lesson_link_html, LearnDash_Custom_Label::label_to_lower( 'course' ), $course_link_html );
//
// return apply_filters( 'reign_bp_ld_format_created_lesson', $action, $activity );
// }

/**
 * Format 'created_topic' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
function reign_bp_ld_format_activity_action_created_topic( $action, $activity ) {

	$user_link        = bp_core_get_userlink( $activity->user_id );
	$topic_id         = $activity->secondary_item_id;
	$topic_title      = get_the_title( $topic_id );
	$topic_link       = get_permalink( $topic_id );
	$topic_link_html  = '<a href="' . esc_url( $topic_link ) . '">' . $topic_title . '</a>';
	$lesson_id        = learndash_get_lesson_id( $topic_id );
	$lesson_title     = get_the_title( $lesson_id );
	$lesson_link      = get_permalink( $lesson_id );
	$lesson_link_html = '<a href="' . esc_url( $lesson_link ) . '">' . $lesson_title . '</a>';

	$action = sprintf( __( '%1$s added the %2$s %3$s to the %4$s %5$s', 'reign-learndash-addon' ), $user_link, LearnDash_Custom_Label::label_to_lower( 'topic' ), $topic_link_html, LearnDash_Custom_Label::get_label( 'lesson' ), $lesson_link_html );

	return apply_filters( 'reign_bp_ld_format_activity_action_created_topic', $action, $activity );
}

/**
 * Format 'completed_lesson' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
function reign_bp_ld_format_completed_lesson( $action, $activity ) {

	$user_link        = bp_core_get_userlink( $activity->user_id );
	$lesson_id        = $activity->secondary_item_id;
	$lesson_title     = get_the_title( $lesson_id );
	$lesson_link      = get_permalink( $lesson_id );
	$lesson_link_html = '<a href="' . esc_url( $lesson_link ) . '">' . $lesson_title . '</a>';

	$action = sprintf( __( '%1$s completed the %2$s %3$s', 'reign-learndash-addon' ), $user_link, LearnDash_Custom_Label::label_to_lower( 'lesson' ), $lesson_link_html );

	return apply_filters( 'reign_bp_ld_format_completed_lesson', $action, $activity );
}

/**
 * Format 'completed_course' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
function reign_bp_ld_format_completed_topic( $action, $activity ) {

	$user_link       = bp_core_get_userlink( $activity->user_id );
	$topic_id        = $activity->secondary_item_id;
	$topic_title     = get_the_title( $topic_id );
	$topic_link      = get_permalink( $topic_id );
	$topic_link_html = '<a href="' . esc_url( $topic_link ) . '">' . $topic_title . '</a>';

	$action = sprintf( __( '%1$s completed the %2$s %3$s', 'reign-learndash-addon' ), $user_link, LearnDash_Custom_Label::label_to_lower( 'topic' ), $topic_link_html );

	return apply_filters( 'reign_bp_ld_format_completed_topic', $action, $activity );
}

/**
 * Format 'completed_topic' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
function reign_bp_ld_format_completed_course( $action, $activity ) {

	$user_link        = bp_core_get_userlink( $activity->user_id );
	$course_id        = $activity->secondary_item_id;
	$course_title     = get_the_title( $course_id );
	$course_link      = get_permalink( $course_id );
	$course_link_html = '<a href="' . esc_url( $course_link ) . '">' . $course_title . '</a>';

	$action = sprintf( __( '%1$s completed the course %2$s', 'reign-learndash-addon' ), $user_link, $course_link_html );

	return apply_filters( 'reign_bp_ld_format_completed_course', $action, $activity );
}

/**
 * Format 'lesson_comment' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
function reign_bp_ld_format_lesson_comment( $action, $activity ) {

	$user_link        = bp_core_get_userlink( $activity->user_id );
	$post_id          = $activity->secondary_item_id;
	$lesson_title     = get_the_title( $post_id );
	$lesson_link      = get_permalink( $post_id );
	$lesson_link_html = '<a href="' . esc_url( $lesson_link ) . '">' . $lesson_title . '</a>';

	$action = sprintf( __( '%1$s commented on %2$s %3$s', 'reign-learndash-addon' ), $user_link, LearnDash_Custom_Label::label_to_lower( 'lesson' ), $lesson_link_html );

	return apply_filters( 'reign_bp_ld_format_lesson_comment', $action, $activity );
}

/**
 * Format 'course_comment' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
function reign_bp_ld_action_course_comment( $action, $activity ) {

	$user_link        = bp_core_get_userlink( $activity->user_id );
	$post_id          = $activity->secondary_item_id;
	$course_title     = get_the_title( $post_id );
	$course_link      = get_permalink( $post_id );
	$course_link_html = '<a href="' . esc_url( $course_link ) . '">' . $course_title . '</a>';

	$action = sprintf( __( '%1$s commented on course %2$s', 'reign-learndash-addon' ), $user_link, $course_link_html );

	return apply_filters( 'reign_bp_ld_action_course_comment', $action, $activity );
}

/**
 * Format 'completed_quiz' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
function reign_bp_ld_format_completed_quiz( $action, $activity ) {

	$user_link      = bp_core_get_userlink( $activity->user_id );
	$quiz_id        = $activity->secondary_item_id;
	$quizzes        = get_user_meta( $activity->user_id, '_sfwd-quizzes', true );
	$key            = array_search( $quiz_id, array_column( $quizzes, 'quiz' ) );
	$quiz_grade     = $quizzes[ $key ]['score'];
	$quiz_title     = get_the_title( $quiz_id );
	$quiz_link      = get_permalink( $quiz_id );
	$quiz_link_html = '<a href="' . esc_url( $quiz_link ) . '">' . $quiz_title . '</a>';

	$action = sprintf( __( '%1$s has passed the %2$s %3$s with score %4$s', 'reign-learndash-addon' ), $user_link, $quiz_link_html, LearnDash_Custom_Label::label_to_lower( 'quiz' ), $quiz_grade );

	return apply_filters( 'reign_bp_ld_format_completed_quiz', $action, $activity );
}

/**
 * Format 'started_course' activity actions.
 *
 * @param $action
 * @param $activity
 * @return mixed|void
 */
function reign_bp_ld_format_course_enrolled_avtivity( $action, $activity ) {

	$course_id        = $activity->secondary_item_id;
	$user_link        = bp_core_get_userlink( $activity->user_id );
	$course_title     = get_the_title( $course_id );
	$course_link      = get_permalink( $course_id );
	$course_link_html = '<a href="' . esc_url( $course_link ) . '">' . $course_title . '</a>';

	$action = sprintf( __( '%1$s enrolled in course %2$s', 'reign-learndash-addon' ), $user_link, $course_link_html );

	return apply_filters( 'reign_bp_ld_format_course_enrolled_avtivity', $action, $activity );
}
