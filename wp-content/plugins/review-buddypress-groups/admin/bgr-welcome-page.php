<?php
/**
 *
 * This file is used for rendering and saving plugin welcome settings.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}
?>

<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">
		<div class="wbcom-welcome-head">
			<h2 class="wbcom-welcome-title"><?php esc_html_e( 'BuddyPress Group Reviews', 'bp-group-reviews' ); ?></h2>
			<p class="wbcom-welcome-description"><?php esc_html_e( 'BuddyPress Group Reviews allows BuddyPress members to add group reviews & give multiple-rating to given criteria(s). After posting reviews, all reviews will be displayed in the Manage Review section of the group.Admins can approve or deny the review. When the admin approves the review, it will be published. All published reviews will be shown in the reviews tab on the single group page. Admin is allowed to add as many criteria for the rating as he wants.', 'bp-group-reviews' ); ?></p>
			<p class="wbcom-welcome-description"><?php esc_html_e( 'Admins can approve or deny review. When admin approves the review they will be published.All published reviews will be shown in reviews tab in the single group page.Admin is allowed to add as many criteria for the rating as he wants.', 'bp-group-reviews' ); ?></p>
		</div><!-- .wbcom-welcome-head -->

		<div class="wbcom-welcome-content">

			<div class="wbcom-video-link-wrapper">
			  <iframe src="https://player.vimeo.com/video/556662460" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
<p><a href="https://vimeo.com/556662460">BuddyPress Group Reviews</a> from <a href="https://vimeo.com/wbcom">Wbcom Designs</a> on <a href="https://vimeo.com">Vimeo</a>.</p>
			</div>

			<div class="wbcom-welcome-support-info">
				<h3><?php esc_html_e( 'Help &amp; Support Resources', 'bp-group-reviews' ); ?></h3>
				<p><?php esc_html_e( 'Here are all the resources you may need to get help from us. Documentation is usually the best place to start. Should you require help anytime, our customer care team is available to assist you at the support center.', 'bp-group-reviews' ); ?></p>
				<hr>

				<div class="three-col">

					<div class="col">
						<h3><span class="dashicons dashicons-book"></span><?php esc_html_e( 'Documentation', 'bp-group-reviews' ); ?></h3>
						<p><?php esc_html_e( 'We have prepared an extensive guide on BuddyPress Group Reviews to learn all aspects of the plugin. You will find most of your answers here.', 'bp-group-reviews' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/docs/buddypress-free-addons/buddypress-group-review/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Read Documentation', 'bp-group-reviews' ); ?></a>
					</div>

					<div class="col">
						<h3><span class="dashicons dashicons-sos"></span><?php esc_html_e( 'Support Center', 'bp-group-reviews' ); ?></h3>
						<p><?php esc_html_e( 'We strive to offer the best customer care via our support center. Once your theme is activated, you can ask us for help anytime.', 'bp-group-reviews' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/support/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Get Support', 'bp-group-reviews' ); ?></a>
					</div>

					<div class="col">
						<h3><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e( 'Got Feedback?', 'bp-group-reviews' ); ?></h3>
						<p><?php esc_html_e( 'We want to hear about your experience with the plugin. We would also love to hear any suggestions you may for future updates.', 'bp-group-reviews' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/contact/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Send Feedback', 'bp-group-reviews' ); ?></a>
					</div>

				</div>

			</div>
		</div>

	</div><!-- .wbcom-welcome-content -->
</div><!-- .wbcom-welcome-main-wrapper -->
