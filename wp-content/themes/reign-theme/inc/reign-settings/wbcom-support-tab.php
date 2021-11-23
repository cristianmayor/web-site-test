<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Wbcom_Support_Tab' ) ) :

	/**
	 * @class Reign_Wbcom_Support_Tab
	 */
	class Reign_Wbcom_Support_Tab {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Wbcom_Support_Tab
		 */
		protected static $_instance	 = null;
		protected static $_slug		 = 'wbcom-support';

		/**
		 * Main Reign_Wbcom_Support_Tab Instance.
		 *
		 * Ensures only one instance of Reign_Wbcom_Support_Tab is loaded or can be loaded.
		 *
		 * @return Reign_Wbcom_Support_Tab - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Wbcom_Support_Tab Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_filter( 'alter_reign_admin_tabs', array( $this, 'alter_reign_admin_tabs' ), 50, 1 );
			add_action( 'render_content_after_form', array( $this, 'render_get_started_with_customization_section' ), 10, 1 );

			add_action( 'admin_menu', array( $this, 'add_reign_setting_submenu' ), 50 );
		}

		public function add_reign_setting_submenu() {
			add_submenu_page(
			'reign-settings', __( 'Support', 'reign' ), __( 'Support', 'reign' ), 'manage_options', admin_url( 'admin.php?page=reign-options&tab=' . self::$_slug )
			);
		}

		public function alter_reign_admin_tabs( $tabs ) {
			$tabs[ self::$_slug ] = __( 'Support', 'reign' );
			return $tabs;
		}

		public function render_get_started_with_customization_section( $tab ) {
			if ( $tab != self::$_slug ) {
				return;
			}
			?>
			<style type="text/css">
				div#poststuff {
					display: none;
				}

				.reign_support_faq {
					background: #fff;
					padding: 40px;
					overflow: hidden;
				}

				.reign_support_faq .panel_left {
					float: left;
					width: 55%;
				}

				.reign_support_faq p {
					font-size: 18px;
				}

				.reign_support_faq h2 {
					font-size: 22px;
					font-weight: 700;
					line-height: 1;
					margin: 0 0 1em;
					padding-bottom: 1em;
					color: #333;
					border-bottom: 1px solid #eaeaea;
					position: relative;
				}

				.reign_support_faq h2:before {
					content: "";
					font-family: dashicons;
					font-size: inherit;
					left: 0;
					background-color: #f6f6f6;
					height: 38px;
					width: 38px;
					border-radius: 50px;
					vertical-align: middle;
					margin-right: 15px;
					display: -webkit-inline-box;
					display: -ms-inline-flexbox;
					display: inline-flex;
					-webkit-box-align: center;
					-ms-flex-align: center;
					align-items: center;
					justify-content: center;
					-webkit-box-pack: center;
					-ms-flex-pack: center;
					justify-content: center;
				}

				.reign_support_faq #faq-demo h2:before {
					content: "\f330";
				}

				.reign_support_faq #faq-create-ticket h2:before {
					content: "\f524";
				}

				.reign_support_faq #faq-custom-development h2:before {
					content: "\f475";
				}

				.reign_support_faq #faq-child-theme h2:before {
					content: "\f501";
				}

				.reign_support_faq .support_link_Section {
					position: relative;
					padding-top: 40px;
				}

				.reign_support_faq .panel-right {
					float: right;
					width: 40%;
				}

				.reign_support_faq .panel-right img {
					max-width: 100%;
				}

				#faq-demo iframe {
					width: 100%;
				}

				@media (max-width: 769px) {
					.reign_support_faq .panel-right,
					.reign_support_faq .panel_left {
						width: 100%;
					}
				}
			</style>

			<script type="text/javascript">

			    // Select all links with hashes
			    jQuery( document ).ready( function () {
			        // Add smooth scrolling to all links
			        jQuery( "a" ).on( 'click', function ( event ) {

			            // Make sure this.hash has a value before overriding default behavior
			            if ( this.hash !== "" ) {
			                // Prevent default anchor click behavior
			                event.preventDefault();

			                // Store hash
			                var hash = this.hash;

			                // Using jQuery's animate() method to add smooth page scroll
			                // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
			                jQuery( 'html, body' ).animate( {
			                    scrollTop: jQuery( hash ).offset().top
			                }, 800, function () {

			                    // Add hash (#) to URL when done scrolling (default click behavior)
			                    window.location.hash = hash;
			                } );
			            } // End if
			        } );
			    } );
			</script>

			<div class="reign_support_faq">
				<div class="panel_left">
					<div id="faq-demo" class="support_link_Section">
						<h2><?php _e( 'How to import demo data?', 'reign' ); ?></h2>
						<p><?php _e( 'The theme package includes demo data importer plugin allowing you to get started with ready to use website in just a few clicks. Find below the related video attached for a closer look and better understanding. For glance around the theme reach us to our knowledge base by <a href="https://wbcomdesigns.com/reign-knowledge-base/" target="_blank">click here</a>.', 'reign' ); ?></p>
						<p><?php _e( 'Watch the video below for getting quick idea.', 'reign' ); ?></p>
						<iframe width="560" height="315" src="https://www.youtube.com/embed/mVJhzckYupE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>
				<div class="panel-right">
					<div id="faq-create-ticket" class="support_link_Section">
						<h2><?php _e( 'Create ticket', 'reign' ); ?></h2>
						<p><?php _e( 'We care about all our clients. Feel free to reach our <a href="https://wbcomdesigns.com/support/" target="_blank">support</a> for any kind of help, we would be only too happy to answer. Always there for you in need.', 'reign' ); ?></p>
					</div>
					<div id="faq-custom-development" class="support_link_Section">
						<h2><?php _e( 'Custom development', 'reign' ); ?></h2>
						<p><?php _e( '<a href="https://wbcomdesigns.com/start-a-project/" target="_blank">Hire us!</a> For anything in WordPress and Magento. From blueprint to development, from speed optimization to marketing we are here to help.</a>. Our team will love to help you.', 'reign' ); ?></p>
					</div>
					<div id="faq-child-theme" class="support_link_Section">
						<h2><?php _e( 'Reign child theme', 'reign' ); ?></h2>
						<p><?php _e( 'If you are going to be making advanced edits to this theme, the best way is to use a child theme. Luckily I’ve already created a child theme for you. You can download the <a href="https://wbcomdesigns.com/wp-content/uploads/2020/01/reign-child-theme.zip" target="_blank">child theme here</a>.', 'reign' ); ?></p>
						<p><?php _e( 'Also you get the child theme from <a href="https://github.com/wbcomdesigns/reign-child-theme" target="_blank">github</a>.', 'reign' ); ?></p>
					</div>
				</div>
			</div>
			<?php
		}

	}

	endif;

/**
 * Main instance of Reign_Wbcom_Support_Tab.
 * @return Reign_Wbcom_Support_Tab
 */
Reign_Wbcom_Support_Tab::instance();
