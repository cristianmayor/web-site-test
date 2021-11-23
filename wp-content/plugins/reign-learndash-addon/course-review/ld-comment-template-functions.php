<?php
/**
 * WooCommerce Template
 *
 * Functions for the templating system.
 *
 * @package  WooCommerce\Functions
 * @version  2.5.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wb_ld_course_comments' ) ) {

	/**
	 * Output the Review comments template.
	 *
	 * @param WP_Comment $comment Comment object.
	 * @param array      $args Arguments.
	 * @param int        $depth Depth.
	 */
	function wb_ld_course_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; // WPCS: override ok.
		$args = array(
			'comment' => $comment,
			'args'    => $args,
			'depth'   => $depth,
		);
		extract( $args ); // @codingStandardsIgnoreLine
		$template = LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'course-review/review.php';
		include( $template );
	}
}

if ( ! function_exists( 'wb_ld_course_review_display_gravatar' ) ) {
	/**
	 * Display the review authors gravatar
	 *
	 * @param array $comment WP_Comment.
	 * @return void
	 */
	function wb_ld_course_review_display_gravatar( $comment ) {
		echo '<div class="wb-ld-comment-author">';
			echo get_avatar( $comment, apply_filters( 'wb_ld_course_review_gravatar_size', '60' ), '' );
		echo '</div>';
	}
}


if ( ! function_exists( 'wb_ld_course_review_display_rating' ) ) {
	/**
	 * Display the reviewers star rating
	 *
	 * @return void
	 */
	function wb_ld_course_review_display_rating() {
		if ( post_type_supports( 'sfwd-courses', 'comments' ) ) {
			// $template = LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'course-review/review-rating.php';
			// include( $template );

			global $comment;
			$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
			// var_dump($rating);

			$count = -1;
		
			if ( 0 < $rating ) {
				$html  = '<div class="star-rating">';
				$html  .= '<div class="wb-ld-review-star-rating" data-rating="'. $rating . '"></div>';
				$html  .= '</div>';

				// $html  = '<div class="star-rating">';
				// $html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';
				// if ( 0 < $count ) {
				// /* translators: 1: rating 2: rating count */
				// $html .= sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count, 'reign-learndash-addon' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>', '<span class="rating">' . esc_html( $count ) . '</span>' );
				// } else {
				// /* translators: %s: rating */
				// $html .= sprintf( esc_html__( 'Rated %s out of 5', 'reign-learndash-addon' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>' );
				// }
				// $html .= '</span>';

				// $html .= '</div>';
			} else {
				$html = '';
			}

			echo apply_filters( 'wb_ld_course_product_get_rating_html', $html, $rating, $count );
		}
	}
}

if ( ! function_exists( 'wb_ld_course_review_display_meta' ) ) {
	/**
	 * Display the review authors meta (name, verified owner, review date)
	 *
	 * @return void
	 */
	function wb_ld_course_review_display_meta() {
		$template = LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'course-review/review-meta.php';
		include( $template );
	}
}

if ( ! function_exists( 'wb_ld_course_display_reviews_overview' ) ) {
	/**
	 * Display the review authors meta (name, verified owner, review date)
	 *
	 * @return void
	 */
	function wb_ld_course_display_reviews_overview() {
		$template = LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'course-review/reviews-overview.php';
		include( $template );
	}
}


if ( ! function_exists( 'wb_ld_course_review_display_comment_text' ) ) {

	/**
	 * Display the review content.
	 */
	function wb_ld_course_review_display_comment_text() {
		echo '<div class="description">';
		comment_text();
		echo '</div>';
	}
}


if ( ! function_exists( 'wb_ld_course_review_display_title' ) ) {

	/**
	 * Display the review title.
	 */
	function wb_ld_course_review_display_title() {
		global $comment;
		$title = get_comment_meta( $comment->comment_ID, 'title', true );
		if ( $title ) {
			echo '<div class="review-title">' . $title . '</div>';
		}
	}
}

/**
 * Get HTML for ratings.
 *
 * @since  3.0.0
 * @param  float $rating Rating being shown.
 * @param  int   $count  Total number of ratings.
 * @return string
 */
function wb_ld_course_get_rating_html( $rating, $count = 0 ) {
	global $comment;
	// $rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
		
	if ( 0 < $rating ) {
		$html  = '<div class="star-rating">';
		$html = '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';
		if ( 0 < $count ) {
		/* translators: 1: rating 2: rating count */
		$html .= sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count, 'reign-learndash-addon' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>', '<span class="rating">' . esc_html( $count ) . '</span>' );
		} else {
		/* translators: %s: rating */
		$html .= sprintf( esc_html__( 'Rated %s out of 5', 'reign-learndash-addon' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>' );
		}
		$html .= '</span>';

		// $html .= wb_ld_course_get_star_rating_html( $rating, $count );
		$html .= '</div>';
	} else {
		$html = '';
	}

	return apply_filters( 'wb_ld_course_product_get_rating_html', $html, $rating, $count );
}

/**
 * Get HTML for star rating.
 *
 * @since  3.1.0
 * @param  float $rating Rating being shown.
 * @param  int   $count  Total number of ratings.
 * @return string
 */
function wb_ld_course_get_star_rating_html( $rating, $count = 0 ) {
	$html = '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';

	if ( 0 < $count ) {
		/* translators: 1: rating 2: rating count */
		$html .= sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count, 'reign-learndash-addon' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>', '<span class="rating">' . esc_html( $count ) . '</span>' );
	} else {
		/* translators: %s: rating */
		$html .= sprintf( esc_html__( 'Rated %s out of 5', 'reign-learndash-addon' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>' );
	}

	$html .= '</span>';

	return apply_filters( 'wb_ld_course_get_star_rating_html', $html, $rating, $count );
}