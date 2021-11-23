<?php
$author_id = get_the_author_meta( 'ID' );
$course_id = learndash_get_course_id( get_the_ID() );
$first_name        = get_the_author_meta( 'user_firstname', $author_id );
$last_name         = get_the_author_meta( 'user_lastname', $author_id );
$author_name       = get_the_author_meta( 'display_name', $author_id );
if ( ! empty( $first_name ) ) {
	$author_name = $first_name . ' ' . $last_name;
}
remove_filter( 'author_link', 'wpforo_change_author_default_page' );
$author_url = apply_filters( 'lm_filter_course_author_url', get_author_posts_url( $author_id ) );
$author_avatar_url = get_avatar_url( $author_id );

$course_price = get_reign_ld_course_price( $course_id );		
global $wbtm_reign_settings;
$review_tab = ( isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) )?$wbtm_reign_settings['learndash']['hide_review_tab']:'';
?>

<div class="lm-course-item-wrapper lm-course-item-<?php echo $course_id; ?>">
	<div class="lm-course-item">
		<div class="lm-course-thumbnail">
			<?php
			$render_course_thumbnail = true;
			if( defined( 'LEARNDASH_COURSE_GRID_FILE' ) ) {
				$enable_video = get_post_meta( $course_id, '_learndash_course_grid_enable_video_preview', true );
				$embed_code = get_post_meta( $course_id, '_learndash_course_grid_video_embed_code', true );
				// Retrive oembed HTML if URL provided
				if ( preg_match( '/^http/', $embed_code ) ) {
					$embed_code = wp_oembed_get( $embed_code, array( 'height' => 600, 'width' => 400 ) );
				}
				if ( 1 == $enable_video && ! empty( $embed_code ) ) {
					echo $embed_code;
					$render_course_thumbnail = false;
				}
			}
			if( $render_course_thumbnail ) {
				if( has_post_thumbnail() ) {
					the_post_thumbnail();
				}
				else {
					echo get_reign_ld_default_course_img_html();
				}
			}
			?>
			<a class="button lm-course-readmore-button lm-course-grid-view-data" href="<?php echo learndash_get_step_permalink( get_the_ID(), $course_id ); ?>" title="<?php echo the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php _e( 'Read More', 'reign-learndash-addon' ); ?></a>
		</div>
		<div class="lm-course-content">
			<div class="lm-course-author lm-course-grid-view-data" itemscope="" itemtype="http://schema.org/Person">
				<a href="<?php echo $author_url; ?>">
					<img alt="Admin bar avatar" src="<?php echo $author_avatar_url; ?>" class="lm-author-avatar" width="40" height="40">
				</a>
				<div class="author-contain">
					<label itemprop="jobTitle"><?php _e( 'Instructor', 'reign-learndash-addon' ); ?></label>
					<div class="lm-value" itemprop="name">
						<a href="<?php echo $author_url; ?>">
							<?php echo $author_name; ?>
						</a>
					</div>
				</div>
			</div>
			<?php
			the_title( '<h2 class="lm-course-title"><a href="' . learndash_get_step_permalink( get_the_ID(), $course_id ) . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h2>' );
			?>


			<div class="lm-course-meta">
				<div class="lm-course-author lm-course-list-view-data" itemscope="" itemtype="http://schema.org/Person">
					<a href="<?php echo $author_url; ?>">
						<img alt="Admin bar avatar" src="<?php echo $author_avatar_url; ?>" class="lm-author-avatar" width="40" height="40">
					</a>
					<div class="author-contain">
						<label itemprop="jobTitle"><?php _e( 'Instructor', 'reign-learndash-addon' ); ?></label>
						<div class="lm-value" itemprop="name">
							<a href="<?php echo $author_url; ?>">
								<?php echo $author_name; ?>
							</a>
						</div>
					</div>
				</div>
				
				<?php
				$course_reviews_analytics = wb_ld_get_course_reviews_analytics( $course_id );
				if( class_exists( 'LD_Course_Review_Manager') && !$review_tab && comments_open( $course_id ) ) :
					$reviews_percentage = 0;
					if ( isset( $course_reviews_analytics['reviews_percentage'] ) ) {
						$reviews_percentage = $course_reviews_analytics['reviews_percentage'];
					}
					?>
					<div class="lm-course-review lm-course-list-view-data">
						<label><?php _e( 'Review', 'reign-learndash-addon' ); ?></label>
						<div class="lm-value">
							<div class="lm-review-stars-rated">
								<ul class="lm-review-stars">
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
								</ul>
								<ul class="lm-review-stars lm-filled" style="width: <?php echo $reviews_percentage; ?>%">
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
								</ul>
							</div>
							<span>(<?php echo $course_reviews_analytics['total_reviews']; ?> <?php _e( 'reviews', 'reign-learndash-addon' ); ?>)</span>
						</div>
					</div>
				<?php endif; ?>

				<div class="lm-course-students">
					<label><?php _e( 'Students', 'reign-learndash-addon' ); ?></label>
					<div class="lm-value">
						<i class="fa fa-group"></i>
						<?php
						$course_user_ids = learndash_get_users_for_course( $course_id, array(), true );
						if( empty( $course_user_ids ) ) {
							$course_user_ids = array();
						}
						else {
							$query_args = $course_user_ids->query_vars;
							$course_user_ids = $query_args['include'];
						}
						echo count( $course_user_ids );
						?>
					</div>
				</div>
				<?php if( class_exists( 'LD_Course_Review_Manager') && !$review_tab && comments_open( $course_id ) ) : ?>
					<div class="lm-course-comments-count lm-course-grid-view-data">
						<label><?php _e( 'Comment', 'reign-learndash-addon' ); ?></label>
						<div class="lm-value">
							<i class="fa fa-comment"></i><?php echo $course_reviews_analytics['total_reviews']; ?>
						</div>
					</div>
			    <?php endif; ?>

				<div class="lm-course-price lm-course-grid-view-data" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
					<div class="lm-value">
						<?php echo $course_price; ?>
					</div>
					<meta itemprop="priceCurrency" content="$">
				</div>
			</div>

			<div class="lm-course-description lm-course-list-view-data">
				<?php 
					$course_options    = get_post_meta( $course_id, "_sfwd-courses", true );
					$short_description = @$course_options['sfwd-courses_course_short_description'];
					if( !empty( $short_description ) ) { 
						echo htmlspecialchars_decode( do_shortcode( $short_description ) ); 							
				    } else {
				    	if ( ! is_singular( 'sfwd-courses' ) ) {
							the_excerpt();
						}
					}
				?>
			</div>
			<div class="lm-course-price lm-course-list-view-data" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
				<div class="lm-value">
					<?php echo $course_price; ?>
				</div>
				<meta itemprop="priceCurrency" content="$">
			</div>
			<div class="lm-course-readmore lm-course-list-view-data">
				<a class="button lm-course-readmore-button" href="<?php echo learndash_get_step_permalink( get_the_ID(), $course_id ); ?>" title="<?php echo the_title_attribute( 'echo=0' ); ?>" rel="bookmark"><?php _e( 'Read More', 'reign-learndash-addon' ); ?></a>
			</div>
		</div>
	</div>
</div>