<div class="groups dir-list">
	<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) . $query_string ) ) : ?>
		<?php
		/**
		 * Fires before the listing of the groups list.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_directory_groups_list' );
		?>
		<ul id="groups-list" class="item-list wb-grid rg-group-list <?php echo $group_directory_type; ?>" aria-live="assertive" aria-atomic="true" aria-relevant="all">

			<?php
			while ( bp_groups() ) :
				bp_the_group();
				?>

				<li <?php bp_group_class( array( 'wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-' . $column . ' ' . $addition_class ) ); ?>>
					<div class="bp-group-inner-wrap">

						<?php
						/**
						 * Fires inside the listing of an individual group listing item.
						 * Added by Reign Theme
						 *
						 * @since 1.0.7
						 */
						if ( $group_directory_type != 'wbtm-group-directory-type-1' ) {
							$args          = array(
								'object_dir' => 'groups',
								'item_id'    => $group_id = bp_get_group_id(),
								'type'       => 'cover-image',
							);
							$cover_img_url = bp_attachments_get_attachment( 'url', $args );
							if ( empty( $cover_img_url ) ) {
								global $wbtm_reign_settings;
								$cover_img_url = isset( $wbtm_reign_settings['reign_buddyextender']['default_group_cover_image_url'] ) ? $wbtm_reign_settings['reign_buddyextender']['default_group_cover_image_url'] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-cover.jpg';
								if ( empty( $cover_img_url ) ) {
									$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-cover.jpg';
								}
							}
							echo '<div class="wbtm-group-cover-img"><img src="' . $cover_img_url . '" /></div>';
						}
						?>
						<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
							<?php
							if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
								echo '<figure class="img-dynamic aspect-ratio avatar">';
							}
							?>
							<a class="item-avatar-group <?php echo $img_class; ?>" href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( '' ); ?></a>
							<?php
							if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
								echo '</figure>';
							}
							?>
						<?php endif; ?>

						<div class="group-content-wrap">

							<div class="item">
								<div class="item-title"><?php bp_group_link(); ?></div>
								<?php
								/**
								 * Fires inside the listing of an individual group listing item.
								 *
								 * @since 1.1.0
								 */
								do_action( 'bp_directory_groups_item' );
								?>

							</div>

							<?php do_action( 'wbtm_bp_directory_groups_data' ); ?>

							<div class="group-admins-wrap">
								<?php reign_bp_group_list_admins(); ?>
							</div>
							<?php
							if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
								echo '<div class="action-wrap"><i class="fa fa-plus-circle"></i>';
							}
							?>
							<div class="action">

								<?php
								/**
								 * Fires inside the action section of an individual group listing item.
								 *
								 * @since 1.1.0
								 */
								do_action( 'bp_directory_groups_actions' );
								?>


							</div>
							<?php
							if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
								echo '</div>';
							}
							?>
						</div>
					</div>
				</li>
			<?php endwhile; ?>
		</ul>

		<?php
		/**
		 * Fires after the listing of the groups list.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_directory_groups_list' );
		?>
	<?php else : ?>

		<div id="message" class="info">
			<p><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
		</div>

	<?php endif; ?>
</div>
