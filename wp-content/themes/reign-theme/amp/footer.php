<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Reign
 */
?>
</div><!-- #content -->
<?php
$reign_footer_footer_type = get_theme_mod( 'reign_footer_footer_type', false );
if ( ! $reign_footer_footer_type ) { ?>
    <footer itemscope="itemscope" itemtype="http://schema.org/WPFooter">
        <?php
        if ( is_active_sidebar( 'footer-widget-area' ) ) {
                ?>
                <div class="footer-wrap">
                        <div class="amp-container">
                                <aside id="footer-area" class="widget-area footer-widget-area" role="complementary">
                                        <div class="widget-area-inner">
                                                <div class="wb-grid">
                                                        <?php dynamic_sidebar( 'footer-widget-area' ); ?>
                                                </div>
                                        </div>
                                </aside>
                        </div>
                </div>
                <?php
        }
        ?>
        <?php
        $reign_footer_bottom = get_theme_mod( 'reign_footer_copyright_enable', true );
        if ( $reign_footer_bottom ) {
                $reign_footer_copyright_text = get_theme_mod( 'reign_footer_copyright_text', '&copy; '. date( 'Y' ) .' - Reign | Theme by <a href="' . esc_url( 'https://wbcomdesigns.com/' ) . '" target="_blank">Wbcom Designs</a>' );
                ?>
                <div id="reign-copyright-text">
                        <div class="container">
                                <?php echo $reign_footer_copyright_text; ?>
                        </div>	
                </div>
                <?php
        }
        ?>
    </footer>
<?php }
?>
	
</div><!-- #page -->
<?php get_template_part( 'amp/off-canvas' ); ?>
<div class="reign-amp-sidebar-mask"></div>
<?php wp_footer(); ?>
</body>
</html>