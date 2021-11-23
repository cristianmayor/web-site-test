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
            <?php
            while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', get_post_format() );

                    $args = array(
                            'prev_text' => '<span class="rg-next-prev">' . __( 'Previous', 'reign' ) . '	</span><span class="nav-title">%title</span>',
                            'next_text' => '<span class="rg-next-prev">' . __( 'Next', 'reign' ) . '</span><span class="nav-title">%title</span>',
                    );
                    the_post_navigation( $args );

                    
                    
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
                    

            endwhile; // End of the loop.
            ?>
    </div>
</div>

<?php do_action( 'reign_amp_after_content_section' ); ?>



<?php get_template_part( 'amp/footer'); ?>
