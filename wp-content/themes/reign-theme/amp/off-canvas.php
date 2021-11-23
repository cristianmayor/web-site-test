<?php
/**
 * The template part for displaying the off-canvas area of AMP.
 *
 * @package reign
 */
?>
<amp-sidebar id="reign-amp-canvas" layout="nodisplay" side="left">
    <div role="button" aria-label="close sidebar" on="tap:reign-amp-canvas.toggle" tabindex="0" class="close-sidebar">✕</div>
    <nav id="amp-site-navigation" class="amp-main-navigation" role="navigation">
        <?php wp_nav_menu(array('theme_location' => 'menu-1', 'menu_id' => 'amp-primary-menu', 'fallback_cb' => '', 'container' => false, 'menu_class' => 'amp-primary-menu',)); ?>
    </nav>
</amp-sidebar>