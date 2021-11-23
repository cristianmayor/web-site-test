<?php
echo $args['before_widget'];

$PeepSoProfile = PeepSoProfile::get_instance();
$PeepSoUser    = $PeepSoProfile->user;

?>

	<div class="psw-profile 
	<?php
	if ( isset( $instance['show_cover'] ) && 1 == intval( $instance['show_cover'] ) ) {
		?>
		psw-profile--cover
																	<?php
	}
	?>
							 ps-js-widget-me">
		<!-- Title of Profile Widget -->
		<?php
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		?>

		<?php
		if ( $instance['user_id'] > 0 ) {
			$user = $instance['user'];

			if ( $instance['user_id'] > 0 && $instance['user_id'] == get_current_user_id() ) {
				$user->profile_fields->load_fields();
				$stats = $user->profile_fields->profile_fields_stats;
			}
			$cover = reign_get_peepso_member_cover_image();

			if ( empty( $cover ) ) {
				$cover = reign_render_peepso_member_cover_image();
			}
			?>
			<div class="psw-profile__header">
				<div class="psw-profile__avatar">
					<?php if ( isset( $instance['show_cover'] ) && 1 == intval( $instance['show_cover'] ) ) { ?>
					<div class="psw-profile__cover ps-js-widget-me-cover" style="background-image:url(<?php echo $cover; ?>);"></div>
					<?php } ?>
					<a class="ps-avatar psw-avatar--profile" href="<?php echo $user->get_profileurl(); ?>">
						<img class="ps-js-widget-me-avatar" src="<?php echo $user->get_avatar(); ?>"
							title="<?php echo $user->get_profileurl(); ?>"
							alt="<?php printf( __( '%s avatar', 'peepso-core' ), $user->get_fullname() ); ?>" />
					</a>
				</div>

				<div class="psw-profile__meta">
					<div class="psw-profile__title" data-hover-card="<?php echo $user->get_id(); ?>">
						<?php
						// [peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
						do_action( 'peepso_action_render_user_name_before', $user->get_id() );

						echo $user->get_fullname();

						// [peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
						do_action( 'peepso_action_render_user_name_after', $user->get_id() );
						?>
					</div>
					<div class="ps-notifs psw-notifs--profile ps-js-widget-me-notifications">
						<?php echo $instance['toolbar']; ?>
					</div>
				</div>

				<!-- Profile Completeness -->
				<?php

				$hide_progress = true;
				if ( isset( $stats ) && $stats['fields_all'] > 0 ) {
					if ( $stats['completeness'] < 100 ) {
						$hide_progress = false;
					}
				}

				?>
				<div class="psw-profile__progress ps-js-widget-me-completeness" 
				<?php
				if ( $hide_progress ) {
					echo 'style="display:none"';
				}
				?>
																				>
					<div class="psw-profile__progress-message ps-js-status">
						<?php
						echo $stats['completeness_message'];
						do_action( 'peepso_action_render_profile_completeness_message_after', $stats );
						?>
					</div>

					<div class="psw-profile__progress-bar ps-js-progressbar"><span style="width:<?php echo $stats['completeness']; ?>%"></span></div>
				</div>
			</div>
			<?php
			// [peepso]_[action]_[WHICH_PLUGIN]_[WHERE]_[WHAT]_[BEFORE/AFTER]
			do_action( 'peepso_action_widget_profile_name_after', $instance['user_id'] );
			?>

			<div class="psw-profile__menu-title">
				<?php echo __( 'My Profile', 'peepso-core' ); ?>
			</div>

			<div class="psw-profile__menu">
				<?php
				// Profile Submenu extra links
				$instance['links']['peepso-core-preferences'] = array(
					'href'  => $user->get_profileurl() . 'about/preferences/',
					'icon'  => 'gcis gci-cog',
					'label' => __( 'Preferences', 'peepso-core' ),
				);

				// @todo #2274 this has to be peepso_navigation_profile
				// if(class_exists('PeepSoPMP')) {
				// $instance['links']['peepso-pmp'] = array(
				// 'href' => pmpro_url("account"),
				// 'label' => __('Membership', 'peepso-pmp'),
				// 'icon' => 'ps-icon-vcard',
				// );
				// }

				$instance['links']['peepso-core-logout'] = array(
					'href'   => PeepSo::get_page( 'logout' ),
					'icon'   => 'gcis gci-power-off',
					'label'  => __( 'Log Out', 'peepso-core' ),
					'widget' => true,
				);

				if ( isset( $instance['show_community_links'] ) && $instance['show_community_links'] === 1 ) {
					$instance['community_links']['peepso-core-logout'] = $instance['links']['peepso-core-logout'];
					unset( $instance['links']['peepso-core-logout'] );
				}

				foreach ( $instance['links'] as $id => $link ) {
					if ( ! isset( $link['label'] ) || ! isset( $link['href'] ) || ! isset( $link['icon'] ) ) {
						var_dump( $link );
					}

					$class = isset( $link['class'] ) ? $link['class'] : '';

					$href = $user->get_profileurl() . $link['href'];
					if ( 'http' == substr( strtolower( $link['href'] ), 0, 4 ) ) {
						$href = $link['href'];
					}

					echo '<a href="' . $href . '" class="psw-profile__menu-item ' . $class . '"><i class="' . $link['icon'] . '"></i> ' . $link['label'] . '</a>';
				}
				?>
			</div>

			<?php if ( isset( $instance['show_community_links'] ) && $instance['show_community_links'] === 1 ) { ?>
			<div class="psw-profile__menu-title">
				<?php echo __( 'Community', 'peepso-core' ); ?>
			</div>

			<div class="psw-profile__menu">
				<?php
				foreach ( $instance['community_links'] as $link ) {
					if ( false == $link['widget'] ) {
						continue;
					}

					$class = isset( $link['class'] ) ? $link['class'] : '';
					echo '<a href="' . $link['href'] . '" class="psw-profile__menu-item ' . $class . '"><i class="' . $link['icon'] . '"></i> ' . $link['label'] . '</a>';

				}
				?>
			</div>
			<?php } ?>
		<?php } else { ?>

			<div class="psf-login">
				<form class="ps-form ps-form--login ps-js-form-login" action="" onsubmit="return false;" method="post" name="login" id="ps-form-login-me">
					<!-- Login -->
					<div class="ps-form__row ps-js-username-field">
						<div class="ps-form__field ps-form__field--icon">
							<input class="ps-input ps-input--sm ps-input--icon" type="text" name="username" placeholder="<?php echo __( 'Username', 'peepso-core' ); ?>" mouseev="true"
								   autocomplete="off" keyev="true" clickev="true" />
							<i class="gcis gci-user"></i>
						</div>
					</div>

					<!-- Password -->
					<div class="ps-form__row ps-js-password-field">
						<div class="ps-form__field ps-form__field--icon">
							<input class="ps-input ps-input--sm ps-input--icon" type="password" name="password" placeholder="<?php echo __( 'Password', 'peepso-core' ); ?>" mouseev="true"
								   autocomplete="off" keyev="true" clickev="true" />
							<i class="gcis gci-key"></i>
						</div>
					</div>

					<?php include_once ABSPATH . 'wp-admin/includes/plugin.php'; ?>
					<?php if ( PeepSo::two_factor_plugin_enabled() /* is_plugin_active('two-factor-authentication/two-factor-login.php') */ ) { ?>
						<!-- Two Factor authentication -->
						<div class="ps-form__row ps-js-password-field">
							<div class="ps-form__field ps-form__field--icon">
								<input class="ps-input ps-input--sm ps-input--icon" type="password" name="password" placeholder="<?php echo __( 'Password', 'peepso-core' ); ?>" mouseev="true"
									   autocomplete="off" keyev="true" clickev="true" />
								<i class="gcis gci-fingerprint"></i>
							</div>
						</div>
					<?php } ?>

					<!-- Remember password -->
					<div class="ps-form__row ps-js-password-field">
						<div class="ps-form__field ps-form__field--checkbox">
							<div class="ps-checkbox ps-checkbox--login">
								<input class="ps-checkbox__input" type="checkbox" alt="<?php echo __( 'Remember Me', 'peepso-core' ); ?>" value="yes" name="remember" id="ps-form-login-me-remember" <?php echo PeepSo::get_option( 'site_frontpage_rememberme_default', 0 ) ? ' checked' : ''; ?>>
								<label class="ps-checkbox__label" for="ps-form-login-me-remember"><?php echo __( 'Remember Me', 'peepso-core' ); ?></label>
							</div>
						</div>
					</div>

					<!-- Submit form -->
					<div class="ps-form__row ps-js-password-field">
						<div class="ps-form__field ps-form__field--submit">
							<button type="submit" class="ps-btn ps-btn--sm ps-btn--action ps-btn--login ps-btn--loading">
								<span><?php echo __( 'Login', 'peepso-core' ); ?></span>
								<img src="<?php echo PeepSo::get_asset( 'images/ajax-loader.gif' ); ?>">
							</button>
						</div>
					</div>

					<input type="hidden" name="option" value="ps_users">
					<input type="hidden" name="task" value="-user-login">
					<input type="hidden" name="redirect_to" value="<?php echo PeepSo::get_page( 'redirectlogin' ); ?>" />
					<?php
					// Remove ID attribute from nonce field.
					$nonce = wp_nonce_field( 'ajax-login-nonce', 'security', true, false );
					$nonce = preg_replace( '/\sid="[^"]+"/', '', $nonce );
					echo $nonce;
					?>

					<?php do_action( 'peepso_action_render_login_form_after' ); ?>
				</form>

				<?php do_action( 'peepso_after_login_form' ); ?>

				<div class="psf-login__links">
					<?php
					$disable_registration = intval( PeepSo::get_option( 'site_registration_disabled', 0 ) );

					// PeepSo/peepso#2906 hide "resend activation" until really necessary
					$hide_resend_activation = true;
					?>

					<?php if ( 0 === $disable_registration ) { ?>
						<a class="psf-login__link psf-login__link--register" href="<?php echo PeepSo::get_page( 'register' ); ?>"><?php echo __( 'Register', 'peepso-core' ); ?></a>
					<?php } ?>

					<a class="psf-login__link psf-login__link--recover" href="<?php echo PeepSo::get_page( 'recover' ); ?>"><?php echo __( 'Forgot Password', 'peepso-core' ); ?></a>

					<?php if ( 0 === $disable_registration ) { ?>
						<a class="psf-login__link psf-login__link--activation ps-js-register-activation" href="<?php echo PeepSo::get_page( 'register' ); ?>?resend"><?php echo __( 'Resend activation code', 'peepso-core' ); ?></a>
					<?php } ?>
				</div>
			</div>

			<script>
				(function() {
					function initLoginForm( $ ) {
						$('.ps-js-form-login').off('submit').on('submit', function( e ) {
							e.preventDefault();
							e.stopPropagation();
							peepso.login.submit( e.target );
						});
					}

					// naively check if jQuery exist to prevent error
					var timer = setInterval(function() {
						if ( window.jQuery ) {
							clearInterval( timer );
							initLoginForm( window.jQuery );
						}
					}, 1000 );
				})();
			</script>

			<?php
		}
		?>
	</div>

<?php
echo $args['after_widget'];

if ( PeepSo::is_dev_mode() ) {
	include 'developer.php';
}
// EOF
