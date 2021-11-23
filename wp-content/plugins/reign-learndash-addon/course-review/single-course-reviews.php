<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/reign-learndash/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.reign-learndash.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
global $post;
$product = $post;
$comments_count = wp_count_comments( $post->ID );//devendra
$count = $comments_count->approved;//devendra

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="reign-learndash-Reviews">
	<div id="comments">
		<h2 class="reign-learndash-Reviews-title"><?php
			if ( $count ) {
				/* translators: 1: reviews count 2: course name */
				printf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'reign-learndash-addon' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
			} else {
				_e( 'Reviews', 'reign-learndash-addon' );
			}
		?></h2>

		<?php if ( have_comments() ) : ?>

			<?php do_action( 'wb_ld_course_before_comments_list' ); ?>

			<ul class="commentlist wb-ld-course-commentlist">
				<?php wp_list_comments( apply_filters( 'reign-learndash_product_review_list_args', array( 'callback' => 'wb_ld_course_comments' ) ) ); ?>
			</ul>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="reign-learndash-pagination">';
				paginate_comments_links( apply_filters( 'reign-learndash_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="reign-learndash-noreviews"><?php _e( 'There are no reviews yet.', 'reign-learndash-addon' ); ?></p>

		<?php endif; ?>
	</div>

	<?php if ( TRUE || get_option( 'reign-learndash_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
					$commenter = wp_get_current_commenter();

					$comment_form = array(
						'title_reply'          => have_comments() ? __( 'Add a review', 'reign-learndash-addon' ) : sprintf( __( 'Be the first to review &ldquo;%s&rdquo;', 'reign-learndash-addon' ), get_the_title() ),
						'title_reply_to'       => __( 'Leave a Reply to %s', 'reign-learndash-addon' ),
						'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
						'title_reply_after'    => '</span>',
						'comment_notes_after'  => '',
						'fields'               => array(
							'author' => '<p class="comment-form-author">' . '<label for="author">' . esc_html__( 'Name', 'reign-learndash-addon' ) . ' <span class="required">*</span></label> ' .
										'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required /></p>',
							'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'reign-learndash-addon' ) . ' <span class="required">*</span></label> ' .
										'<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required /></p>',
						),
						'label_submit'  => __( 'Submit', 'reign-learndash-addon' ),
						'logged_in_as'  => '',
						'comment_field' => '',
					);

					// if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
					// 	$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'reign-learndash-addon' ), esc_url( $account_page_url ) ) . '</p>';
					// }

					$comment_form['comment_field'] = '<p class="comment-form-comment"><label for="title">' . esc_html__( 'Your title', 'reign-learndash-addon' ) . ' <span class="required">*</span></label><input type="text" id="title" name="title" required="required" /></p>';

					if ( TRUE || get_option( 'reign-learndash_enable_review_rating' ) === 'yes' ) {
						$comment_form['comment_field'] .= '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'reign-learndash-addon' ) . '</label><select name="rating" id="rating" aria-required="true" required="required" style="display:none;">
							<option value="">' . esc_html__( 'Rate&hellip;', 'reign-learndash-addon' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'reign-learndash-addon' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'reign-learndash-addon' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'reign-learndash-addon' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'reign-learndash-addon' ) . '</option>
							<option value="1">' . esc_html__( 'Very poor', 'reign-learndash-addon' ) . '</option>
						</select></div>';
					}

					$comment_form['comment_field'] .= '<div id="wb-ld-course-rate"></div>';

					$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'reign-learndash-addon' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required="required"></textarea></p>';

					comment_form( apply_filters( 'reign-learndash_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>

	<?php else : ?>

		<p class="reign-learndash-verification-required"><?php _e( 'Only logged in customers who have purchased this product may leave a review.', 'reign-learndash-addon' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>