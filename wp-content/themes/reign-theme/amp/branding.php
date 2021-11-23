<?php
/**
 * The branding for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Reign
 */
?>

<header class="reign-amp-header">
    <div class="amp-container">
        <div class="amp-header-inner">
            <div role="button" on="tap:reign-amp-canvas.toggle" tabindex="0" class="hamburger amp-nav-menu-toggle">☰</div>
            <div class="amp-header-wrapper">
                <div class="amp-site-branding">
                    <div class="logo">
                        <?php
                        if (function_exists('the_custom_logo') && has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            if (is_front_page() && is_home()) :
                                ?>
                                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                            <?php else : ?>
                                <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                                <?php
                                endif;
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>