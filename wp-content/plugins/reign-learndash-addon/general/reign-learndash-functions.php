<?php

function learnmate_course_data_tabs() {
	global $wbtm_reign_settings;
	$curriculum_label = sprintf( esc_html_x( '%s Content', 'Course Content Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'course' ) );
	$tabs             = array(
		'description' => array(
			'slug'  => 'description',
			'href'  => '#',
			'label' => esc_html__( 'Description', 'reign-learndash-addon' ),
			'icon'  => 'fa fa-align-left',
		),
		'curriculum'  => array(
			'slug'  => 'curriculum',
			'href'  => '#',
			'label' => $curriculum_label,
			'icon'  => 'fa fa-cube',
		),
	);

	$instructor_tab = ( isset( $wbtm_reign_settings['learndash']['hide_instructor_tab'] ) ) ? $wbtm_reign_settings['learndash']['hide_instructor_tab'] : '';

	if ( ! $instructor_tab ) {
		$tabs['instructors'] = array(
			'slug'  => 'instructors',
			'href'  => '#',
			'label' => esc_html__( 'Instructors', 'reign-learndash-addon' ),
			'icon'  => 'fa fa-user',
		);
	}

	$review_tab = ( isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) ) ? $wbtm_reign_settings['learndash']['hide_review_tab'] : '';

	global $post;
	$user_has_access = false;

	if ( $post ) {
		if ( $post ) {
			$user_has_access = sfwd_lms_has_access( $post->ID, get_current_user_id() );
		}
	}
	if ( ! $user_has_access ) {
		$user_has_access = ( isset( $wbtm_reign_settings['learndash']['guest_reviews'] ) ) ? $wbtm_reign_settings['learndash']['guest_reviews'] : '';
	}
	if ( comments_open() && ! $review_tab && $user_has_access ) {
		$tabs['review'] = array(
			'slug'  => 'review',
			'href'  => '#',
			'label' => esc_html__( 'Review', 'reign-learndash-addon' ),
			'icon'  => 'fa fa-comments',
		);
	}

	return apply_filters( 'learnmate_course_data_tabs', $tabs );
}

function wb_ld_get_course_reviews_analytics( $post_id = null ) {
	if ( is_null( $post_id ) ) {
		global $post;
		$args = array(
			'post_id' => $post->ID, // use post_id, not post_ID
		);
	} else {
		$args = array(
			'post_id' => $post_id, // use post_id, not post_ID
		);
	}

	$comments = get_comments( $args );

	$reviews_analytics = array(
		'1star' => array(
			'label' => esc_html__( '1 star', 'reign-learndash-addon' ),
			'count' => 0,
		),
		'2star' => array(
			'label' => esc_html__( '2 stars', 'reign-learndash-addon' ),
			'count' => 0,
		),
		'3star' => array(
			'label' => esc_html__( '3 stars', 'reign-learndash-addon' ),
			'count' => 0,
		),
		'4star' => array(
			'label' => esc_html__( '4 stars', 'reign-learndash-addon' ),
			'count' => 0,
		),
		'5star' => array(
			'label' => esc_html__( '5 stars', 'reign-learndash-addon' ),
			'count' => 0,
		),
	);
	$reviews_analytics = array_reverse( $reviews_analytics );
	$rating_total      = 0.0;

	foreach ( $comments as $key => $comment ) {
		$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
		if ( ! $rating ) {
			continue;
		}

		$rating_total += $rating;
		if ( $rating && isset( $reviews_analytics[ $rating . 'star' ] ) ) {
			$temp_count = $reviews_analytics[ $rating . 'star' ]['count'];
			$temp_count++;
			$reviews_analytics[ $rating . 'star' ]['count'] = $temp_count;
		} else {
			$reviews_analytics = apply_filters( 'wb_ld_course_review_analytics_exception', $reviews_analytics, $comments, $comment );
		}
	}
	if ( count( $comments ) ) {
		$reviews_average            = round( ( $rating_total / count( $comments ) ), 1 );
		$reviews_average_percentage = ( ( $rating_total / ( 5 * count( $comments ) ) ) * 100 );
	} else {
		$reviews_average            = 0;
		$reviews_average_percentage = 0;
	}

	$course_reviews_analytics = array(
		'total_reviews'      => count( $comments ),
		'reviews_average'    => $reviews_average,
		'reviews_analytics'  => $reviews_analytics,
		'reviews_percentage' => $reviews_average_percentage,
	);

	return $course_reviews_analytics;
}

function get_reign_ld_course_price( $course_id ) {
	$post_id  = $course_id;
	$user_id  = get_current_user_id();
	$options  = get_option( 'sfwd_cpt_options' );
	$currency = null;
	if ( ! is_null( $options ) ) {
		if ( isset( $options['modules'] ) && isset( $options['modules']['sfwd-courses_options'] ) && isset( $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'] ) ) {
			$currency = $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'];
		}
	}
	if ( is_null( $currency ) ) {
		$currency = 'USD';
	}
	$course_options    = get_post_meta( $post_id, '_sfwd-courses', true );
	$price             = $course_options && isset( $course_options['sfwd-courses_course_price'] ) ? $course_options['sfwd-courses_course_price'] : esc_html__( 'Free', 'reign-learndash-addon' );
	$short_description = @$course_options['sfwd-courses_course_short_description'];
	$has_access        = sfwd_lms_has_access( $course_id, $user_id );
	$is_completed      = learndash_course_completed( $user_id, $course_id );
	if ( $price == '' ) {
		$price .= '<span class="lm-course-free">' . esc_html__( 'Free', 'reign-learndash-addon' ) . '</span>';
	}
	if ( is_numeric( $price ) ) {
		if ( $currency == 'USD' ) {
			$price = '<span class="lm-course-paid">' . '$' . $price . '</span>';
		} else {
			$price .= '<span class="lm-course-paid">' . ' ' . $currency . '</span>';
		}
	}
	$price = apply_filters( 'get_reign_ld_course_price', $price, $course_id );
	return $price;
}

function _get_reign_ld_course_price( $course_id ) {
	$course_price_html = '';
	$lms_veriosn       = version_compare( Leandash_Version, '2.6.4' );
	if ( $lms_veriosn >= 0 ) {
		$course_price_type = learndash_get_course_meta_setting( $course_id, 'course_price_type' );
	} else {
		$course_price_type = get_course_meta_setting( $course_id, 'course_price_type' );
	}

	$course_price = '';
	switch ( $course_price_type ) {
		case 'open':
			$course_price      = esc_html__( 'Open', 'reign-learndash-addon' );
			$course_price_html = '<div class="lm-value lm-free-course" itemprop="price">' . $course_price . '</div>';
			break;
		case 'closed':
			$currency = '$';
			if ( $lms_veriosn >= 0 ) {
				$course_price = learndash_get_course_meta_setting( $course_id, 'course_price' );
			} else {
				$course_price = get_course_meta_setting( $course_id, 'course_price' );
			}

			if ( empty( $course_price ) ) {
				$course_price = esc_html__( 'Free', 'reign-learndash-addon' );
			} else {
				$course_price = $currency . $course_price;
			}
			$course_price_html = '<div class="lm-value lm-free-course" itemprop="price">' . $course_price . '</div>';
			break;
		case 'free':
			$course_price      = esc_html__( 'Free', 'reign-learndash-addon' );
			$course_price_html = '<div class="lm-value lm-free-course" itemprop="price">' . $course_price . '</div>';
			break;
		case 'paynow':
			$currency = '$';
			if ( $lms_veriosn >= 0 ) {
				$course_price = learndash_get_course_meta_setting( $course_id, 'course_price' );
			} else {
				$course_price = get_course_meta_setting( $course_id, 'course_price' );
			}
			$course_price      = $currency . $course_price;
			$course_price_html = '<div class="lm-value lm-free-course" itemprop="price">' . $course_price . '</div>';
			break;
		case 'subscribe':
			$currency = '$';
			if ( $lms_veriosn >= 0 ) {
				$course_price            = learndash_get_course_meta_setting( $course_id, 'course_price' );
				$course_price_billing_p3 = learndash_get_course_meta_setting( $course_id, 'course_price_billing_p3' );
			} else {
				$course_price            = get_course_meta_setting( $course_id, 'course_price' );
				$course_price_billing_p3 = get_course_meta_setting( $course_id, 'course_price_billing_p3' );
			}
			$course_price_billing_p3 = get_post_meta( $course_id, 'course_price_billing_p3', true );
			$course_price_billing_t3 = get_post_meta( $course_id, 'course_price_billing_t3', true );
			switch ( $course_price_billing_t3 ) {
				case 'D':
					$course_price_billing_t3 = 'day(s)';
					break;
				case 'W':
					$course_price_billing_t3 = 'week(s)';
					break;
				case 'M':
					$course_price_billing_t3 = 'month(s)';
					break;
				case 'Y':
					$course_price_billing_t3 = 'year(s)';
					break;
				default:
					break;
			}
			$course_price     .= ' / ' . $course_price_billing_p3 . ' ' . $course_price_billing_t3;
			$course_price      = $currency . $course_price;
			$course_price_html = '<div class="lm-value lm-free-course" itemprop="price">subscribe</div>';
			break;
		default:
			$course_price_html = '';
			break;
	}
	$course_price = apply_filters( 'get_reign_ld_course_price', $course_price, $course_id, $course_price_type );
	return $course_price;
}

function get_reign_ld_default_course_img_url() {
	return LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/imgs/default-course-image.png';
}

function get_reign_ld_default_course_img_html() {
	global $wbtm_reign_settings;
	$default_course_img_url = isset( $wbtm_reign_settings['learndash']['default_course_img_url'] ) ? $wbtm_reign_settings['learndash']['default_course_img_url'] : get_reign_ld_default_course_img_url();
	return '<img src="' . $default_course_img_url . '" />';
}

/**
 * Display search form.
 *
 * @since 1.0.0
 *
 * @param bool $echo Default to echo and not return the form.
 * @return string|void String when $echo is false.
 */
function get_reign_ld_course_search_form( $echo = true ) {
	global $wbtm_reign_settings, $wpdb;

	do_action( 'pre_get_reign_ld_course_search_form' );

	$post_type_slug         = 'course';
	$ld_categorydropdown    = '';
	$ld_instructor_dropdown = '';
	if ( isset( $wbtm_reign_settings['learndash']['course_category_filter'] ) && $wbtm_reign_settings['learndash']['course_category_filter'] == 'on' ) {

		// And also let this query be filtered.
		$get_ld_categories_args = array(
			'taxonomy' => 'ld_course_category',
			'type'     => 'course',
			'orderby'  => 'name',
			'order'    => 'ASC',
		);
		$ld_categories          = get_terms( $get_ld_categories_args );
		$ld_categorydropdown    = '<div id="reign-learndash-course-list-category-filters" class="select-wrap"> <select id="ld_' . $post_type_slug . '_categorydropdown_select" name="' . $post_type_slug . '_catid" onChange="jQuery(\'.courses-searching form\').submit()">';
		$ld_categorydropdown   .= '<option value="">' . sprintf(
			// translators: placeholder Category label
			esc_html__( 'All Categories', 'reign-learndash-addon' )
		) . '</option>';

		foreach ( $ld_categories as $ld_category ) {
			$selected             = ( empty( $_GET[ $post_type_slug . '_catid' ] ) || $_GET[ $post_type_slug . '_catid' ] != $ld_category->term_id ) ? '' : 'selected="selected"';
			$ld_categorydropdown .= "<option value='" . $ld_category->term_id . "' " . $selected . '>' . $ld_category->name . '</option>';
		}

		$ld_categorydropdown .= '</select></div>';
	}

	if ( isset( $wbtm_reign_settings['learndash']['course_instructor_filter'] ) && $wbtm_reign_settings['learndash']['course_instructor_filter'] == 'on' ) {
		$args = array(
			'orderby'  => 'user_nicename',
			'role__in' => array( 'administrator', 'ld_instructor', 'wdm_instructor' ),
			'order'    => 'ASC',
			'fields'   => array( 'ID', 'display_name' ),
		);

		$instructors = get_users( $args );

		$ld_instructor_dropdown  = '<div id="reign-learndash-course-list-category-filters" class="select-wrap"> <select id="ld_' . $post_type_slug . '_instructordropdown_select" name="' . $post_type_slug . '_instid" onChange="jQuery(\'.courses-searching form\').submit()">';
		$ld_instructor_dropdown .= '<option value="">' . sprintf(
			// translators: placeholder Category label
			esc_html__( 'All Instructors', 'reign-learndash-addon' )
		) . '</option>';

		foreach ( $instructors as $ld_instructor ) {
			$course_get_sql = "SELECT SQL_CALC_FOUND_ROWS {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta pm6 ON ( {$wpdb->prefix}posts.ID = pm6.post_id ) INNER JOIN {$wpdb->prefix}postmeta pm7 ON ( {$wpdb->prefix}posts.ID = pm7.post_id ) WHERE 1=1 AND ({$wpdb->prefix}posts.post_author = {$ld_instructor->ID} ) AND {$wpdb->prefix}posts.post_type = 'sfwd-courses' AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'graded' OR {$wpdb->prefix}posts.post_status = 'not_graded' OR {$wpdb->prefix}posts.post_status = 'private') AND {$wpdb->prefix}posts.post_author = {$ld_instructor->ID} OR ( ( (pm6.meta_key = '_ld_instructor_ids' AND 			pm6.meta_value REGEXP '.*;s:[0-9]+:\"{$ld_instructor->ID}\"*' ) OR (pm7.meta_key = 'ir_shared_instructor_ids' AND FIND_IN_SET ({$ld_instructor->ID}, pm7.meta_value)) ) AND {$wpdb->prefix}posts.post_type = 'sfwd-courses' AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'graded' OR {$wpdb->prefix}posts.post_status = 'not_graded' OR {$wpdb->prefix}posts.post_status = 'private') ) GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC LIMIT 1";
			$courses        = $wpdb->get_results( $course_get_sql );
			if ( ! empty( $courses ) ) {
				$selected                = ( empty( $_GET[ $post_type_slug . '_instid' ] ) || $_GET[ $post_type_slug . '_instid' ] != $ld_instructor->ID ) ? '' : 'selected="selected"';
				$ld_instructor_dropdown .= "<option value='" . $ld_instructor->ID . "' " . $selected . '>' . $ld_instructor->display_name . '</option>';

			}
		}

		$ld_instructor_dropdown .= '</select></div>';
	}

	$format = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';

	$format               = apply_filters( 'reign_ld_course_search_form_format', $format );
	$course_page_url      = get_post_type_archive_link( 'sfwd-courses' );
	$search_form_template = locate_template( 'reign_ld_course_searchform.php' );
	if ( '' != $search_form_template ) {
		ob_start();
		require $search_form_template;
		$form = ob_get_clean();
	} else {
		if ( 'html5' == $format ) {
			$form = '<form role="search" method="get" class="reign_ld_course_search-form search-form" action="' . esc_url( $course_page_url ) . '">
					' . $ld_categorydropdown . '
					' . $ld_instructor_dropdown . '
				<div class="ld-filter-input-wrap">
					<label>
						<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'reign-learndash-addon' ) . '</span>
						<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'reign-learndash-addon' ) . '" value="' . get_search_query() . '" name="s" />
					</label>
					<input name="post_type" value="sfwd-courses" type="hidden">
					<input type="submit" class="search-submit" value="' . esc_attr_x( 'Search', 'submit button', 'reign-learndash-addon' ) . '" />
				</div>
				<input type="hidden" name="bp_search" class="rld_course_bp_search" value="0">
            </form>';
		} else {
			$form = '<form role="search" method="get" id="reign_ld_course_search-form searchform" class="searchform" action="' . esc_url( $course_page_url ) . '">
				<div id="reign-learndash-course-list-category-filters" class="select-wrap">
					' . $ld_categorydropdown . '
				</div>
				<div id="reign-learndash-course-list-category-filters" class="select-wrap">
					' . $ld_instructor_dropdown . '
				</div>
                <div class="ld-filter-input-wrap">
                    <label class="screen-reader-text" for="s">' . _x( 'Search for:', 'label', 'reign-learndash-addon' ) . '</label>
                    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
                    <input name="post_type" value="sfwd-courses" type="hidden">
                    <input type="submit" id="searchsubmit" value="' . esc_attr_x( 'Search', 'submit button', 'reign-learndash-addon' ) . '" />
                </div>
				<input type="hidden" name="bp_search" class="rld_course_bp_search" value="0">
            </form>';
		}
	}

	$result = apply_filters( 'get_reign_ld_course_search_form', $form );

	if ( null === $result ) {
		$result = $form;
	}

	if ( $echo ) {
		echo $result;
	} else {
		return $result;
	}
}

/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/woocommerce-plugin-templates/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/woocommerce-plugin-templates/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param   string $template_name          Template to load.
 * @param   string $string $template_path  Path to templates.
 * @param   string $default_path           Default path to template files.
 * @return  string                          Path to the template file.
 */
function learnmate_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	if ( ! $template_path ) :
		$template_path = 'reign-learndash-addon/legacy/';
		if ( defined( 'LEARNDASH_LEGACY_THEME' ) ) {
			if ( learndash_is_active_theme( 'ld30' ) ) {
				$template_path = 'reign-learndash-addon/ld30/';
			}
		}
	endif;
	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'learndash/legacy/';
		if ( defined( 'LEARNDASH_LEGACY_THEME' ) ) {
			if ( learndash_is_active_theme( 'ld30' ) ) {
				$default_path = LearnMate_LearnDash_Addon_PLUGIN_DIR_PATH . 'learndash/ld30/'; // Path to the template folder
			}
		}
	endif;
	// Search template file in theme folder.
	$template = locate_template(
		array(
			$template_path . $template_name,
			$template_name,
		)
	);
	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'learnmate_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see learnmate_locate_template()
 *
 * @param string $template_name          Template to load.
 * @param array  $args                   Args passed for the template file.
 * @param string $string $template_path  Path to templates.
 * @param string $default_path           Default path to template files.
 */
function learnmate_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = learnmate_locate_template( $template_name, $tempate_path, $default_path );
	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;
	include $template_file;
}

/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
	$length = 20;
	return $length;
}

// add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

add_filter( 'ld_course_list_shortcode_attr_defaults', 'reign_ld_course_grid_shortcode_attr' );

function reign_ld_course_grid_shortcode_attr( $attr ) {
	$attr['template'] = '';
	return $attr;
}

// When Design upgrade pro learndash plugin activated add body class
add_filter( 'body_class', 'reign_ld30_body_class' );

function reign_ld30_body_class( $classes ) {
	global $wbtm_reign_settings;
	if ( is_plugin_active( 'design-upgrade-pro-learndash/design-upgrade-pro-learndash.php' ) ) {
		$classes[] = 'ld-design-upgrade-pro';
	}
	if ( is_single() && get_post_type() == 'sfwd-courses' ) {
		$course_layout = get_post_meta( get_the_id(), 'rla_course_layout', true );
		if ( $course_layout != '' ) {
			$wbtm_reign_settings['learndash']['course_layout'] = $course_layout;
		}
		$classes[] = 'learndash-course-layout-' . $wbtm_reign_settings['learndash']['course_layout'];
	}
	return $classes;
}

function reign_learndash_get_single_template( $single_template ) {

	global $wpdb, $wp_query, $post, $wbtm_reign_settings;

	if ( LearnDash_Theme_Register::get_active_theme_key() != 'ld30' ) {
		return $single_template;
	}

	/* Check Sigle Course Page layout set default */
	$course_layout = get_post_meta( get_the_id(), 'rla_course_layout', true );
	if ( $course_layout != '' ) {
		$wbtm_reign_settings['learndash']['course_layout'] = $course_layout;
	}

	if ( ! isset( $wbtm_reign_settings['learndash']['course_layout'] ) || isset( $wbtm_reign_settings['learndash']['course_layout'] ) && $wbtm_reign_settings['learndash']['course_layout'] == 'default' ) {
		return $single_template;
	}

	if ( get_post_type() == 'sfwd-courses' ) {
		/* auto detect mobile devices */
		$template = '/single-' . get_post_type() . '.php';

		if ( file_exists( STYLESHEETPATH . $template ) ) {

			$single_template = STYLESHEETPATH . $template;

		} elseif ( file_exists( TEMPLATEPATH . $template ) ) {

			$single_template = TEMPLATEPATH . $template;

		} else {

			if ( file_exists( REIGN_LEARNDASH_ADDON_DIR . 'learndash' . $template ) ) {
				remove_action( 'reign_before_content', array( Reign_Theme_Structure::instance(), 'render_page_header' ) );
				if ( $course_layout != '' ) {
					$course_layout = $course_layout;
				} else {
					if ( isset( $wbtm_reign_settings['learndash']['course_layout'] ) && $wbtm_reign_settings['learndash']['course_layout'] == 'udemy' ) {
						$course_layout = 'udemy';
					}
				}
				if ( $course_layout == 'udemy' ) {
					remove_action( 'reign_post_content_begins', array( Reign_Theme_Structure::instance(), 'render_post_meta_section' ) );
					add_action( 'reign_before_content', 'reign_learndash_single_course_header' );
				}

				$single_template = REIGN_LEARNDASH_ADDON_DIR . 'learndash' . $template;
			}
		}
	}

	return $single_template;
}
add_filter( 'single_template', 'reign_learndash_get_single_template', 13 );

add_filter( 'template_include', 'reign_learndash_get_author_template', 13 );
function reign_learndash_get_author_template( $template ) {

	if ( is_author() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sfwd-courses' ) {
		remove_action( 'reign_before_content', array( Reign_Theme_Structure::instance(), 'render_page_header' ) );

		add_action( 'reign_before_content', 'reign_learndash_wbcom_before_content_section', 9 );
	}
	return $template;
}
function reign_learndash_single_course_header() {
	global $wbtm_reign_settings;

	$breadcrumb = get_theme_mod( 'reign_site_enable_breadcrumb', true );
	if ( ! isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) ) {
		$args                     = array(
			'post_id' => get_the_ID(), // use post_id, not post_ID
		);
		$comments                 = get_comments( $args );
		$course_reviews_analytics = wb_ld_get_course_reviews_analytics( get_the_ID() );
		$reviews_average          = $course_reviews_analytics['reviews_average'];
		$reviews_analytics        = $course_reviews_analytics['reviews_analytics'];
	}
	$students_enrolled = learndash_get_users_for_course( get_the_ID(), array(), true );
	if ( empty( $students_enrolled ) ) {
		$students_enrolled = array();
	} else {
		$query_args        = $students_enrolled->query_vars;
		$students_enrolled = $query_args['include'];
	}

	$description = get_post_meta( get_the_ID(), '_learndash_course_grid_short_description', true );

	$author_id          = array( get_post_field( 'post_author', get_the_ID() ) );
	$_ld_instructor_ids = get_post_meta( get_the_ID(), '_ld_instructor_ids', true );
	if ( empty( $_ld_instructor_ids ) ) {
		$_ld_instructor_ids = array();
	}
	$ir_shared_instructor_ids = get_post_meta( get_the_ID(), 'ir_shared_instructor_ids', true );
	if ( $ir_shared_instructor_ids != '' ) {
		$ir_shared_instructor_ids = explode( ',', $ir_shared_instructor_ids );
	} else {
		$ir_shared_instructor_ids = array();
	}

	$author_ids = array_merge( $author_id, $_ld_instructor_ids, $ir_shared_instructor_ids );
	$author_ids = array_unique( $author_ids );

	?>
	<div class="learndash-single-course-header">
		<div class="container">
			<div class="learndash-single-course-header-inner-wrap">
				<?php if ( $breadcrumb && function_exists( 'reign_breadcrumbs' ) ) : ?>
					<div class="lm-breadcrumbs-wrapper">
						<div class="container"><?php reign_breadcrumbs(); ?></div>
					</div>
				<?php endif; ?>
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<p class="course-header-short-description">
					<?php
					if ( $description != '' ) {
						echo $description;
					} else {
						the_excerpt();
					}
					?>
				</p>
				<?php if ( ! isset( $wbtm_reign_settings['learndash']['hide_review_tab'] ) ) : ?>
					<div class="learndash-course-info">
						<div class="learndash-rating-box">
							<div class="wb-ld-average-value" itemprop="ratingValue"><?php echo $reviews_average; ?></div>
							<div class="wb-ld-review-stars-rated"></div>
							<div class="review-amount" itemprop="ratingCount">
							<?php
							if ( count( $comments ) <= 1 ) {
								echo '(' . count( $comments ) . __( ' rating', 'reign-learndash-addon' ) . ')';
							} else {
								echo '(' . count( $comments ) . __( ' ratings', 'reign-learndash-addon' ) . ')';
							}
							?>
							 </div>
						</div>
						<div class="learndash-course-student-enrollment">
							<?php echo count( $students_enrolled ) . ' ' . ( ( count( $students_enrolled ) > 1 ) ? __( 'students', 'reign-learndash-addon' ) : __( 'student', 'reign-learndash-addon' ) ); ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="learndash-course-instructor">
					<?php
					if ( ! empty( $author_ids ) ) {
						$instructor_image = '';
						$instructor_name  = '';
						$i                = 0;

						remove_filter( 'author_link', 'wpforo_change_author_default_page' );
						foreach ( $author_ids as $insttuctor_id ) {
							$author_avatar_url = get_avatar_url( $insttuctor_id );
							$author_url        = apply_filters( 'lm_filter_course_author_url', get_author_posts_url( $insttuctor_id ), $insttuctor_id );
							$first_name        = get_the_author_meta( 'user_firstname', $insttuctor_id );
							$last_name         = get_the_author_meta( 'user_lastname', $insttuctor_id );
							$author_name       = get_the_author_meta( 'display_name', $insttuctor_id );
							if ( ! empty( $first_name ) && ! empty( $last_name ) && $author_name == '' ) {
								$author_name = $first_name . ' ' . $last_name;
							}
							if ( $i < 3 ) {
								$instructor_image .= '<img alt="instructor avatar" src="' . $author_avatar_url . '" class="lm-author-avatar" width="40" height="40">';
							}
							$instructor_name .= '<a href="' . $author_url . '" target="_blank">' . $author_name . '</a>, ';
							 $i++;
						}
						?>
						<div class="instructor-avatar">
							<?php echo $instructor_image; ?>
						</div>
						<div class="instructor-name">
							<?php echo substr( $instructor_name, 0, -2 ); ?>
						</div>
						<?php
					}
					?>
				</div>
				<div class="last-update-date">
					<span class="last-update-date_icon">
						<i class="fas fa-certificate"></i>
					</span>
					<span><?php echo sprintf( __( 'Last updated %s', 'reign-learndash-addon' ), the_modified_date( '', '', '', false ) ); ?> </span>
				</div>
			</div>
		</div>
	</div>
	<?php
}
