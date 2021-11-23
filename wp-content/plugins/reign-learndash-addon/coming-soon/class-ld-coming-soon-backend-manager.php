<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'LD_Coming_Soon_Backend_Manager' ) ) :

/**
 * @class LD_Coming_Soon_Backend_Manager
 */
class LD_Coming_Soon_Backend_Manager {

	/**
	 * The single instance of the class.
	 *
	 * @var LD_Coming_Soon_Backend_Manager
	 */
	protected static $_instance = null;

	/**
	 * Main LD_Coming_Soon_Backend_Manager Instance.
	 *
	 * Ensures only one instance of LD_Coming_Soon_Backend_Manager is loaded or can be loaded.
	 *
	 * @return LD_Coming_Soon_Backend_Manager - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * LD_Coming_Soon_Backend_Manager Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 15 );
		add_action( 'save_post', array( $this, 'save_post_meta' ), 10, 1 );

		add_action( 'admin_print_scripts-post-new.php', array( $this, 'enqueue_ignify_metabox_style_n_script' ), 11 );
		add_action( 'admin_print_scripts-post.php', array( $this, 'enqueue_ignify_metabox_style_n_script' ), 11 );


		add_filter( 'reign_leardash_manage_course_proceed_default', array( $this, 'enqueue_ignify_metabox_style_n_script' ), 11 );
		add_filter( 'learndash_header_tab_menu', array( $this, 'reign_ld_manage_metaboxes' ), 10, 4 );

	}

	public function reign_ld_manage_metaboxes( $header_data_tabs, $menu_tab_key, $screen_post_type ) {
		foreach ( $header_data_tabs as $key => $data_tabs ) {
			if ( 'sfwd-courses-settings' === $data_tabs['id'] ) {
				array_push( $header_data_tabs[$key]['metaboxes'], 'learndash_course_banner_title' );
				array_push( $header_data_tabs[$key]['metaboxes'], 'learndash_custom_course_features' );
			}
		}
		return $header_data_tabs;
	}

	public function enqueue_ignify_metabox_style_n_script() {

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');

		wp_enqueue_style(
			'rl-cc-datepicker-jquery-ui',
			LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/css/datepicker-jquery-ui.css',
			array(),
			time(),
			'all'
		);

		wp_register_style(
			$handle	 = 'reign-tooltip-css',
			$src	 = get_template_directory_uri() . '/assets/css/reign-tooltip.css',
			$deps	 = array(),
			$ver	 = time(),
			$media	 = 'all'
		);
		wp_enqueue_style( 'reign-tooltip-css' );

		wp_register_script(
			$handle  = 'reign_learndash_backend_js',
			$src     = LearnMate_LearnDash_Addon_PLUGIN_DIR_URL . 'assets/js/reign-learndash-backend.js',
			$deps    = array( 'jquery' ),
			$ver     = time(),
			$in_footer = true
		);
		wp_localize_script(
			'reign_learndash_backend_js',
			'reign_learndash_backend_js_params',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'home_url'	 => get_home_url(),
			)
		);
		wp_enqueue_script( 'reign_learndash_backend_js' );
	}

	
	public function render_custom_course_features_meta_box($post ) {
		$post_id = $post->ID;
		$fontawesome_icons = reign_learndash_fontawesome_icons();
		
		$rla_ccf_enable = get_post_meta( $post_id, 'rla_ccf_enable', true );
		$rla_ccf_features = get_post_meta( $post_id, 'rla_ccf_features', true );		
		?>
		<div class="rla-cs-coming-soon-panel">

			<div class="rla-option-wrapper">
				<div class="rla-label">
					<?php _e( 'Enable', 'reign-learndash-addon' ); ?>
				</div>
				<div class="rla-input">
					<input type="checkbox" name="rla_ccf_enable" value="yes" <?php checked( $rla_ccf_enable, 'yes', true ); ?> />
				</div>
				<div class="rtm-tooltip">?
					<span class="rtm-tooltiptext">
					<?php _e( 'Enable custom course features.', 'reign-learndash-addon' ); ?>
					</span>
				</div>
			</div>
			<div class="rla-option-wrapper">
				<div class="rla-custom-features">
					<table id="custom-course-feature-lists">
						<thead>
							<tr>
								<th><?php esc_html_e('Icon', 'reign-learndash-addon');?></th>
								<th><?php esc_html_e('Feature', 'reign-learndash-addon');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<?php if ( !empty($rla_ccf_features)): ?>
								<?php for($i=0; $i<sizeof($rla_ccf_features['icon']); $i++ ):?>
									<tr>
										<td>
											<select name="rla_ccf_features[icon][]" class="reign-select2-element">
											<?php foreach( $fontawesome_icons as $icons):
												$icon_html = '<i class="' . $icons . '"></i>';
											?>
												<option value="<?php echo $icons?>" <?php selected( $rla_ccf_features['icon'][$i] , $icons)?>><?php echo $icons;?></option>
											<?php endforeach;?>
											</select>
										</td>
                                                                                <td class="rla_ccf_features">
											<input type="text" name="rla_ccf_features[text][]" value="<?php echo $rla_ccf_features['text'][$i];?>" />
										</td>
										<td>
											<span class="rla-delete-course-feature dashicons dashicons-no-alt"></span>
										</td>								
									</tr>
								<?php endfor;?>
								
							<?php else : ?>
								<tr>
									<td>
										<select name="rla_ccf_features[icon][]">
										<?php foreach( $fontawesome_icons as $icons):
											$icon_html = '<i class="' . $icons . '"></i>';
										?>
											<option value="<?php echo $icons?>" <?php selected( $social_channel_value['fontawesome_icon'] , $icons)?>><?php echo $icons;?></option>
										<?php endforeach;?>
										</select>
									</td>
									<td>
										<input type="text" name="rla_ccf_features[text][]" value="" />
									</td>
									<td>
										<a href="" class="rla-delete-course-feature">X</a>
									</td>								
								</tr>
							<?php endif;?>
						</tbody>
					</table>
					<button class="rla-add-course-feature"><?php esc_html_e('Add Feature', 'reign-learndash-addon');?></button>
				</div>
			</div>
			
		</div>
		<?php
		
	}
	public function render_custom_course_layout( $post ) {
		$post_id = $post->ID;
		$course_layout = get_post_meta( $post_id, 'rla_course_layout', true );	
		?>
		<ul>
			<li>
				<label>
					<input type="radio" name="rla_course_layout" value="default" <?php checked($course_layout, 'default', true); ?> />&nbsp;<?php _e('Default', 'reign-learndash-addon'); ?>
				</label>
			</li>
			<li>
				<label>
					<input type="radio" name="rla_course_layout" value="udemy" <?php checked($course_layout, 'udemy', true); ?>/>&nbsp;<?php _e('Udemy', 'reign-learndash-addon'); ?>
				</label>
			</li>
			<li>
				<label>
					<input type="radio" name="rla_course_layout" value="teachable"<?php checked($course_layout, 'teachable', true); ?> />&nbsp;<?php _e('Teachable', 'reign-learndash-addon'); ?>
				</label>
			</li>
		</ul>	
		<p class="description"><?php _e('Choose single course layout.', 'reign-learndash-addon'); ?></p>
		<?php
		
	}
	public function add_meta_box() {		
		
		add_meta_box(
			'learndash_custom_course_features',
			__( 'Custom Course Features', 'reign-learndash-addon' ),
			array( $this, 'render_custom_course_features_meta_box' ),
			array( 'sfwd-courses' )
		);
		
		add_meta_box(
			'learndash_course_layout',
			__( 'Course Layout', 'reign-learndash-addon' ),
			array( $this, 'render_custom_course_layout' ),
			array( 'sfwd-courses' ),
			'side'
		);
	}

	public function save_post_meta( $post_id ) {
		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		if( isset( $_POST['post_type'] ) && ( $_POST['post_type'] == 'sfwd-courses' ) ) {
			
			if( isset( $_POST['rla_ccf_enable'] ) ) {
				update_post_meta( $post_id, 'rla_ccf_enable', 'yes' );
			} else {
				update_post_meta( $post_id, 'rla_ccf_enable', '' );
			}
			
			update_post_meta( $post_id, 'rla_ccf_features', $_POST['rla_ccf_features'] );
			
			update_post_meta( $post_id, 'rla_course_layout', $_POST['rla_course_layout'] );

			do_action( 'rla_cs_save_coming_soon_options' );
		}
	}

}

endif;

/**
 * Main instance of LD_Coming_Soon_Backend_Manager.
 * @return LD_Coming_Soon_Backend_Manager
 */
LD_Coming_Soon_Backend_Manager::instance();
