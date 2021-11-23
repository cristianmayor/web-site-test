<?php
/**
 * WBCom Essential Elementor Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

define( 'ELEMENTOR_WBCOMESSENTIAL__FILE__', __FILE__ );
define( 'ELEMENTOR_WBCOMESSENTIAL__DIR__', __DIR__ );


/**
 * Load BB Elementor
 *
 * Load the widgets after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function wbcom_essential_elementor_load() {

	// Notice if the Elementor is not active.
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'wbcom_essential_elementor_fail_load' );

		return;
	}

	// Check required version.
	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'wbcom_essential_elementor_fail_load_out_of_date' );

		return;
	}
	
	// Require templates.
	require ELEMENTOR_WBCOMESSENTIAL__DIR__ . '/templates/templates.php';
}

function wbcom_essential_elementor_fail_load() {
	$plugin = 'elementor/elementor.php';
	$installed_plugins = get_plugins();	
	
	/* Check Plugin Install but not activate */
	if ( isset($installed_plugins[$plugin]) && !is_plugin_active($plugin)) {
		
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return; 
		}
		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		
		$admin_message = '<p>' . esc_html__( 'Wbcom Essential plugin does not work because you need to first activate the Elementor plugin.', 'wbcom-essential' ) . '</p>';
		$admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'wbcom-essential' ) ) . '</p>';
		
	} else {
		
		if ( ! current_user_can( 'install_plugins' ) ) {
			return; 
		}
		
		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
		$admin_message = '<p>' . esc_html__( 'Wbcom Essential plugin does not work because you need to first install the Elementor plugin.', 'wbcom-essential' ) . '</p>';
		$admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'wbcom-essential' ) ) . '</p>';
	}
	
	echo '<div class="error">' . $admin_message . '</div>';
}

add_action( 'init', 'wbcom_essential_elementor_load', -999 );


function wbcom_essential_elementor_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message      = '<p>' . __( 'Elementor Hello World is not working because you are using an old version of Elementor.', 'wbcom-essential' ) . '</p>';
	$message     .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'wbcom-essential' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}
