<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Reign_Grid_Layout' ) ) :

/**
 * @class Reign_Grid_Layout
 */
class Reign_Grid_Layout {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Reign_Grid_Layout
	 */
	protected static $_instance = null;
	
	/**
	 * Main Reign_Grid_Layout Instance.
	 *
	 * Ensures only one instance of Reign_Grid_Layout is loaded or can be loaded.
	 *
	 * @return Reign_Grid_Layout - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Reign_Grid_Layout Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_shortcode( 'reign_grid_layout', array( $this, 'render_reign_grid_layout' ) );
	}

	public function render_reign_grid_layout() {
		ob_start();
		?>

		<div class="wb_reign">

			<div class="reign-post-grid">

				<div class="reign-post-grid-wrap">

					<div class="reign-grid-listing ">

						<div class="reign-post-grid-item">

	                        <div class="reign-grid-item-inner post-grid-header">

	                            <div class="reign-grid-item-content">

	                                <h1>Course Grid</h1>                                
	                                <p>This shortcode present courses in grid layout.</p>       
	                                <a class="reign-btn-link" href="#">All Course <i class="fa fa-angle-right"></i></a>

	                            </div>

	                        </div>

	                    </div>

	                    <div class="reign-post-grid-item">

	                            <div class="reign-grid-item-inner">

	                                <div class="reign-grid-item-content">

	                                    <div class="reign-post-thumbnail">
	                                        <a href="#" title="">
	                                            <img src="http://university.cactusthemes.com/wp-content/uploads/2014/10/002-554x674.jpg" title="" alt="">
	                                        </a>
	                                    </div>

	                                    <div class="reign-grid-date ">
	                                        <div class="month">May</div>
	                                        <div class="day">12</div>
	                                    </div>

										<div class="reign-grid-overlay">
	                                        <a class="reign-grid-overlay-top" href="#" title="Chemical Engineering">
	                                            <h4>Chemical Engineering</h4>
	                                            <span class="reign-grid-price">£1999</span>
	                                        </a>
	                                        <div class="reign-grid-overlay-bottom">
	                                        	<div class="reign-exceprt">Ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis</div>                                          
	                                        </div>

	                                    </div>

	                                </div>

	                            </div>

						</div>	

					</div>	

				</div>

			</div>	

		</div>


		<?php
		$html = ob_get_clean();
		return $html;
	}

}

endif;

/**
 * Main instance of Reign_Grid_Layout.
 * @return Reign_Grid_Layout
 */
Reign_Grid_Layout::instance();