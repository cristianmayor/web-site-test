<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used for rendering and saving plugin general settings.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Bp_Checkins_Pro
 * @subpackage Bp_Checkins_Pro/admin/partials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">
	<form method="post" action="options.php">
		<table class="form-table bpchk-admin-page-table">
			<h2 class="title"><?php esc_html_e( 'Select MAP provider', 'bp-checkins' ); ?></h2>
			<tbody>
				<!-- API Key -->
				<tr>
					<th scope="row">
						<label for="bpcp-select-map"><?php esc_html_e( 'Select Maps Provider', 'bp-checkins' ); ?></label>
					</th>
					<td>
						<select id="bpcp-select-map" class="bpcp-map-select" name="map_settings[map_provider]" disabled>
							<option selected><?php esc_html_e( 'Google Map', 'bp-checkins' ); ?></option>
						</select>
						<input id="bpchk_geocode_provider" type="hidden" name="map_settings[geocoder_provider]" disabled>
						<p class="description"><?php esc_html_e( 'Select the maps provider that you would like to use.', 'bp-checkins' ); ?></p>
					</td>
				</tr>
				<tr class="bpckp-google-map-settings">
					<th scope="row"><label for="api-key"><?php esc_html_e( 'API Key', 'bp-checkins' ); ?></label></th>
					<td>
						<input class="regular-text" type="text"  name="map_settings[apikey]" id="bpchk-api-key" disabled placeholder="<?php esc_html_e( 'API Key', 'bp-checkins' ); ?>">
						<button type="button" class="button button-secondary"  disabled><?php esc_html_e( 'Verify', 'bp-checkins' ); ?></button>
						<p class="description"><?php esc_html_e( "Due to changes in Google Maps API it's required to use an API key for the BuddyPress Check-ins plugin to work properly. You can get the API key", 'bp-checkins' ); ?>&nbsp;<a target="blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key" class="Disabled"><?php esc_html_e( 'here.', 'bp-checkins' ); ?></a>&nbsp;
							<a href="javascript:void(0);" onClick="window.open('https://wbcomdesigns.com/helpdesk/knowledge-base/get-google-api-key/','pagename','resizable,height=600,width=700'); return false;" class="Disabled">
								<?php esc_html_e( '( How to Get Google API Key? )', 'bp-checkins' ); ?>
							</a>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<h2 class="title check-ins-settings-title"><?php esc_html_e( 'Check-ins Settings', 'bp-checkins' ); ?></h2>
		<table class="form-table">
			<tbody>
					<!-- Checkin By - autocomplete or placetype -->
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'Enable Check-in functionality', 'bp-checkins' ); ?></label>
						</th>
						<td>
							<label class="wb-switch">
								<input type="checkbox" id="bpchk_disable_checkins" class="bpcp-switch-field" name="map_settings[disable_checkins]" checked disabled>
								<div class="wb-slider wb-round"></div>
							</label>
							<p class="description"><?php esc_html_e( 'Enable this option, if you want to remove check-in functionality.', 'bp-checkins' ); ?></p>
						</td>
					</tr>		
			</tbody>
		</table>
	</form>

</div>
	<?php
