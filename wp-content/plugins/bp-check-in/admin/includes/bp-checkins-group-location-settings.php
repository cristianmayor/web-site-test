<?php

/**
 * Advanced settings area view for the plugin
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
	<form class="bpchkpro-group-location" action="options.php" method="post">
		<h2><?php esc_html_e( 'BP Group Location/Address Fields Settings', 'bp-checkins' ); ?></h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="group_tab_lable"><?php esc_html_e( 'Location/Address tab label', 'bp-checkins' ); ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" name="groups_settings[group_tab_lable]" id="group_tab_lable" value="Location" disabled>
						<p class="description"><?php esc_html_e( 'Group location/address tab label.', 'bp-checkins' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="group_tab_slug"><?php esc_html_e( 'Location/Address tab slug', 'bp-checkins' ); ?></label>
					</th>
					<td>
						<input class="regular-text" type="text" name="groups_settings[group_tab_slug]" value="location" disabled>
						<p class="description"><?php esc_html_e( 'You can rewite the group location/address tab slug.', 'bp-checkins' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="group_location_fields"><?php esc_html_e( 'Group Location Fields', 'bp-checkins' ); ?></label>
					</th>
					<td>
						<select id="group_location_fields" name="groups_settings[group_location_field_set]" disabled>
							<option value="none" selected><?php esc_html_e( 'Multiple Fields', 'bp-checkins' ); ?></option>
						</select>
						<p class="description"><?php esc_html_e( 'Select how many fields you want to show on group. Select disable for none.', 'bp-checkins' ); ?></p>
						<table>
							<tbody>
								<tr class="bpchkpro-multi-field " >
									<th scope="row">
										<label for="group_location_street"><?php esc_html_e( 'Street', 'bp-checkins' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="groups_settings[group_location_street_enable]" checked disabled>
										<input type="text" id="group_location_street" class="regular-text" name="groups_settings[group_location_street]" value="Street" disabled>
										<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
									</td>
								</tr>
								<tr class="bpchkpro-multi-field" >
									<th scope="row">
										<label for="group_location_city"><?php esc_html_e( 'City', 'bp-checkins' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="groups_settings[group_location_city_enable]" checked disabled>
										<input type="text" id="group_location_city" class="regular-text" name="groups_settings[group_location_city]" value="City" disabled>
										<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
									</td>
								</tr>
								<tr class="bpchkpro-multi-field" >
									<th scope="row">
										<label for="group_location_state"><?php esc_html_e( 'State', 'bp-checkins' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="groups_settings[group_location_state_enable]" checked disabled>
										<input type="text" id="group_location_state" class="regular-text" name="groups_settings[group_location_state]" value="State" disabled>
										<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
									</td>
								</tr>
								<tr class="bpchkpro-multi-field" >
									<th scope="row">
										<label for="group_location_postalcode"><?php esc_html_e( 'Postal Code', 'bp-checkins' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="groups_settings[group_location_postalcode_enable]"checked disabled>
										<input type="text" id="group_location_postalcode" class="regular-text" name="groups_settings[group_location_postalcode]" value="Postal Code" disabled>
										<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
									</td>
								</tr>
								<tr class="bpchkpro-multi-field" >
									<th scope="row">
										<label for="group_location_country"><?php esc_html_e( 'Country', 'bp-checkins' ); ?></label>
									</th>
									<td>
										<input type="checkbox" name="groups_settings[group_location_country_enable]" checked disabled>
										<input type="text" id="group_location_country" class="regular-text" name="groups_settings[group_location_country]" value="Country" disabled>
										<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
