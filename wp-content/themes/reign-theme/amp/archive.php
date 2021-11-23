<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Reign
 */
 
get_template_part( 'amp/header'); ?>

<?php do_action( 'reign_amp_before_content_section' ); ?>

<div class="amp-container">
    
    <div class="content-wrapper">
            <?php if ( have_posts() ) : ?>
                    <?php                    
                    $blog_list_layout = get_theme_mod( 'reign_blog_list_layout', 'default-view' );
                    if ( $blog_list_layout == 'masonry-view' ) {
                            echo '<div class="masonry wb-post-listing">';
                    } else if ( $blog_list_layout == 'wb-grid-view' ) {
                            echo '<div class="wb-grid-view-wrap wb-post-listing">';
                    } else {
                            echo '<div class="wb-lists-view-wrap wb-post-listing">';
                    }
                    /* Start the Loop */
                    while ( have_posts() ) : the_post();

                            /*
                             * Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'template-parts/content', get_post_format() );

                    endwhile;

                    if ( $blog_list_layout == 'masonry-view' ) {
                            echo '</div>';
                    } elseif ( $blog_list_layout == 'wb-grid-view' ) {
                            echo '</div>';
                    } else {
                            echo '</div>';
                    }

                    reign_custom_post_navigation();

            else :
				get_template_part( 'template-parts/content', 'none' );

            endif;
            ?>

    </div>
	
</div>

<?php do_action( 'reign_amp_after_content_section' ); ?>



<?php get_template_part( 'amp/footer'); ?>
