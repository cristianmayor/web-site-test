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
	<form method="post" action="options.php" class="bpchk_xprofile_setup">
		<h2><?php esc_html_e( 'Member xProfile Location/Address fields setup', 'bp-checkins' ); ?></h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label><?php esc_html_e( 'xProfile Fields Usage', 'bp-checkins' ); ?></label>
						</th>
						<td>
							<select name="bpchkpro_xprofile_options[xprofile_location_field_type]" id="bpcp_settings_field_usage" disabled>
								<option selected><?php esc_html_e( 'Multiple Address Fields', 'bp-checkins' ); ?></option>
							</select>
							<p class="description"><?php esc_html_e( 'Select to either use a sinlge address field as the full address, or multiple address fields. Then select the Xprofile Fields for each location field below.', 'bp-checkins' ); ?></p>
							<table class="form-table bpcp_multiple_address_field bpcp_field_usage_wrapper">
								<tbody>
									<tr>
										<td>
											<table>
											<tr>
												<th scope="row">
													<label><?php esc_html_e( 'Street', 'bp-checkins' ); ?></label>
												</th>
												<td>
														<select name="bpchkpro_xprofile_options[xprofile_location_field_street]" disabled>
															<option value="none" selected><?php esc_attr_e( 'Street', 'bp-checkins' ); ?></option>
														</select>
														<p class="description"><?php esc_html_e( 'Select street address xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label><?php esc_html_e( 'City', 'bp-checkins' ); ?></label>
												</th>
												<td>
														<select name="bpchkpro_xprofile_options[xprofile_location_field_city]" disabled>
															<option value="none" selected><?php esc_attr_e( 'City', 'bp-checkins' ); ?></option>
														</select>
														<p class="description"><?php esc_html_e( 'Select city xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label><?php esc_html_e( 'State', 'bp-checkins' ); ?></label>
												</th>
												<td>
														<select name="bpchkpro_xprofile_options[xprofile_location_field_state]" disabled>
															<option value="none" selected><?php esc_attr_e( 'State', 'bp-checkins' ); ?></option>
														</select>
														<p class="description"><?php esc_html_e( 'Select state xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label><?php esc_html_e( 'ZIP', 'bp-checkins' ); ?></label>
												</th>
												<td>
														<select name="bpchkpro_xprofile_options[xprofile_location_field_zip]" disabled>
															<option value="none" selected><?php esc_attr_e( 'ZIP', 'bp-checkins' ); ?></option>
														</select>
														<p class="description"><?php esc_html_e( 'Select zip xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label><?php esc_html_e( 'Country', 'bp-checkins' ); ?></label>
												</th>
												<td>
														<select name="bpchkpro_xprofile_options[xprofile_location_field_country]" disabled>
															<option value="none" selected><?php esc_attr_e( 'Country', 'bp-checkins' ); ?></option>
														</select>
													<p class="description"><?php esc_html_e( 'Select country xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
												</td>
											</tr>
										</table>
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
