<?php
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
if ( ! defined( 'Reign_Learndash_Addon_EDD_STORE_URL' ) ) {
	define( 'Reign_Learndash_Addon_EDD_STORE_URL', 'https://wbcomdesigns.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
}

// the name of your product. This should match the download name in EDD exactly
if ( ! defined( 'Reign_Learndash_Addon_EDD_ITEM_NAME' ) ) {
	define( 'Reign_Learndash_Addon_EDD_ITEM_NAME', 'Reign LearnDash Addon' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
}

// the name of the settings page for the license input to be displayed
if ( ! defined( 'Reign_Learndash_Addon_EDD_PLUGIN_LICENSE_PAGE' ) ) {
	define( 'Reign_Learndash_Addon_EDD_PLUGIN_LICENSE_PAGE', 'Reign_Learndash_Addon_edd_license_page' );
}

if ( ! class_exists( 'EDD_Reign_Learndash_Addon_Plugin_Updater' ) ) {
	// load our custom updater.
	include dirname( __FILE__ ) . '/EDD_Reign_Learndash_Addon_Plugin_Updater.php';
}

function Reign_Learndash_Addon_edd_plugin_updater() {
	// retrieve our license key from the DB.
	$license_key = trim( get_option( 'Reign_Learndash_Addon_edd_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_Reign_Learndash_Addon_Plugin_Updater(
		Reign_Learndash_Addon_EDD_STORE_URL,
		LearnMate_LearnDash_Addon_PLUGIN_FILE,
		array(
			'version'   => LearnMate_LearnDash_Addon_VERSION,             // current version number.
			'license'   => $license_key,        // license key (used get_option above to retrieve from DB).
			'item_name' => Reign_Learndash_Addon_EDD_ITEM_NAME,  // name of this plugin.
			'author'    => 'wbcomdesigns',  // author of this plugin.
			'url'       => home_url(),
		)
	);
}
add_action( 'admin_init', 'Reign_Learndash_Addon_edd_plugin_updater', 0 );

function Reign_Learndash_Addon_edd_license_page() {
	 $license = get_option( 'Reign_Learndash_Addon_edd_license_key', '' );
	$status   = get_option( 'Reign_Learndash_Addon_edd_license_status' );
	?>
	<div class="wrap">
		<h1><?php _e( 'Reign LearnDash Addon - License', 'reign-learndash-addon' ); ?></h1>
		<form method="post" action="options.php">

			<?php settings_fields( 'Reign_Learndash_Addon_edd_license' ); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'License Key', 'reign-learndash-addon' ); ?>
						</th>
						<td>
							<input id="Reign_Learndash_Addon_edd_license_key" name="Reign_Learndash_Addon_edd_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license, 'reign-learndash-addon' ); ?>" />
							<label class="description" for="Reign_Learndash_Addon_edd_license_key"><?php _e( 'Enter your license key', 'reign-learndash-addon' ); ?></label>
						</td>
					</tr>
					<?php if ( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'License Status', 'reign-learndash-addon' ); ?>
							</th>
							<td>
								<?php if ( $status !== false && $status == 'valid' ) { ?>
									<span style="color:green;"><?php _e( 'active', 'reign-learndash-addon' ); ?></span>
									<?php wp_nonce_field( 'Reign_Learndash_Addon_edd_nonce', 'Reign_Learndash_Addon_edd_nonce' ); ?>
									<?php
								} else {
									wp_nonce_field( 'Reign_Learndash_Addon_edd_nonce', 'Reign_Learndash_Addon_edd_nonce' );
									?>
								<span style="color:red;"><?php _e( 'Inactive', 'reign-learndash-addon' ); ?></span>
																<?php } ?>
							</td>
						</tr>
						<?php if ( $status !== false && $status == 'valid' ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Deactivate License', 'reign-learndash-addon' ); ?>
							</th>
							<td>
								<input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php _e( 'Deactivate License', 'reign-learndash-addon' ); ?>"/>
								<p class="description"><?php _e( 'Click for deactivate license.', 'reign-learndash-addon' ); ?></p>
							</td>
						</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
				<?php
				submit_button( __( 'Save Settings', 'reign-learndash-addon' ), 'primary', 'Reign_Learndash_Addon_edd_license_activate', true );
				?>

		</form>
	</div>
	<?php
}

function Reign_Learndash_Addon_edd_register_option() {
	// creates our settings in the options table
	register_setting( 'Reign_Learndash_Addon_edd_license', 'Reign_Learndash_Addon_edd_license_key', 'Reign_Learndash_Addon_edd_sanitize_license' );
}
add_action( 'admin_init', 'Reign_Learndash_Addon_edd_register_option' );

function Reign_Learndash_Addon_edd_sanitize_license( $new ) {
	$old = get_option( 'Reign_Learndash_Addon_edd_license_key' );
	if ( $old && $old != $new ) {
		delete_option( 'Reign_Learndash_Addon_edd_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
 * this illustrates how to activate
 * a license key
 *************************************/

function Reign_Learndash_Addon_edd_activate_license() {
	// listen for our activate button to be clicked
	if ( isset( $_POST['Reign_Learndash_Addon_edd_license_activate'] ) ) {
		// run a quick security check
		if ( ! check_admin_referer( 'Reign_Learndash_Addon_edd_nonce', 'Reign_Learndash_Addon_edd_nonce' ) ) {
			return; // get out if we didn't click the Activate button
		}

		// retrieve the license from the database
		$license = $_POST['Reign_Learndash_Addon_edd_license_key'];

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( Reign_Learndash_Addon_EDD_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			Reign_Learndash_Addon_EDD_STORE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'reign-learndash-addon' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {
				switch ( $license_data->error ) {
					case 'expired':
						$message = sprintf(
							__( 'Your license key expired on %s.', 'reign-learndash-addon' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked':
						$message = __( 'Your license key has been disabled.', 'reign-learndash-addon' );
						break;

					case 'missing':
						$message = __( 'Invalid license.', 'reign-learndash-addon' );
						break;

					case 'invalid':
					case 'site_inactive':
						$message = __( 'Your license is not active for this URL.', 'reign-learndash-addon' );
						break;

					case 'item_name_mismatch':
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'reign-learndash-addon' ), Reign_Learndash_Addon_EDD_ITEM_NAME );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'reign-learndash-addon' );
						break;

					default:
						$message = __( 'An error occurred, please try again.', 'reign-learndash-addon' );
						break;
				}
			}
		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'admin.php?page=reign-options&tab=license-manager' );
			$redirect = add_query_arg(
				array(
					'Reign_Learndash_Addon_activation' => 'false',
					'message'                          => urlencode( $message ),
				),
				$base_url
			);
			$license  = trim( $license );
			update_option( 'Reign_Learndash_Addon_edd_license_key', $license );
			update_option( 'Reign_Learndash_Addon_edd_license_status', $license_data->license );
			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"
		$license = trim( $license );
		update_option( 'Reign_Learndash_Addon_edd_license_key', $license );
		update_option( 'Reign_Learndash_Addon_edd_license_status', $license_data->license );
		wp_redirect( admin_url( 'admin.php?page=reign-options&tab=license-manager' ) );
		exit();
	}
}
add_action( 'admin_init', 'Reign_Learndash_Addon_edd_activate_license' );


/***********************************************
 * Illustrates how to deactivate a license key.
 * This will decrease the site count
 ***********************************************/

function Reign_Learndash_Addon_edd_deactivate_license() {
	// listen for our activate button to be clicked
	if ( isset( $_POST['edd_license_deactivate'] ) ) {
		// run a quick security check
		if ( ! check_admin_referer( 'Reign_Learndash_Addon_edd_nonce', 'Reign_Learndash_Addon_edd_nonce' ) ) {
			return; // get out if we didn't click the Activate button
		}

		// retrieve the license from the database
		$license = trim( get_option( 'Reign_Learndash_Addon_edd_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( Reign_Learndash_Addon_EDD_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			Reign_Learndash_Addon_EDD_STORE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'reign-learndash-addon' );
			}

			$base_url = admin_url( 'admin.php?page=reign-options&tab=license-manager' );
			$redirect = add_query_arg(
				array(
					'Reign_Learndash_Addon_activation' => 'false',
					'message'                          => urlencode( $message ),
				),
				$base_url
			);

			wp_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' ) {
			delete_option( 'Reign_Learndash_Addon_edd_license_status' );
		}

		wp_redirect( admin_url( 'admin.php?page=reign-options&tab=license-manager' ) );
		exit();
	}
}
add_action( 'admin_init', 'Reign_Learndash_Addon_edd_deactivate_license' );


/************************************
 * this illustrates how to check if
 * a license key is still valid
 * the updater does this for you,
 * so this is only needed if you
 * want to do something custom
 *************************************/

function Reign_Learndash_Addon_edd_check_license() {
	global $wp_version;

	$license = trim( get_option( 'Reign_Learndash_Addon_edd_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license'    => $license,
		'item_name'  => urlencode( Reign_Learndash_Addon_EDD_ITEM_NAME ),
		'url'        => home_url(),
	);

	// Call the custom API.
	$response = wp_remote_post(
		Reign_Learndash_Addon_EDD_STORE_URL,
		array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params,
		)
	);

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ( $license_data->license == 'valid' ) {
		echo 'valid';
		exit;
		// this license is still valid
	} else {
		echo 'invalid';
		exit;
		// this license is no longer valid
	}
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function Reign_Learndash_Addon_edd_admin_notices() {
	if ( isset( $_GET['Reign_Learndash_Addon_activation'] ) && ! empty( $_GET['message'] ) ) {
		switch ( $_GET['Reign_Learndash_Addon_activation'] ) {
			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;
		}
	}
}
add_action( 'admin_notices', 'Reign_Learndash_Addon_edd_admin_notices' );
