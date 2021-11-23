<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'RTM_LD_Buy_Course_Handler' ) ) :

/**
 * @class RTM_LD_Buy_Course_Handler
 */
class RTM_LD_Buy_Course_Handler {
	
	/**
	 * The single instance of the class.
	 *
	 * @var RTM_LD_Buy_Course_Handler
	 */
	protected static $_instance = null;
	
	/**
	 * Main RTM_LD_Buy_Course_Handler Instance.
	 *
	 * Ensures only one instance of RTM_LD_Buy_Course_Handler is loaded or can be loaded.
	 *
	 * @return RTM_LD_Buy_Course_Handler - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * RTM_LD_Buy_Course_Handler Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_filter( 'learndash_payment_closed_button', array( $this, 'reign_ld_payment_closed_button' ), 15, 2 );


		add_action( 'wp_ajax_nopriv_rtm_ld_atc_woo_product', array( $this, 'rtm_ld_atc_woo_product' ) );
		add_action( 'wp_ajax_rtm_ld_atc_woo_product', array( $this, 'rtm_ld_atc_woo_product' ) );
	}

	public function rtm_ld_atc_woo_product() {
		$product_id = isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0;
		if( isset( $_POST['buy_using'] ) && !empty( $_POST['buy_using'] ) ) {
			if( 'woocommerce' === $_POST['buy_using'] ) {
				$quantity = 1;
				$variation_id = 0;
				$variation = array();
				$cart_item_data = array();
				WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );
			}
			elseif( 'easydigitaldownload' === $_POST['buy_using'] ) {
				$_POST['download_id'] = $product_id;
				$_POST['price_ids'] = array( $product_id );
				$_POST['post_data'] = 'download_id=' . $product_id . '&edd_action=add_to_cart';
				edd_ajax_add_to_cart();
			}
		}
		wp_die();
	}

	public function reign_ld_payment_closed_button( $custom_button, $payment_params ) {
		$course_id = $payment_params['post']->ID;
		$custom_button_url = $payment_params['custom_button_url'];
		$associated_pro_id = url_to_postid( $custom_button_url );
		if( $associated_pro_id ) {
			if( class_exists( 'WooCommerce' ) && class_exists( 'learndash_woocommerce' ) ) { // manage WooCommerce
				$associated_pro = wc_get_product( $associated_pro_id );
				if( $associated_pro ) {
					$pre_custom_button = '<div class="rtm-ld-common-buy-now rtm-ld-woo-buy-now">';
					if( TRUE ) {
						$pre_custom_button .= '<input type="hidden" value="' . $associated_pro_id . '" id="rtm_ld_associated_pro_id" />';
						$redirect_url = wc_get_cart_url();
						$redirect_url = wc_get_checkout_url();
						$pre_custom_button .= '<input type="hidden" value="' . $redirect_url . '" id="rtm_ld_atc_redirect_url" />';
						$pre_custom_button .= '<input type="hidden" value="woocommerce" id="rtm_ld_buy_using" />';
					}
					$post_custom_button = '</div>';
					$custom_button = $pre_custom_button . $custom_button . $post_custom_button;
				}
			}
			
			if( class_exists( 'LearnDash_EDD' ) && class_exists( 'Easy_Digital_Downloads' ) ) { // manage Easy Digital Downloads
				$associated_pro = new EDD_Download( $associated_pro_id );
				if( $associated_pro->ID ) {
					$pre_custom_button = '<div class="rtm-ld-common-buy-now rtm-ld-edd-buy-now">';
					if( TRUE ) {
						$pre_custom_button .= '<input type="hidden" value="' . $associated_pro_id . '" id="rtm_ld_associated_pro_id" />';
						$redirect_url = edd_get_checkout_uri();
						$pre_custom_button .= '<input type="hidden" value="' . $redirect_url . '" id="rtm_ld_atc_redirect_url" />';
						$pre_custom_button .= '<input type="hidden" value="easydigitaldownload" id="rtm_ld_buy_using" />';
					}
					$post_custom_button = '</div>';
					$custom_button = $pre_custom_button . $custom_button . $post_custom_button;
				}
			}
		}
		else {
			$pre_custom_button = '<div class="rtm-ld-common-buy-now rtm-ld-extra-buy-now">';
			$post_custom_button = '</div>';
			$custom_button = $pre_custom_button . $custom_button . $post_custom_button;
		}

		if( defined( 'PMPRO_VERSION' ) && class_exists( 'Learndash_Paidmemberships' ) ) { // manage Paid Memberships Pro
			$course_id = learndash_get_course_id( $payment_params['post']->ID );
			$level_course_option = get_option( '_level_course_option', array() );
			if( !empty( $level_course_option ) ) {
				$array_levels = explode( ",", $level_course_option[$course_id] );
				if( !empty( $array_levels ) ) {
					global $pmpro_pages;
					if( isset( $pmpro_pages['levels'] ) && !empty( $pmpro_pages['levels'] ) ) {
						$pmpro_buy_membership_url = get_permalink( $pmpro_pages['levels'] );
						$pmpro_buy_membership_url = add_query_arg( 'course_id', $course_id, $pmpro_buy_membership_url );
						$pmpro_buy_membership_url = apply_filters( '', $pmpro_buy_membership_url, $course_id, $level_course_option, $array_levels );
						ob_start();
						?>
						<div class="rtm-ld-common-buy-now rtm-ld-pmpro-buy-now">
							<a class="btn-join rtm-ld-pmpro-btn-join" href="<?php echo $pmpro_buy_membership_url; ?>" id="rtm-ld-pmpro-btn-join"><?php _e( 'Buy Membership', 'reign-learndash-addon' ); ?></a>
						</div>
						<?php
						$pmpro_buy_now = ob_get_clean();
						$custom_button = $custom_button . $pmpro_buy_now;
					}
				}
			}
		}
		return '<div class="lm-course-buy-now">' . $custom_button . '</div>';
	}

}

endif;

/**
 * Main instance of RTM_LD_Buy_Course_Handler.
 * @return RTM_LD_Buy_Course_Handler
 */
RTM_LD_Buy_Course_Handler::instance();