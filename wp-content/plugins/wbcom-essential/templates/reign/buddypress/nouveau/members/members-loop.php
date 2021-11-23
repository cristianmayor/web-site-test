<?php

bp_nouveau_before_loop(); ?>

<?php if ( bp_get_current_member_type() ) : ?>
	<p class="current-member-type"><?php bp_current_member_type_message(); ?></p>
<?php endif; ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) . $query_string ) ) : ?>
  <ul id="members-list" class="rg-member-list item-list members-list bp-list grid <?php echo $column_class . ' ' . $member_directory_type; ?>">

	<?php
	while ( bp_members() ) :
		 bp_the_member();
		?>
		<li <?php bp_member_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_member_user_id(); ?>" data-bp-item-component="members">
			<div class="list-wrap">
				<?php
				if ( $member_directory_type == 'wbtm-member-directory-type-2' || $member_directory_type == 'wbtm-member-directory-type-3' ) {
					$args          = array(
						'object_dir' => 'members',
						'item_id'    => bp_get_member_user_id(),
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
					<a class="<?php echo $img_class; ?>" href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar( bp_nouveau_avatar_args() ); ?><?php echo reign_get_online_status( bp_get_member_user_id() ); ?></a>
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
					<div class="item-block">
						<h2 class="list-title member-name">
							<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
						</h2>

						<?php
						if ( bp_nouveau_member_has_meta() ) :
							?>
							<p class="item-meta last-activity">
								<?php bp_nouveau_member_meta(); ?>
							</p><!-- #item-meta -->
						<?php endif; ?>
						<?php
						if ( $member_directory_type == 'wbtm-member-directory-type-2' || $member_directory_type == 'wbtm-member-directory-type-3' ) {
							// $this->wbtm_get_members_directory_meta();
						}
						?>
					</div>

					  <?php
						if ( false && bp_get_member_latest_update() && ! bp_nouveau_loop_is_grid() ) :
							?>
						<div class="user-update">
							<p class="update"> <?php bp_member_latest_update(); ?></p>
						</div>
						<?php endif; ?>
				</div><!-- // .item -->
				<div class="action-wrap">
					<i class="fa fa-plus-circle"></i>
					<?php
					bp_nouveau_members_loop_buttons(
						array(
							'container'      => 'ul',
							'button_element' => 'button',
						)
					);
					?>
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

	else :
		bp_nouveau_user_feedback( 'members-loop-none' );
endif;
