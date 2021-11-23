<?php
/**
 * @package nmbs
 */
$col   = empty( $shortcode_atts['col'] ) ? LEARNDASH_COURSE_GRID_COLUMNS :intval( $shortcode_atts['col'] );
$col   = $col > 6 ? 6 : $col;
$smcol = $col == 1 ? 1 : $col / 2;
$col   = 12 / $col;
$smcol = intval( ceil( 12 / $smcol ) );
$col   = is_float( $col ) ? number_format( $col, 1 ) : $col;
$col   = str_replace( '.', '-', $col );

global $post; $post_id = $post->ID;

$course_id = $post_id;
$user_id   = get_current_user_id();

$enable_video = get_post_meta( $post->ID, '_learndash_course_grid_enable_video_preview', true );
$embed_code   = get_post_meta( $post->ID, '_learndash_course_grid_video_embed_code', true );
$button_text  = get_post_meta( $post->ID, '_learndash_course_grid_custom_button_text', true );

// Retrive oembed HTML if URL provided
if ( preg_match( '/^http/', $embed_code ) ) {
	$embed_code = wp_oembed_get( $embed_code, array( 'height' => 600, 'width' => 400 ) );
}

if ( isset( $shortcode_atts['course_id'] ) ) {
	$button_link = learndash_get_step_permalink( get_the_ID(), $shortcode_atts['course_id'] );
} else {
	$button_link = get_permalink();
}

$button_link = apply_filters( 'learndash_course_grid_custom_button_link', $button_link, $post_id );

$button_text = isset( $button_text ) && ! empty( $button_text ) ? $button_text : __( 'See more...', 'learndash-course-grid' );
$button_text = apply_filters( 'learndash_course_grid_custom_button_text', $button_text, $post_id );

$options = get_option( 'sfwd_cpt_options' );
$currency_setting = class_exists( 'LearnDash_Settings_Section' ) ? LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Section_PayPal', 'paypal_currency' ) : null;
$currency = '';

if ( isset( $currency_setting ) || ! empty( $currency_setting ) ) {
	$currency = $currency_setting;
} elseif ( isset( $options['modules'] ) && isset( $options['modules']['sfwd-courses_options'] ) && isset( $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'] ) ) {
	$currency = $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'];
}

if ( class_exists( 'NumberFormatter' ) ) {
	
	$locale = get_locale();
	$number_format = new NumberFormatter( $locale . '@currency=' . $currency, NumberFormatter::CURRENCY );
	$currency = $number_format->getSymbol( NumberFormatter::CURRENCY_SYMBOL );
}

/**
 * Currency symbol filter hook
 * 
 * @param string $currency Currency symbol
 * @param int    $course_id
 */
$currency = apply_filters( 'learndash_course_grid_currency', $currency, $course_id );

$course_options = get_post_meta($post_id, "_sfwd-courses", true);
$price = $course_options && isset($course_options['sfwd-courses_course_price']) ? $course_options['sfwd-courses_course_price'] : __( 'Free', 'learndash-course-grid' );
$price_type = $course_options && isset( $course_options['sfwd-courses_course_price_type'] ) ? $course_options['sfwd-courses_course_price_type'] : '';
$short_description = @$course_options['sfwd-courses_course_short_description'];

/**
 * Filter: individual grid class
 * 
 * @param int 	$course_id Course ID
 * @param array $course_options Course options
 * @var string
 */
$grid_class = apply_filters( 'learndash_course_grid_class', '', $course_id, $course_options );

$has_access   = sfwd_lms_has_access( $course_id, $user_id );
$is_completed = learndash_course_completed( $user_id, $course_id );

$price_text = '';

if ( is_numeric( $price ) && ! empty( $price ) ) {
	$price_format = apply_filters( 'learndash_course_grid_price_text_format', '{currency}{price}' );

	$price_text = str_replace(array( '{currency}', '{price}' ), array( $currency, $price ), $price_format );
} elseif ( is_string( $price ) && ! empty( $price ) ) {
	$price_text = $price;
} elseif ( empty( $price ) ) {
	$price_text = __( 'Free', 'learndash-course-grid' );
}

$class       = 'ld_course_grid_price';
$ribbon_text = get_post_meta( $post->ID, '_learndash_course_grid_custom_ribbon_text', true );
$ribbon_text = isset( $ribbon_text ) && ! empty( $ribbon_text ) ? $ribbon_text : '';

if ( $has_access && ! $is_completed && $price_type != 'open' && empty( $ribbon_text ) ) {
	$class .= ' ribbon-enrolled';
	$ribbon_text = __( 'Enrolled', 'learndash-course-grid' );
} elseif ( $has_access && $is_completed && $price_type != 'open' && empty( $ribbon_text ) ) {
	$class .= '';
	$ribbon_text = __( 'Completed', 'learndash-course-grid' );
} elseif ( $price_type == 'open' && empty( $ribbon_text ) ) {
	if ( is_user_logged_in() && ! $is_completed ) {
		$class .= ' ribbon-enrolled';
		$ribbon_text = __( 'Enrolled', 'learndash-course-grid' );
	} elseif ( is_user_logged_in() && $is_completed ) {
		$class .= '';
		$ribbon_text = __( 'Completed', 'learndash-course-grid' );
	} else {
		$class .= ' ribbon-enrolled';
		$ribbon_text = '';
	}
} elseif ( $price_type == 'closed' && empty( $price ) ) {
	$class .= ' ribbon-enrolled';

	if ( is_numeric( $price ) ) {
		$ribbon_text = $price_text;
	} else {
		$ribbon_text = '';
	}
} else {
	if ( empty( $ribbon_text ) ) {
		$class .= ! empty( $course_options['sfwd-courses_course_price'] ) ? ' price_' . $currency : ' free';
		$ribbon_text = $price_text;
	} else {
		$class .= ' custom';
	}
}

/**
 * Filter: individual course ribbon text
 *
 * @param string $ribbon_text Returned ribbon text
 * @param int    $course_id   Course ID
 * @param string $price_type  Course price type
 */
$ribbon_text = apply_filters( 'learndash_course_grid_ribbon_text', $ribbon_text, $course_id, $price_type );

if ( '' == $ribbon_text ) {
	$class = '';
}

/**
 * Filter: individual course ribbon class names
 *
 * @param string $class     	 Returned class names
 * @param int    $course_id 	 Course ID
 * @param array  $course_options Course's options
 * @var string
 */
$class = apply_filters( 'learndash_course_grid_ribbon_class', $class, $course_id, $course_options );

$thumb_size = isset( $shortcode_atts['thumb_size'] ) && ! empty( $shortcode_atts['thumb_size'] ) ? $shortcode_atts['thumb_size'] : 'course-thumb';

global $wbtm_reign_settings;
$review_tab = ( isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) )?$wbtm_reign_settings['learndash']['hide_review_tab']:'';
$course_id   = learndash_get_course_id( get_the_ID() );
$author_id   = get_the_author_meta( 'ID' );
$first_name  = get_the_author_meta( 'user_firstname', $author_id );
$last_name   = get_the_author_meta( 'user_lastname', $author_id );
$author_name = get_the_author_meta( 'display_name', $author_id );
if ( ! empty( $first_name ) ) {
	$author_name = $first_name . ' ' . $last_name;
}
remove_filter( 'author_link', 'wpforo_change_author_default_page' );
$author_url = apply_filters( 'lm_filter_course_author_url', get_author_posts_url( $author_id ) );

$author_avatar_url = get_avatar_url( $author_id );
$course_price      = get_reign_ld_course_price( $course_id );
$thumb_act_inact   = ( $shortcode_atts['show_thumbnail'] == 'true' ) ? 'thumb_active' : 'thumb_inactive';
?>
<div class="ld_course_grid col-sm-<?php echo $smcol; ?> col-md-<?php echo $col; ?> <?php echo esc_attr( $grid_class ); ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'thumbnail course lm-course-item-wrapper' ); ?>>
		<div class="lm-course-item <?php echo $thumb_act_inact; ?>">
			<?php if ( $shortcode_atts['show_thumbnail'] == 'true' ) : ?>		

				<?php if ( $post->post_type == 'sfwd-courses' ) : ?>
				<div class="<?php echo esc_attr( $class ); ?>">
					<?php echo esc_attr( $ribbon_text ); ?>
				</div>
				<?php endif; ?>

				<?php if ( 1 == $enable_video && ! empty( $embed_code ) ) : ?>
				<div class="ld_course_grid_video_embed">
				<?php echo $embed_code; ?>
				</div>
				<?php elseif( has_post_thumbnail() ) : ?>
				<div class="lm-course-thumbnail">
					<a href="<?php the_permalink(); ?>" rel="bookmark">
						<?php the_post_thumbnail( $thumb_size ); ?>
					</a>
					<!-- Read More Link Added :: Start -->
					<a class="button lm-course-readmore-button lm-course-grid-view-data" href="<?php echo learndash_get_step_permalink( get_the_ID(), $course_id ); ?>" title="<?php echo the_title_attribute( 'echo=0' ); ?>" rel="bookmark">
						<?php esc_html_e( 'Read More', 'reign-learndash-addon' ); ?>
					</a>
					<!-- Read More Link Added :: End -->
				</div>	
				<?php else :?>
				<div class="lm-course-thumbnail">	
					<a href="<?php the_permalink(); ?>" rel="bookmark">
						<img alt="" src="<?php echo plugins_url( 'no_image.jpg', REIGN_LEARNDASH_ADDON_FILE); ?>"/>
						<!-- Read More Link Added :: Start -->
						<a class="button lm-course-readmore-button lm-course-grid-view-data" href="<?php echo learndash_get_step_permalink( get_the_ID(), $course_id ); ?>" title="<?php echo the_title_attribute( 'echo=0' ); ?>" rel="bookmark">
							<?php esc_html_e( 'Read More', 'reign-learndash-addon' ); ?>
						</a>
						<!-- Read More Link Added :: End -->
					</a>
				</div>
				<?php endif;?>
			<?php endif; ?>

			<div class="lm-course-content">
				<!-- Course Author Info :: End -->
				<div class="lm-course-author lm-course-grid-view-data" itemscope="" itemtype="http://schema.org/Person">
					<a href="<?php echo $author_url; ?>">
						<img alt="Admin bar avatar" src="<?php echo $author_avatar_url; ?>" class="lm-author-avatar" width="40" height="40">
					</a>
					<div class="author-contain">
						<div class="lm-value" itemprop="name">
							<a href="<?php echo $author_url; ?>">
								<?php echo $author_name; ?>
							</a>
						</div>
					</div>
				</div>
				<!-- Course Author Info :: End -->

				<?php if ( $shortcode_atts['show_content'] == 'true' ) : ?>				
					<div class="caption">
						<?php the_title( '<h2 class="lm-course-title"><a href="' . learndash_get_step_permalink( get_the_ID(), $course_id ) . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h2>' ); ?>
						<p class="entry-content">
							<?php if( !empty( $short_description ) ) { 
								echo htmlspecialchars_decode( do_shortcode( $short_description ) ); 							
						    } else {
								the_excerpt();
							} ?>							
						</p>
						<?php if ( isset( $shortcode_atts['progress_bar'] ) && $shortcode_atts['progress_bar'] == 'true' ) : ?>
						<p><?php echo do_shortcode( '[learndash_course_progress course_id="' . get_the_ID() . '" user_id="' . get_current_user_id() . '"]' ); ?></p>
						<?php endif; ?>
					</div><!-- .entry-header -->
				<?php endif; ?>

				<!-- Course Meta Info :: Start -->
				<div class="lm-course-meta">
					
					<div class="lm-course-students">
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

					<?php
					if( class_exists( 'LD_Course_Review_Manager') && !$review_tab && comments_open( $course_id ) ) {
						$course_reviews_analytics = wb_ld_get_course_reviews_analytics( $course_id );
						?>
						<div class="lm-course-comments-count lm-course-grid-view-data">
							<div class="lm-value">
								<i class="fa fa-comment"></i>
								<?php echo $course_reviews_analytics['total_reviews']; ?>
							</div>
						</div>
						<?php
					}
					?>

					<div class="lm-course-price lm-course-grid-view-data" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
						<div class="lm-value">
							<?php echo $course_price; ?>
						</div>
						<meta itemprop="priceCurrency" content="$">
					</div>

				</div>
				<!-- Course Author Info :: End -->
			</div>	
		</div>	
	</article><!-- #post-## -->
</div>