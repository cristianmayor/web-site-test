<?php
/**
 * BuddyPress - Groups Cover Image Header.
 *
 * @since 3.0.0
 * @version 7.0.0
 */
?>
<?php
global $wbtm_reign_settings;
$group_header_class = isset($wbtm_reign_settings['reign_buddyextender']['group_header_type']) ? $wbtm_reign_settings['reign_buddyextender']['group_header_type'] : 'wbtm-cover-header-type-1';
$group_header_class = apply_filters('wbtm_rth_manage_group_header_class', $group_header_class);
?>
<div id="cover-image-container" class="wbtm-group-cover-image-container <?php echo esc_attr($group_header_class); ?>">
    <div id="header-cover-image"></div>

    <div id="item-header-cover-image">

        <div class="wbtm-member-info-section"><!-- custom wrapper for main content :: start -->

            <?php if (!bp_disable_group_avatar_uploads()) : ?>
                <div id="item-header-avatar">
                    <a href="<?php echo esc_url(bp_get_group_permalink()); ?>" title="<?php echo esc_attr(bp_get_group_name()); ?>">

                        <?php bp_group_avatar(); ?>

                    </a>
                </div><!-- #item-header-avatar -->
            <?php endif; ?>

            <?php if (!bp_nouveau_groups_front_page_description()) : ?>
                <div id="item-header-content">

                    <?php bp_nouveau_group_hook('before', 'header_meta'); ?>

                    <?php if (function_exists('bp_nouveau_the_group_meta')) { ?>   
                        <p class="highlight group-status"><strong><?php echo esc_html( bp_nouveau_the_group_meta( array( 'keys' => 'status' ) ) ); ?></strong></p>
                    <?php } else { ?>
                        <p class="highlight group-status bp-tooltip" data-bp-tooltip-length="large" data-bp-tooltip-pos="up" data-bp-tooltip="<?php echo esc_html( bp_get_group_status_description() ); ?>"><strong><?php echo wp_kses( bp_nouveau_group_meta()->status, array( 'span' => array( 'class' => array() ) ) ); ?></strong></p>
                    <?php } ?>

                    <p class="activity" data-livestamp="<?php bp_core_iso8601_date(bp_get_group_last_active(0, array('relative' => false))); ?>">
                        <?php
                        /* translators: %s = last activity timestamp (e.g. "active 1 hour ago") */
                        printf(__('active %s', 'buddypress'), bp_get_group_last_active());
                        ?>
                    </p>

                    <?php echo isset(bp_nouveau_group_meta()->group_type_list) ? bp_nouveau_group_meta()->group_type_list : ''; ?>

                    <?php if (bp_nouveau_group_has_meta_extra()) : ?>
                        <div class="item-meta">

                            <?php echo bp_nouveau_group_meta()->extra; ?>

                        </div><!-- .item-meta -->
                    <?php endif; ?>

                    <div class="wbtm-item-buttons-wrapper">
                        <!-- custom section to hover and reveal actions buttons :: start -->
                        <div class="wbtm-show-item-buttons"><i class="fa fa-ellipsis-v"></i></div>
                        <!-- custom section to hover and reveal actions buttons :: end -->

                        <div id="item-buttons">
                            <?php bp_nouveau_group_header_buttons(); ?>
                        </div><!-- #item-buttons -->
                    </div>

                </div><!-- #item-header-content -->
            <?php endif; ?>

            <?php if (bp_nouveau_groups_front_page_description()) : ?>
                <div id="item-header-content">
                    <div class="item-title"><h2 class="bp-group-title"><?php echo esc_html(bp_get_group_name()); ?></h2></div>
                </div>
            <?php endif; ?>

            <?php bp_get_template_part('groups/single/parts/header-item-actions'); ?>

        </div><!-- custom wrapper for main content :: end -->

        <!-- custom section for extra content :: start -->
        <div class="wbtm-cover-extra-info-section">
            <?php
            /**
             * Fires after main content to show extra information.
             *
             * @since 1.0.7
             */
            do_action('reign_group_extra_info_section');
            ?>
        </div>
        <!-- custom section for extra content :: start -->

    </div><!-- #item-header-cover-image -->

</div><!-- #cover-image-container -->

<?php if (!bp_nouveau_groups_front_page_description() && bp_nouveau_group_has_meta('description')) : ?>
    <div class="desc-wrap">
        <div class="group-description">
            <?php bp_group_description(); ?>
        </div><!-- //.group_description -->
    </div>
<?php endif; ?>
