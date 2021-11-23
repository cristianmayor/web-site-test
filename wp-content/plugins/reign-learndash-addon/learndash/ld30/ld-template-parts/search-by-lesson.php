<?php
return;
if ( is_user_logged_in() )
	$user_id = get_current_user_id();
else
	$user_id = 0;
$course_id = learndash_get_course_id();
if ( ! empty( $course_id ) ) {
	$course = get_post( $course_id );
	$has_access = sfwd_lms_has_access( $course_id, $user_id );
}
$lessons = learndash_get_course_lessons_list( $course_id );
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

print_r($lessons);
print_r($quizzes);

?>

<select name="sdsds">
	<option>1</option>
	<option>1</option>
	<option>1</option>
	<option>1</option>
	<option>1</option>
	<option>1</option>
</select>