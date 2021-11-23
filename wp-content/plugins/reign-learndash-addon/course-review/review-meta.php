<?php
/**
 * The template to display the reviewers meta data (name, verified owner, review date)
 *
 * This template can be overridden by copying it to yourtheme/reign-learndash/single-product/review-meta.php.
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
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $comment;
// $verified = wc_review_is_from_verified_owner( $comment->comment_ID );
$verified = FALSE;

if ( '0' === $comment->comment_approved ) { ?>

	<p class="meta"><em class="reign-learndash-review__awaiting-approval"><?php esc_attr_e( 'Your review is awaiting approval', 'reign-learndash-addon' ); ?></em></p>

<?php } else { ?>

	<p class="meta">
		<strong class="reign-learndash-review__author"><?php comment_author(); ?></strong> <?php

		if ( 'yes' === get_option( 'reign-learndash_review_rating_verification_label' ) && $verified ) {
			echo '<em class="reign-learndash-review__verified verified">(' . esc_attr__( 'verified owner', 'reign-learndash-addon' ) . ')</em> ';
		}

		?><span class="reign-learndash-review__dash">&ndash;</span> <small class="reign-learndash-review__published-date" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( get_option( 'date_format' ) ); ?></small>
	</p>

<?php }
