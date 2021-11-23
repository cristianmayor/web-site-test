<div id="groups-dir-list" class="groups dir-list">
<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) . $query_string ) ) : ?>
	<ul id="groups-list" class="rg-group-list item-list groups-list bp-list grid <?php echo $column_class . ' ' . $group_directory_type; ?>">

		<?php
		while ( bp_groups() ) :
			bp_the_group();
			?>

			<li <?php bp_group_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups">
				<div class="list-wrap">

					<?php
					/**
					 * Fires inside the listing of an individual group listing item.
					 * Added by Reign Theme
					 *
					 * @since 1.0.7
					 */
					// do_action( 'wbtm_before_group_avatar_group_directory' );
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
						<div class="item-avatar">
							<?php
							if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
								echo '<figure class="img-dynamic aspect-ratio avatar">';
							}
							?>
							<a class="item-avatar-group <?php echo $img_class; ?>" href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( bp_nouveau_avatar_args() ); ?></a>
							   <?php
								if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
									echo '</figure>';
								}
								?>
						</div>
					<?php endif; ?>

					<div class="group-content-wrap">
						<div class="item">

							<div class="item-block">

								<h2 class="list-title groups-title"><?php bp_group_link(); ?></h2>

								<?php if ( bp_nouveau_group_has_meta() ) : ?>

									<p class="item-meta group-details">
										<?php 
											if (function_exists( 'bp_nouveau_the_group_meta' ) ) { 
												bp_nouveau_the_group_meta();
											}else if ( function_exists( 'bp_nouveau_group_meta') ) {
												bp_nouveau_group_meta();
											} 
										?>
									 </p>

								<?php endif; ?>

								<p class="last-activity item-meta">
									<?php
									printf(
									/* translators: %s = last activity timestamp (e.g. "active 1 hour ago") */
										__( 'active %s', 'buddypress' ),
										bp_get_group_last_active()
									);
									?>
								</p>

							</div>

							<div class="group-desc"><p><?php bp_group_description_excerpt(); ?></p></div>

							<?php bp_nouveau_groups_loop_item(); ?>

							<?php // bp_nouveau_groups_loop_buttons(); ?>

						</div>

						<?php do_action( 'wbtm_bp_directory_groups_data' ); ?>

						<div class="group-admins-wrap">
							<?php reign_bp_group_list_admins(); ?>
						</div>

						<!-- Added action buttons here -->
						<?php
						if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
							echo '<div class="action-wrap"><i class="fa fa-plus-circle"></i>';
						}
						bp_nouveau_groups_loop_buttons();
						if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
							echo '</div>';
						}
						?>
					</div>
				</div>
			</li>

		<?php endwhile; ?>

	</ul>
<?php else : ?>

	<?php bp_nouveau_user_feedback( 'groups-loop-none' ); ?>

<?php endif; ?>
</div>
