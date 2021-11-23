<?php
/**
 * The template to display the reviewers star rating in reviews
 *
 * This template can be overridden by copying it to yourtheme/reign-learndash/review-rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.reign-learndash.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $comment;
$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
if ( TRUE || $rating && 'yes' === get_option( 'reign-learndash_enable_review_rating' ) ) {
	// echo wb_ld_course_get_rating_html( $rating );
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