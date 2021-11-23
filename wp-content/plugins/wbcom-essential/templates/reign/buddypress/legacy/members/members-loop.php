<?php
if ( bp_has_members( bp_ajax_querystring( 'members' ) . $query_string ) ) : ?>

	<?php
	/**
	 * Fires before the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_members_list' );
	?>
<ul id="members-list" class="item-list rg-member-list wb-grid <?php echo $member_directory_type; ?>" aria-live="assertive" aria-relevant="all">

	<?php
	while ( bp_members() ) :
		bp_the_member();
		?>
		<?php $user_id = bp_get_member_user_id(); ?>
		<li <?php bp_member_class( array( 'wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-2 lg-wb-grid-1-' . $settings['columns'] . '' ) ); ?>>
			<div class="bp-inner-wrap">

		<?php
		if ( $member_directory_type == 'wbtm-member-directory-type-2' || $member_directory_type == 'wbtm-member-directory-type-3' ) {
			$args          = array(
				'object_dir' => 'members',
				'item_id'    => $user_id = bp_get_member_user_id(),
				'type'       => 'cover-image',
			);
			$cover_img_url = bp_attachments_get_attachment( 'url', $args );
			if ( empty( $cover_img_url ) ) {
				$cover_img_url = isset( $wbtm_reign_settings['reign_buddyextender']['default_xprofile_cover_image_url'] ) ? $wbtm_reign_settings['reign_buddyextender']['default_xprofile_cover_image_url'] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-cover.jpg';
				if ( empty( $cover_img_url ) ) {
					$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-cover.jpg';
				}
			}
			echo '<div class="wbtm-mem-cover-img"><img src="' . $cover_img_url . '" /></div>';
		}
		?>

				<div class="item-avatar">
		<?php
		if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
			echo '<figure class="img-dynamic aspect-ratio avatar">';
		}
		?>
					<a class="<?php echo $img_class; ?>" href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?><?php echo reign_get_online_status( $user_id ); ?></a>
					<?php
					if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
						echo '</figure>';
					}
					?>
				</div>

					<?php
					if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
						echo '<div class="item-wrapper">';
					}
					?>

				<div class="item">

					<div class="item-title">
						<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
			<?php if ( bp_get_member_latest_update() ) : ?>
							<span class="update"> <?php bp_member_latest_update(); ?></span>
						<?php endif; ?>
					</div>

					<div class="item-meta">
						<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => false ) ) ); ?>"><?php bp_member_last_active(); ?></span>
					</div>

		<?php
		/**
		 * Fires inside the display of a directory member item.
		 *
		 * @since 1.1.0
		 */
		if ( $member_directory_type != 'wbtm-member-directory-type-1' ) {
			do_action( 'bp_directory_members_item' );
		}
		?>
				</div>
				<div class="action-wrap">
					<i class="fa fa-plus-circle"></i>
					<div class="action rg-dropdown"><?php do_action( 'bp_directory_members_actions' ); ?></div>
				</div>

				<?php
				if ( $member_directory_type == 'wbtm-member-directory-type-4' ) {
					echo '</div>';
				}
				?>

			</div>
		</li>
		<?php endwhile; ?>
</ul>
	<?php
	/**
	 * Fires after the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_members_list' );
	?>

	<?php bp_member_hidden_fields(); ?>
<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no members were found.', 'buddypress' ); ?></p>
	</div>
<?php endif; ?>
