<?php

// $users_list = learndash_get_users_for_course( $course_id = learndash_get_course_id(), $query_args = array(), $exclude_admin = true );
// print_r($users_list);
?>
<?php
global $post;
$args = array(
	'post_id' => $post->ID, // use post_id, not post_ID
);
$comments = get_comments( $args );
$course_reviews_analytics = wb_ld_get_course_reviews_analytics( $post->ID );
$reviews_average = $course_reviews_analytics['reviews_average'];
$reviews_analytics = $course_reviews_analytics['reviews_analytics'];
?>
<div class="course-rating">
    <div class="average-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
        <div class="rating-box-wrapper">
            <p class="rating-title"><?php _e( 'Average Rating', 'reign-learndash-addon' ); ?></p>
            <div class="rating-box">
                <div class="wb-ld-average-value" itemprop="ratingValue"><?php echo $reviews_average; ?></div>
                <div class="wb-ld-review-stars-rated"></div>
                <div class="review-amount" itemprop="ratingCount">
                <?php 
                if (count( $comments ) <= 1) {
                        echo "(".count( $comments ). __( ' rating', 'reign-learndash-addon' ) . ")"; 
                } else {
                        echo "(".count( $comments ). __( ' ratings', 'reign-learndash-addon' ) . ")"; 
                }?>
                </div>
            </div>
        </div>
        <div class="detailed-rating">
            <p class="rating-title"><?php _e( 'Detailed Rating', 'reign-learndash-addon' ); ?></p>
            <div class="rating-box">
                    <div class="detailed-rating">
                            <?php foreach ( $reviews_analytics as $review ) {
                                    $percentage = ( ( $review['count'] / count( $comments ) ) * 100 )
                                    ?>
                                    <div class="stars">
                                            <div class="key"><?php echo $review['label']; ?></div>
                                            <div class="bar">
                                                    <div class="full_bar">
                                                            <div style="width: <?php echo $percentage; ?>%"></div>
                                                    </div>
                                            </div>
                                            <div class="value"><?php echo $review['count']; ?></div>
                                    </div>
                                    <?php
                            }
                            ?>
                    </div>
            </div>
        </div>
    </div>
</div>