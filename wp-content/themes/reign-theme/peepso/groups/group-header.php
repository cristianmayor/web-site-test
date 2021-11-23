<?php
	global $wbtm_reign_settings;

	$PeepSoGroupUser = new PeepSoGroupUser( $group->id );
	// $PeepSoGroup = new PeepSoGroup($group->id);
	$PeepSoGroup = $group;
	$coverUrl    = $PeepSoGroup->get_cover_url();
	$has_cover   = false;

if ( false !== stripos( $coverUrl, 'peepso/groups/' ) ) {
	$has_cover = true;
}

if ( false === $PeepSoGroupUser->can( 'manage_group' ) || ( false === $has_cover ) ) {
	$reposition_style = 'display:none;';
	$cover_class      = 'default';
} else {
	$reposition_style = '';
	$cover_class      = 'has-cover';
}

	$description = str_replace( "\n", '<br/>', $group->description );
	$description = html_entity_decode( $description );

	$group_categories      = PeepSoGroupCategoriesGroups::get_categories_for_group( $group->id );
	$group_categories_html = array();

	$group_cover_photo = get_post_meta( $group->id, 'group_cover_photo', true );
if ( empty( $group_cover_photo ) ) {
	$group_cover_photo = reign_render_peepso_group_cover_image();
}

?>
<div class="ps-focus ps-focus--group ps-group__profile-focus ps-js-focus ps-js-focus--group ps-js-group-header">
	<div class="ps-focus__cover ps-js-cover">
		<div class="ps-focus__cover-image ps-js-cover-wrapper">
			<img class="ps-js-cover-image" src="<?php echo $group_cover_photo; ?>"
				alt="<?php printf( __( '%s cover photo', 'reign' ), $PeepSoGroup->get( 'name' ) ); ?>"
				style="<?php echo $PeepSoGroup->cover_photo_position(); ?>" />
		</div>

		<div class="ps-avatar ps-avatar--focus ps-group__profile-focus-avatar ps-js-avatar">
			<img class="ps-js-avatar-image" src="<?php echo $PeepSoGroup->get_avatar_url_full(); ?>"
				alt="<?php printf( __( '%s avatar', 'reign' ), $PeepSoGroup->get( 'name' ) ); ?>" />
			<?php if ( $PeepSoGroupUser->can( 'manage_group' ) ) { ?>
			<a href="#" class="ps-focus__avatar-change ps-js-avatar-button">
				<i class="gcis gci-camera"></i>
			</a>
			<?php } ?>
		</div>

		<div class="ps-focus__cover-inner">
			<div class="ps-focus__cover-actions ps-js-group-header-actions ps-js-loading">
				<button class="ps-focus__cover-action">
					<img src="<?php echo PeepSo::get_asset( 'images/ajax-loader.gif' ); ?>" />
				</button>
			</div>
		</div>

		<?php if ( $PeepSoGroupUser->can( 'manage_group' ) ) { ?>

		<div class="ps-focus__options ps-js-dropdown ps-js-cover-dropdown">
			<a href="#" class="ps-focus__options-toggle ps-js-dropdown-toggle"><i class="gcis gci-image"></i></a>
			<div class="ps-focus__options-menu ps-js-dropdown-menu">
				<a href="#" class="ps-js-cover-upload">
					<i class="gcis gci-paint-brush"></i>
			<?php echo __( 'Upload a new cover', 'reign' ); ?>
				</a>
				<a href="#" class="ps-js-cover-reposition">
					<i class="gcis gci-arrows-alt"></i>
			<?php echo __( 'Reposition', 'reign' ); ?>
				</a>
				<a href="#" class="ps-js-cover-remove">
					<i class="gcis gci-trash"></i>
			<?php echo __( 'Remove cover', 'reign' ); ?>
				</a>
			</div>
		</div>

		<div class="ps-focus__reposition ps-js-cover-reposition-actions" style="display:none">
			<div class="ps-focus__reposition-actions reposition-cover-actions">
				<a href="#" class="ps-focus__reposition-action ps-js-cover-reposition-cancel"><?php echo __( 'Cancel', 'reign' ); ?></a>
				<a href="#" class="ps-focus__reposition-action ps-js-cover-reposition-confirm"><i class="fas fa-check"></i> <?php echo __( 'Save', 'reign' ); ?></a>
			</div>
		</div>

		<?php } ?>
	</div>

	<div class="ps-focus__footer ps-group__profile-focus-footer">
		<div class="ps-focus__info">
			<div class="ps-focus__title">
				<div class="ps-focus__name">
					<?php echo $group->name; ?>
				</div>
				<div class="ps-focus__desc-toggle ps-tip ps-tip--absolute ps-tip--inline ps-js-focus-box-toggle" aria-label="<?php echo __( 'Show details', 'reign' ); ?>">
					<i class="gcis gci-info-circle"></i>
				</div>
			</div>

			<div class="ps-focus__desc ps-js-focus-desc">
				<!-- Description -->
				<?php echo stripslashes( $description ); ?>

				<!-- Categories -->
				<?php if ( PeepSo::get_option( 'groups_categories_enabled', false ) ) { ?>
				<div class="ps-focus__desc-details">
					<?php
					if ( count( $group_categories ) > 1 ) {
						?>
						<i class="gcis gci-tags"></i> <?php echo __( 'Group categories', 'reign' ); ?>:
													  <?php
					} else {

						?>
					<i class="gcis gci-tag"></i> <?php echo __( 'Group category', 'reign' ); ?>:
						<?php
					}
					?>
					<?php

					foreach ( $group_categories as $PeepSoGroupCategory ) {
						echo "<a href=\"{$PeepSoGroupCategory->get_url()}\">{$PeepSoGroupCategory->name}</a>";
					}

					?>
				</div>
				<?php } ?>
			</div>

			<div class="ps-focus__details">
				<!-- DETAILS -->

				<!-- Privacy -->
				<div class="ps-focus__detail">
					<?php if ( $PeepSoGroupUser->can( 'manage_group' ) && strlen( $group_segment ) && 'settings' == $group_segment ) { ?>
						<div class="ps-group__profile-privacy ps-dropdown ps-dropdown--menu ps-js-dropdown ps-js-privacy ps-js-privacy--<?php echo $group->id; ?>">
							<a href="javascript:" data-value="" class="ps-btn ps-btn--sm ps-btn--dropdown ps-dropdown__toggle ps-js-dropdown-toggle">
								<span class="dropdown-value">
									<i class="<?php echo $group->privacy['icon']; ?>"></i><span><?php echo $group->privacy['name']; ?></span>
								</span>
								<img class="ps-loading" src="<?php echo PeepSo::get_asset( 'images/ajax-loader.gif' ); ?>" />
								<div class="ps-btn__icon"><span class="gcis gci-chevron-down"></span></div>
							</a>

						<?php echo PeepSoGroupPrivacy::render_dropdown(); ?>
						</div>
					<?php } else { ?>
						<span class="ps-btn ps-btn--sm ps-btn--app ps-tip ps-tip--md ps-tip--arrow ps-tip--left" aria-label="<?php echo $group->privacy['desc']; ?>">
							<i class="<?php echo $group->privacy['icon']; ?>"></i><?php echo sprintf( __( '%s Group', 'reign' ), $group->privacy['name'] ); ?>
						</span>
					<?php } ?>
				</div>

				<!-- Members -->
				<a class="ps-focus__detail" href="<?php echo $group->get_url() . 'members/'; ?>">
					<i class="gcis gci-user-friends"></i>
					<span class="ps-js-member-count"><?php printf( _n( '%s member', '%s members', $group->members_count, 'reign' ), number_format_i18n( $group->members_count ) ); ?></span>
				</a>

				<!-- Pending members -->
				<?php if ( $group->pending_admin_members_count > 0 && $PeepSoGroupUser->can( 'manage_users' ) ) { ?>
				<a class="ps-focus__detail" href="<?php echo $group->get_url() . 'members/pending'; ?>">
					<i class="gcis gci-user-clock"></i>
					<?php echo sprintf( __( '<span class="ps-js-pending-count" data-id="%1$d">%2$s</span> pending', 'reign' ), $group->id, $group->pending_admin_members_count ); ?>
				</a>
				<?php } ?>
			</div>
		</div>

		<div class="ps-focus__menu ps-js-focus__menu">
			<div class="ps-focus__menu-inner ps-js-focus__menu-inner">
				<?php

				$segments      = array();
				$segments[0][] = array(
					'href'  => '',
					'title' => __( 'Stream', 'reign' ),
					'icon'  => 'gcis gci-stream',
				);

				if ( $PeepSoGroupUser->can( 'manage_group' ) ) {
					$segments[0][] = array(
						'href'  => 'settings',
						'title' => __( 'Settings', 'reign' ),
						'icon'  => 'gcis gci-cog',
					);
				}

				$title = __( 'Members', 'reign' );

				if ( $PeepSoGroupUser->can( 'manage_users' ) && $pending = $group->pending_admin_members_count ) {
					$title .= ' <span class="ps-js-pending-label">(' . sprintf( __( '<span class="ps-js-pending-count" data-id="%1$d">%2$s</span> pending', 'reign' ), $group->id, $pending ) . ')</span>';
				}

				$segments[0][] = array(
					'href'  => 'members',
					'title' => $title,
					'icon'  => 'gcis gci-user-friends',
				);

				$segments = apply_filters( 'peepso_group_segment_menu_links', $segments );

				foreach ( $segments as $segment_group ) {
					foreach ( $segment_group as $segment ) {

						$can_access = $PeepSoGroupUser->can( 'access_segment', $segment['href'] );

						$href = $group->get_url();

						if ( strlen( $segment['href'] ) ) {
							$href .= $segment['href'] . '/';
						}

						if ( $can_access ) {
							?>
						<a class="ps-focus__menu-item ps-js-item <?php echo( $segment['href'] == $group_segment ) ? 'ps-focus__menu-item--active' : ''; ?>" href="<?php echo $href; ?>">
							<i class="<?php echo $segment['icon']; ?>"></i>
							<span><?php echo $segment['title']; ?></span>
						</a>
							<?php
						}
					}
				}

				?>
				<a href="#" class="ps-focus__menu-item ps-focus__menu-item--more ps-tip ps-tip--arrow ps-js-item-more" aria-label="<?php echo __( 'More', 'reign' ); ?>" style="display:none">
					<i class="gcis gci-ellipsis-h"></i>
				</a>
				<div class="ps-focus__menu-more ps-dropdown ps-dropdown--menu ps-js-focus-more">
					<div class="ps-dropdown__menu ps-js-focus-link-dropdown"></div>
				</div>
			</div>
			<div class="ps-focus__menu-shadow ps-focus__menu-shadow--left ps-js-aid-left"></div>
			<div class="ps-focus__menu-shadow ps-focus__menu-shadow--right ps-js-aid-right"></div>
		</div>
	</div>
</div>
<script>
jQuery(function() {
	peepsogroupsdata.group_id = +'<?php echo $group->id; ?>';
});
</script>
