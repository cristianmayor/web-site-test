<?php

function reign_get_theme_version() {
	// Get theme data
	$theme = wp_get_theme( get_template() );

	// Return theme version
	return $theme->get( 'Version' );
}

function reign_get_default_page_header_image() {
	// return '';
	return REIGN_THEME_URI . '/lib/images/default-header-image.jpg';
}

function reign_header_topbar_default_info_links() {
	$reign_header_topbar_default_info_links = array(
		array(
			'link_text' => esc_attr__( 'Call Us Today! 1.555.555.555', 'reign' ),
			'link_icon' => '<i class="fa fa-phone"></i>',
			'link_url'  => '',
		),
		array(
			'link_text' => esc_attr__( 'support@wbcomdesigns.com', 'reign' ),
			'link_icon' => '<i class="fa fa-envelope"></i>',
			'link_url'  => 'mailto:support@wbcomdesigns.com',
		),
	);
	$reign_header_topbar_default_info_links = apply_filters( 'reign_header_topbar_default_info_links', $reign_header_topbar_default_info_links );
	return $reign_header_topbar_default_info_links;
}

function reign_header_topbar_default_social_links() {
	$reign_header_topbar_default_social_links = array(
		array(
			'link_text' => esc_attr__( 'Facebook', 'reign' ),
			'link_icon' => '<i class="fa fa-facebook"></i>',
			'link_url'  => '#',
		),
		array(
			'link_text' => esc_attr__( 'Twitter', 'reign' ),
			'link_icon' => '<i class="fa fa-twitter"></i>',
			'link_url'  => '#',
		),
		array(
			'link_text' => esc_attr__( 'LinkedIn', 'reign' ),
			'link_icon' => '<i class="fa fa-linkedin"></i>',
			'link_url'  => '#',
		),
		array(
			'link_text' => esc_attr__( 'Dribbble', 'reign' ),
			'link_icon' => '<i class="fa fa-dribbble"></i>',
			'link_url'  => '#',
		),
		array(
			'link_text' => esc_attr__( 'Github', 'reign' ),
			'link_icon' => '<i class="fa fa-github"></i>',
			'link_url'  => '#',
		),
	);
	$reign_header_topbar_default_social_links = apply_filters( 'reign_header_topbar_default_social_links', $reign_header_topbar_default_social_links );
	return $reign_header_topbar_default_social_links;
}

function reign_header_default_icons_set() {
	$reign_header_default_icons_set = array(
		'search',
		'cart',
		'message',
		'notification',
		'user-menu',
		'login',
		'register-menu',
	);
	$reign_header_default_icons_set = apply_filters( 'reign_header_default_icons_set', $reign_header_default_icons_set );
	return $reign_header_default_icons_set;
}

function reign_mobile_header_default_icons_set() {
	$reign_mobile_header_default_icons_set = array(
		'message',
		'notification',
		'login',
		'register-menu',
	);
	$reign_mobile_header_default_icons_set = apply_filters( 'reign_mobile_header_default_icons_set', $reign_mobile_header_default_icons_set );
	return $reign_mobile_header_default_icons_set;
}

/**
 * Returns the correct sidebar ID
 *
 * @since  1.0.4
 */
function reign_get_sidebar_id_to_show( $sidebar_location = 'primary_sidebar' ) {
	$theme_slug = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
	global $wp_query;
	if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
		$post_id = get_option( 'page_for_posts' );
		$post    = get_post( $post_id );
	} else {
		global $post;
	}
	if ( $post ) {
		$wbcom_metabox_data = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
		$sidebar_id         = isset( $wbcom_metabox_data['layout'][ $sidebar_location ] ) ? $wbcom_metabox_data['layout'][ $sidebar_location ] : '';
		$site_layout        = isset( $wbcom_metabox_data['layout']['site_layout'] ) ? $wbcom_metabox_data['layout']['site_layout'] : '';
		if ( $site_layout != 'both_sidebar' ) {

		}
		// if( ( $site_layout == 'both_sidebar' ) && ( $sidebar_location == 'secondary_sidebar' ) ) {
		// return false;
		// }
		if ( ! empty( $sidebar_id ) && ( $sidebar_id != '0' ) ) {
			return $sidebar_id;
		}
	}
	return false;
}

/** altering Wbcom Essential setting slug as per theme name */
add_filter(
	'wbcom_essential_theme_slug',
	function () {
		$theme_info = wp_get_theme();
		// Get parent theme name
		$reflection = new ReflectionClass( $theme_info );
		$property   = $reflection->getProperty( 'parent' );
		$property->setAccessible( true );
		$parent = $property->getValue( $theme_info );
		if ( $parent ) {
			$theme_info = $property->getValue( $theme_info );
		} else {
			$reflection = new ReflectionClass( $theme_info );
			$property   = $reflection->getProperty( 'headers' );
			$property->setAccessible( true );
			$theme_info = $property->getValue( $theme_info );
		}
		return strtolower( $theme_info['Name'] );
	},
	10,
	1
);

// Breadcrumbs
function reign_breadcrumbs() {

	$alter_reign_breadcrumbs = apply_filters( 'alter_reign_breadcrumbs', false );
	if ( $alter_reign_breadcrumbs ) {
		do_action( 'reign_breadcrumbs' );
		return;
	}

	$wpseo_titles = get_option( 'wpseo_titles' );
	if ( function_exists( 'yoast_breadcrumb' ) && isset( $wpseo_titles['breadcrumbs-enable'] ) && $wpseo_titles['breadcrumbs-enable'] == 1 ) {

		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );

	} else {

		// Settings
		$separator        = '&gt;';
		$breadcrums_id    = 'breadcrumbs';
		$breadcrums_class = 'breadcrumbs';
		$home_title       = esc_html__( 'Homepage', 'reign' );

		/* managed */
		$separator  = '<i class="fa fa-angle-double-right"></i>';
		$home_title = esc_html__( 'Home', 'reign' );
		$prefix     = '';
		/* managed */

		// If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
		$custom_taxonomy = 'product_cat';

		// Get the query & post information
		global $post, $wp_query;

		// Do not display on the homepage
		if ( ! is_front_page() ) {

			// Build the breadcrums
			echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';

			// Home page
			echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
			echo '<li class="separator separator-home"> ' . $separator . ' </li>';

			if ( is_archive() && ! is_tax() && ! is_category() && ! is_tag() ) {

				echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title( $prefix, false ) . '</strong></li>';
			} elseif ( is_archive() && is_tax() && ! is_category() && ! is_tag() ) {

				// If post is a custom post type
				$post_type = get_post_type();

				// If it is a custom post type display name and link
				if ( $post_type != 'post' ) {

					$post_type_object  = get_post_type_object( $post_type );
					$post_type_archive = get_post_type_archive_link( $post_type );

					echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
					echo '<li class="separator"> ' . $separator . ' </li>';
				}

				$custom_tax_name = get_queried_object()->name;
				echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';
			} elseif ( is_single() ) {

				// If post is a custom post type
				$post_type = get_post_type();

				if ( $post_type == 'page' && get_query_var( 'post_type' ) ) {
					$post_type = get_query_var( 'post_type' );
				}

				// If it is a custom post type display name and link
				if ( $post_type != 'post' ) {

					$post_type_object  = get_post_type_object( $post_type );
					$post_type_archive = get_post_type_archive_link( $post_type );

					echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
					echo '<li class="separator"> ' . $separator . ' </li>';
				}

				// Get post category info
				$category = get_the_category();

				if ( ! empty( $category ) ) {

					// Get last category post is in
					// $last_category = end(array_values($category));
					/* managed */
					$category_values = array_values( $category );
					$category_values = end( $category_values );
					$last_category   = $category_values;
					/* managed */

					// Get parent any categories and create array
					$get_cat_parents = rtrim( get_category_parents( $last_category->term_id, true, ',' ), ',' );
					$cat_parents     = explode( ',', $get_cat_parents );

					// Loop through parent categories and store in variable $cat_display
					$cat_display = '';
					foreach ( $cat_parents as $parents ) {
						$cat_display .= '<li class="item-cat">' . $parents . '</li>';
						$cat_display .= '<li class="separator"> ' . $separator . ' </li>';
					}
				}

				// If it's a custom post type within a custom taxonomy
				$taxonomy_exists = taxonomy_exists( $custom_taxonomy );
				if ( empty( $last_category ) && ! empty( $custom_taxonomy ) && $taxonomy_exists ) {

					$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
					if ( is_array( $taxonomy_terms ) ) {
						if ( 'uncategorized' !== $taxonomy_terms[0]->slug ) {
							$cat_id       = $taxonomy_terms[0]->term_id;
							$cat_nicename = $taxonomy_terms[0]->slug;
							$cat_link     = get_term_link( $taxonomy_terms[0]->term_id, $custom_taxonomy );
							$cat_name     = $taxonomy_terms[0]->name;
						} else {
							if ( isset( $taxonomy_terms[1] ) ) {
								$cat_id       = $taxonomy_terms[1]->term_id;
								$cat_nicename = $taxonomy_terms[1]->slug;
								$cat_link     = get_term_link( $taxonomy_terms[1]->term_id, $custom_taxonomy );
								$cat_name     = $taxonomy_terms[1]->name;
							}
						}
					}
				}

				// Check if the post is in a category
				if ( ! empty( $last_category ) ) {
					echo $cat_display;
					echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

					// Else if post is in a custom taxonomy
				} elseif ( ! empty( $cat_id ) ) {

					echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
					echo '<li class="separator"> ' . $separator . ' </li>';
					echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
				} else {
					$post_title = get_the_title();
					$post_title = strip_tags( $post_title );
					echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . $post_title . '">' . $post_title . '</strong></li>';
				}
			} elseif ( is_category() ) {

				// Category page
				echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title( '', false ) . '</strong></li>';
			} elseif ( is_page() ) {

				// Standard page
				if ( $post->post_parent ) {

					// If child page, get parents
					$anc = get_post_ancestors( $post->ID );

					// Get parents in the right order
					$anc = array_reverse( $anc );

					// Parent page loop
					if ( ! isset( $parents ) ) {
						$parents = null;
					}
					foreach ( $anc as $ancestor ) {
						$parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink( $ancestor ) . '" title="' . get_the_title( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></li>';
						$parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
					}

					// Display parent pages
					echo $parents;

					// Current page
					echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';
				} else {

					// Just display current page if not parents
					echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';
				}
			} elseif ( is_tag() ) {

				// Tag page
				// Get tag information
				$term_id       = get_query_var( 'tag_id' );
				$taxonomy      = 'post_tag';
				$args          = 'include=' . $term_id;
				$terms         = get_terms( $taxonomy, $args );
				$get_term_id   = $terms[0]->term_id;
				$get_term_slug = $terms[0]->slug;
				$get_term_name = $terms[0]->name;

				// Display the tag name
				echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';
			} elseif ( is_day() ) {

				// Day archive
				// Year link
				echo '<li class="item-year item-year-' . get_the_time( 'Y' ) . '"><a class="bread-year bread-year-' . get_the_time( 'Y' ) . '" href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( 'Y' ) . '">' . get_the_time( 'Y' ) . ' Archives</a></li>';
				echo '<li class="separator separator-' . get_the_time( 'Y' ) . '"> ' . $separator . ' </li>';

				// Month link
				echo '<li class="item-month item-month-' . get_the_time( 'm' ) . '"><a class="bread-month bread-month-' . get_the_time( 'm' ) . '" href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" title="' . get_the_time( 'M' ) . '">' . get_the_time( 'M' ) . ' Archives</a></li>';
				echo '<li class="separator separator-' . get_the_time( 'm' ) . '"> ' . $separator . ' </li>';

				// Day display
				echo '<li class="item-current item-' . get_the_time( 'j' ) . '"><strong class="bread-current bread-' . get_the_time( 'j' ) . '"> ' . get_the_time( 'jS' ) . ' ' . get_the_time( 'M' ) . ' Archives</strong></li>';
			} elseif ( is_month() ) {

				// Month Archive
				// Year link
				echo '<li class="item-year item-year-' . get_the_time( 'Y' ) . '"><a class="bread-year bread-year-' . get_the_time( 'Y' ) . '" href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( 'Y' ) . '">' . get_the_time( 'Y' ) . ' Archives</a></li>';
				echo '<li class="separator separator-' . get_the_time( 'Y' ) . '"> ' . $separator . ' </li>';

				// Month display
				echo '<li class="item-month item-month-' . get_the_time( 'm' ) . '"><strong class="bread-month bread-month-' . get_the_time( 'm' ) . '" title="' . get_the_time( 'M' ) . '">' . get_the_time( 'M' ) . ' Archives</strong></li>';
			} elseif ( is_year() ) {

				// Display year archive
				echo '<li class="item-current item-current-' . get_the_time( 'Y' ) . '"><strong class="bread-current bread-current-' . get_the_time( 'Y' ) . '" title="' . get_the_time( 'Y' ) . '">' . get_the_time( 'Y' ) . ' Archives</strong></li>';
			} elseif ( is_author() ) {

				// Auhor archive
				// Get the author information
				global $author;
				$userdata = get_userdata( $author );

				// Display author name
				echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';
			} elseif ( get_query_var( 'paged' ) ) {

				// Paginated archives
				echo '<li class="item-current item-current-' . get_query_var( 'paged' ) . '"><strong class="bread-current bread-current-' . get_query_var( 'paged' ) . '" title="Page ' . get_query_var( 'paged' ) . '">' . __( 'Page', 'reign' ) . ' ' . get_query_var( 'paged' ) . '</strong></li>';
			} elseif ( is_search() ) {

				// Search results page
				echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';
			} elseif ( is_404() ) {

				// 404 page
				echo '<li>' . 'Error 404' . '</li>';
			}

			if ( is_author() ) {

				$author_id = get_query_var( 'author' );
				if ( $author_id ) {
					$author = get_user_by( 'id', $author_id );
					if ( ! empty( get_user_meta( $author_id, 'first_name', true ) ) ) {
						$author_name = get_user_meta( $author_id, 'first_name', true ) . ' ' . get_user_meta( $author_id, 'last_name', true );
					} else {
						$author_info = get_userdata( $author_id );
						$author_name = $author_info->data->user_login;
					}

					// Author page
					echo '<li>' . $author_name . '</li>';
				}
			}

			if ( is_home() ) {
				echo '<li>' . __( 'Blog', 'reign' ) . '</li>';
			}

			do_action( 'reign_breadcrumbs_last_element' );

			echo '</ul>';
		}
	}
}

add_action( 'init', 'reign_setup_global_settings_variable', 0 );

function reign_setup_global_settings_variable() {
	global $wbtm_reign_settings;
	$wbtm_reign_settings = get_option( 'reign_options', array() );
}

add_action( 'wp_enqueue_scripts', 'enqueue_fav_new_section_style' );

function enqueue_fav_new_section_style() {

	$css_path = is_rtl() ? '/assets/css/rtl' : '/assets/css';

	if ( is_plugin_active( 'edd-sell-services/edd-sell-services.php' ) ) {
		wp_register_style(
			'edd-sell-services-css',
			get_template_directory_uri() . $css_path . '/edd-sell-services.css',
			array(),
			time(),
			'all'
		);
		wp_enqueue_style( 'edd-sell-services-css' );
	}
}

/**
 * show avatars of user who liked a particular activity on activity directory
 * feature like facebook
 *
 * @since 1.0.7
 */
add_action( 'bp_activity_entry_content', 'reign_show_activity_like_avatars' );

function reign_show_activity_like_avatars() {
	$activity_id = bp_get_activity_id();
	global $wpdb;
	$query = 'SELECT user_id FROM ' . $wpdb->base_prefix . "usermeta WHERE meta_key = 'bp_favorite_activities' AND (meta_value LIKE '%:$activity_id;%' OR meta_value LIKE '%:\"$activity_id\";%') ";
	$users = $wpdb->get_results( $query, ARRAY_A );
	if ( ! empty( $users ) && is_array( $users ) ) {
		$num_of_avatar_count  = apply_filters( 'reign_show_activity_like_avatars_count', 3 );
		$num_of_listing_count = apply_filters( 'reign_show_activity_like_listing_count', 5 );
		echo '<div class="wbtm_fav_avatar_listing">';

		// for ($i=0; $i < 20; $i++) {
		// $users[$i]['user_id'] = 1;
		// }
		foreach ( $users as $counter => $user ) {
			$user_id = $user['user_id'];
			$avatar  = bp_core_fetch_avatar(
				array(
					'item_id' => $user_id,
					'object'  => 'user',
					'type'    => 'thumb',
				)
			);
			if ( ( $counter + 1 ) <= $num_of_avatar_count ) {
				?>
				<div class="rtm-tooltip">
					<?php echo $avatar; ?>
					<span class="rtm-tooltiptext">
						<?php echo $user_link = bp_core_get_userlink( $user_id ); ?>
					</span>
				</div>
				<?php
			} elseif ( ( $counter + 1 ) <= ( $num_of_avatar_count + $num_of_listing_count ) ) {
				if ( $counter == $num_of_avatar_count ) {
					?>
					<div class="rtm-tooltip">
						<span class="round-fav-counter">
							+<?php echo ( count( $users ) - $num_of_avatar_count ); ?>
						</span>
						<span class="rtm-tooltiptext">
							<ul class="wbtm-rest-member-list">
								<?php
				}
							$user_link = bp_core_get_userlink( $user_id );
							echo '<li>' . $user_link . '</li>';
				if ( ( ( $counter + 1 ) == ( $num_of_avatar_count + $num_of_listing_count ) ) ) {
					echo '<li>+' . ( count( $users ) - ( $counter + 1 ) ) . __( 'others', 'reign' ) . '</li>';
					?>
							</ul>
						</span>
					</div>
					<?php
				}
			}
			?>
			<?php
		}
		echo '<span class="wbtm-likes-this">' . __( 'likes this', 'reign' ) . '</span>';
		echo '</div>';
	}
}

if ( ! function_exists( 'reign_profile_achievements' ) ) {

    /**
     * Output badges on profile
     */
    function reign_profile_achievements() {
        if ( class_exists( 'BadgeOS' ) ) {
            global $blog_id, $post;
            $type = "all";
            $limit = apply_filters('reign_user_badges_limit', 10);
            $offset = 0;
            $count = 0;
            $filter = "completed";
            $search = false;
            $orderby = "menu_order";
            $order = "ASC";
            $wpms = false;
            $include = array();
            $exclude = array();
            $meta_key = '';
            $meta_value = '';
            $old_post = $post;
            $user_id = bp_displayed_user_id();
            // Convert $type to properly support multiple achievement types
            if ('all' == $type) {
                $type = badgeos_get_achievement_types_slugs();
                // Drop steps from our list of "all" achievements
                $step_key = array_search('step', $type);
                if ($step_key)
                    unset($type[$step_key]);
            } else {
                $type = explode(',', $type);
            }
            // Build $include array
            if (!is_array($include)) {
                $include = explode(',', $include);
            }
            // Build $exclude array
            if (!is_array($exclude)) {
                $exclude = explode(',', $exclude);
            }
            // Initialize our output and counters
            $achievements = '';
            $achievement_count = 0;
            $query_count = 0;
            // Grab our hidden badges (used to filter the query)
            $hidden = badgeos_get_hidden_achievement_ids($type);
            // If we're polling all sites, grab an array of site IDs
            if ($wpms && $wpms != 'false') {
                $sites = badgeos_get_network_site_ids();
            } else {
                // Otherwise, use only the current site
                $sites = array($blog_id);
            }
            // Loop through each site (default is current site only)
            foreach ($sites as $site_blog_id) {
                // If we're not polling the current site, switch to the site we're polling
                if ($blog_id != $site_blog_id) {
                    switch_to_blog($site_blog_id);
                }
                // Grab our earned badges (used to filter the query)
                $earned_ids = badgeos_get_user_earned_achievement_ids($user_id, $type);
                // Query Achievements
                $args = array(
                    'post_type' => $type,
                    'orderby' => $orderby,
                    'order' => $order,
                    'posts_per_page' => $limit,
                    'offset' => $offset,
                    'post_status' => 'publish',
                    'post__not_in' => array_diff($hidden, $earned_ids)
                );
                // Filter - query completed or non completed achievements
                if ($filter == 'completed') {
                    $args['post__in'] = array_merge(array(0), $earned_ids);
                } elseif ($filter == 'not-completed') {
                    $args['post__not_in'] = array_merge($hidden, $earned_ids);
                }
                if ('' !== $meta_key && '' !== $meta_value) {
                    $args['meta_key'] = $meta_key;
                    $args['meta_value'] = $meta_value;
                }
                // Include certain achievements
                if (!empty($include)) {
                    $args['post__not_in'] = array_diff($args['post__not_in'], $include);
                    $args['post__in'] = array_merge(array(0), array_diff($include, $args['post__in']));
                }
                // Exclude certain achievements
                if (!empty($exclude)) {
                    $args['post__not_in'] = array_merge($args['post__not_in'], $exclude);
                }
                // Search
                if ($search) {
                    $args['s'] = $search;
                }
                // Loop Achievements
                $achievement_posts = new WP_Query($args);
                $query_count += $achievement_posts->found_posts;
                while ($achievement_posts->have_posts()) : $achievement_posts->the_post();
                    // If we were given an ID, get the post
                    if (is_numeric(get_the_ID())) {
                        $achievement = get_post(get_the_ID());
                    }
                    $achievements .= '<div class="ps-badgeos__item ps-badgeos__item--focus" >';
                    $achievements .= '<a href="' . get_permalink($achievement->ID) . '">' . badgeos_get_achievement_post_thumbnail($achievement->ID) . '</a>';
                    $achievements .= '</div>';
                    $achievement_count++;
                endwhile;
                wp_reset_query();
                $post = $old_post;
            }
            echo '<div class="ps-badgeos__list-wrapper">';
            echo '<div class="ps-badgeos__list-title">' . _n('Recently earned badge', 'Recently earned badges', $achievement_count, 'reign') . '</div>';
            echo '<div class="ps-badgeos__list">' . $achievements . '</div>';
            echo '</div>';
        }
    }
}