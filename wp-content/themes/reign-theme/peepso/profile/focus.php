<?php
global $wbtm_reign_settings;

$PeepSoProfile = PeepSoProfile::get_instance();
$PeepSoUser    = $PeepSoProfile->user;
$cover         = reign_get_peepso_member_cover_image();

if ( empty( $cover ) ) {
	$cover            = reign_render_peepso_member_cover_image();
	$reposition_style = 'display:none;';
	$cover_class      = 'default';
} else {
	$reposition_style = '';
	$cover_class      = 'has-cover';
}

$is_profile_segment = isset( $current ) ? true : false;
$use_small_cover    = $is_profile_segment && 0 == PeepSo::get_option( 'always_full_cover', 0 );

?>
<div class="ps-focus <?php echo $use_small_cover ? 'ps-focus--small' : ''; ?> ps-js-focus ps-js-focus--profile ps-js-focus--<?php echo $PeepSoUser->get_id(); ?>">
	<div class="ps-focus__cover ps-js-cover">
		<div class="ps-focus__cover-image ps-js-cover-wrapper">
			<img class="ps-js-cover-image <?php echo esc_attr( $cover_class ); ?>" src="<?php echo esc_url( $cover ); ?>"
				alt="<?php printf( __( '%s cover photo', 'peepso-core' ), $PeepSoUser->get_fullname() ); ?>"
				style="<?php echo $PeepSoUser->get_cover_position(); ?>" />
		</div>

		<div class="ps-avatar ps-avatar--focus ps-js-avatar">
			<img class="ps-js-avatar-image" src="<?php echo $PeepSoUser->get_avatar( 'full' ); ?>"
				alt="<?php printf( __( '%s avatar', 'peepso-core' ), $PeepSoUser->get_fullname() ); ?>" />
			<?php if ( ( 1 != PeepSo::get_option( 'avatars_wordpress_only', 0 ) ) && $PeepSoProfile->can_edit() ) { ?>
			<a href="#" class="ps-focus__avatar-change ps-js-avatar-button">
				<i class="gcis gci-camera"></i>
			</a>
			<?php } ?>

			<?php if ( $PeepSoUser->get_online_status() ) { ?>
			<div class="ps-online ps-online--focus ps-tip ps-tip--inline"
				aria-label="<?php echo sprintf( __( '%s is currently online', 'peepso-core' ), $PeepSoUser->get_fullname() ); ?>"></div>
			<?php } ?>
		</div>

		<?php if ( $PeepSoProfile->can_edit() ) { ?>

		<!-- Cover options dropdown -->
		<div class="ps-focus__cover-inner">
			<div class="ps-focus__cover-actions ps-js-focus-actions">
			<?php $PeepSoProfile->profile_actions(); ?>
			</div>
		</div>
		<div class="ps-focus__options ps-js-dropdown ps-js-cover-dropdown">
			<a href="#" class="ps-focus__options-toggle ps-js-dropdown-toggle"><i class="gcis gci-image"></i></a>
			<div class="ps-focus__options-menu ps-js-dropdown-menu">
				<a href="#" class="ps-js-cover-upload">
					<i class="gcis gci-paint-brush"></i>
			<?php echo __( 'Upload a new cover', 'peepso-core' ); ?>
				</a>
				<a href="#" class="ps-js-cover-reposition">
					<i class="gcis gci-arrows-alt"></i>
			<?php echo __( 'Reposition', 'peepso-core' ); ?>
				</a>
				<a href="#" class="ps-js-cover-remove">
					<i class="gcis gci-trash"></i>
			<?php echo __( 'Remove cover', 'peepso-core' ); ?>
				</a>
			</div>
		</div>
		<!-- Reposition cover - buttons -->
		<div class="ps-focus__reposition ps-js-cover-reposition-actions" style="display:none">
			<div class="ps-focus__reposition-actions reposition-cover-actions">
				<a href="#" class="ps-focus__reposition-action ps-js-cover-reposition-cancel"><?php echo __( 'Cancel', 'peepso-core' ); ?></a>
				<a href="#" class="ps-focus__reposition-action ps-js-cover-reposition-confirm"><i class="gcis gci-check"></i> <?php echo __( 'Save', 'peepso-core' ); ?></a>
			</div>
		</div>

		<?php } else { ?>

		<div class="ps-focus__cover-inner">
			<div class="ps-focus__cover-actions ps-js-focus-actions">
			<?php $PeepSoProfile->profile_actions(); ?>
			</div>
		</div>

		<?php } ?>
		<div class="reign-social-icons">
			<?php reign_peepso_user_social_links( $PeepSoUser->get_id() ); ?>
		</div>    
		 
	</div>

	<div class="ps-focus__footer">
		<div class="ps-focus__info">
			<div class="ps-focus__title">
				<?php
				if ( ! $is_profile_segment || 1 == PeepSo::get_option( 'always_full_cover', 0 ) ) {
					echo '<div class="ps-focus__title-before">', do_action( 'peepso_profile_cover_full_before_name', $PeepSoUser->get_id() ), '</div>';
				}
				?>
				<div class="ps-focus__name" data-hover-card="<?php echo $PeepSoUser->get_id(); ?>">
				<?php
					// [peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
					do_action( 'peepso_action_render_user_name_before', $PeepSoUser->get_id() );

					echo $PeepSoUser->get_fullname();

					// [peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
					do_action( 'peepso_action_render_user_name_after', $PeepSoUser->get_id() );
				?>
				</div>
				<?php
				if ( ! $is_profile_segment || 1 == PeepSo::get_option( 'always_full_cover', 0 ) ) {
					echo '<div class="ps-focus__title-after">', do_action( 'peepso_profile_cover_full_after_name', $PeepSoUser->get_id() ), '</div>';
				}
				?>
			</div>
			<div class="ps-focus__details ps-js-focus-interactions">
				<?php $PeepSoProfile->interactions(); ?>
			</div>
			<div class="ps-focus__mobile-actions ps-js-focus-actions"><?php $PeepSoProfile->profile_actions(); ?></div>
			<div class="ps-focus__actions ps-js-profile-actions-extra">
				<?php $PeepSoProfile->profile_actions_extra(); ?>
			</div>
		</div>

		<?php
		do_action( 'peepso_action_render_user_menu_before', $PeepSoUser->get_id() );
		?>

		<?php

		if ( ! $is_profile_segment ) {
			$current = 'stream';
		}

		?>
		<div class="ps-focus__menu ps-js-focus__menu">
			<div class="ps-focus__menu-inner ps-js-focus__menu-inner">
				<?php echo $PeepSoProfile->profile_navigation( array( 'current' => $current ) ); ?>
			</div>
			<div class="ps-focus__menu-shadow ps-focus__menu-shadow--left ps-js-aid-left"></div>
			<div class="ps-focus__menu-shadow ps-focus__menu-shadow--right ps-js-aid-right"></div>
		</div>
	</div>
</div>
