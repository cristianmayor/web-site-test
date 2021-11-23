<?php
/**
 * Template Item
 */
?>

<div class="elementor-template-library-template-body">
	<div class="elementor-template-library-template-screenshot">
		<div class="elementor-template-library-template-title">
            <span class="">{{ title }}</span>
        </div>
        <div class="wbcom-essential-template--thumb">
            <div class="wbcom-essential-template--label">
                <# if ( is_pro ) { #>
                <span class="wbcom-essential-template--tag wbcom-essential-template--pro"><?php echo __( 'Elementor Pro', 'wbcom-essential' ); ?></span><span class="wbcom-essential-template--sep"></span>
                <# } #>
                <?php if (class_exists( 'SFWD_LMS' )) { ?>
                    <# if ( is_learndash ) { #>
                    <span class="wbcom-essential-template--tag wbcom-essential-template--ld"><?php echo __( 'LearnDash', 'wbcom-essential' ); ?></span><span class="wbcom-essential-template--sep"></span>
                    <# } #>
                <?php } elseif (class_exists( 'LifterLMS' )) { ?>
                    <# if ( is_lifter ) { #>
                    <span class="wbcom-essential-template--tag wbcom-essential-template--llms"><?php echo __( 'LifterLMS', 'wbcom-essential' ); ?></span><span class="wbcom-essential-template--sep"></span>
                    <# } #>
                <?php } else { ?>
                    <# if ( is_learndash ) { #>
                    <span class="wbcom-essential-template--tag wbcom-essential-template--ld"><?php echo __( 'LearnDash', 'wbcom-essential' ); ?></span><span class="wbcom-essential-template--sep"></span>
                    <# } #>
                    <# if ( is_lifter ) { #>
                    <span class="wbcom-essential-template--tag wbcom-essential-template--llms"><?php echo __( 'LifterLMS', 'wbcom-essential' ); ?></span><span class="wbcom-essential-template--sep"></span>
                    <# } #>
                <?php } ?>
                <# if ( is_woo ) { #>
                <span class="wbcom-essential-template--tag wbcom-essential-template--woo"><?php echo __( 'WooCommerce', 'wbcom-essential' ); ?></span><span class="wbcom-essential-template--sep"></span>
                <# } #>
                <# if ( is_tec ) { #>
                <span class="wbcom-essential-template--tag wbcom-essential-template--tec"><?php echo __( 'The Events Calendar', 'wbcom-essential' ); ?></span><span class="wbcom-essential-template--sep"></span>
                <# } #>
            </div>
            <img src="{{ thumbnail }}" alt="{{ title }}">
        </div>
	</div>
</div>
<div class="elementor-template-library-template-controls">
    <button class="elementor-template-library-template-action wbcom-essential-template-insert elementor-button elementor-button-success">
        <i class="eicon-file-download"></i>
        <span class="elementor-button-title"><?php echo __( 'Insert', 'wbcom-essential' ); ?></span>
    </button>
</div>