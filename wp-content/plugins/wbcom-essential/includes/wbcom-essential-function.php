<?php

/**
 * Gets and includes template files.
 *
 * @since 3.0.0
 * @param mixed  $template_name
 * @param array  $args (default: array()).
 * @param string $template_path (default: '').
 * @param string $default_path (default: '').
 */
function wbcom_essential_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- Please, forgive us.
		extract( $args );
	}

	include wbcom_essential_locate_template( $template_name, $template_path, $default_path );
}

/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 *
 * @since 3.0.0
 *
 * @param   string $template_name          Template to load.
 * @param   string $string $template_path  Path to templates.
 * @param   string $default_path           Default path to template files.
 * @return  string                          Path to the template file.
 */
function wbcom_essential_locate_template( $template_name, $template_path, $default_path = '' ) {
	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template.
	if ( ! $template && false !== $default_path ) {
		$default_path = $default_path ? $default_path : WBCOM_ESSENTIAL_PATH . 'templates/';
		if ( file_exists( trailingslashit( $default_path . $template_path ) . $template_name ) ) {
			$template = trailingslashit( $default_path . $template_path ) . $template_name;
		}
	}
	return apply_filters( 'wbcom_essential_locate_template', $template, $template_name, $template_path, $default_path );
}

function _is_theme_active( $theme ) {
	$current_theme = wp_get_theme(); // gets the current theme
	if ( $theme == $current_theme->name || $theme == $current_theme->parent_theme ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Get column class
 *
 * @param $type
 * @param string $viewport
 *
 * @return mixed|string
 */
function _get_column_class( $type, $viewport = '' ) {

	$classes = array(
		'one'   => 'one',
		'two'   => 'two',
		'three' => 'three',
		'four'  => 'four',
	);

	if ( $viewport === 'tablet' ) {
		return 'md-' . $classes[ $type ];
	}

	if ( $viewport === 'mobile' ) {
		return 'sm-' . $classes[ $type ];
	}

	return $classes[ $type ];
}
