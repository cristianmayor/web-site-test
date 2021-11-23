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
        <div class="wbcomessentialelementor-template--thumb">
            <div class="wbcomessentialelementor-template--label">
                <# if ( is_pro ) { #>
                <span class="wbcomessentialelementor-template--tag wbcomessentialelementor-template--pro"><?php echo __( 'Elementor Pro', 'wbcom-essential' ); ?></span><span class="wbcomessentialelementor-template--sep"></span>
                <# } #>
                <?php if (class_exists( 'SFWD_LMS' )) { ?>
                    <# if ( is_learndash ) { #>
                    <span class="wbcomessentialelementor-template--tag wbcomessentialelementor-template--ld"><?php echo __( 'LearnDash', 'wbcom-essential' ); ?></span><span class="wbcomessentialelementor-template--sep"></span>
                    <# } #>
                <?php } elseif (class_exists( 'LifterLMS' )) { ?>
                    <# if ( is_lifter ) { #>
                    <span class="wbcomessentialelementor-template--tag wbcomessentialelementor-template--llms"><?php echo __( 'LifterLMS', 'wbcom-essential' ); ?></span><span class="wbcomessentialelementor-template--sep"></span>
                    <# } #>
                <?php } else { ?>
                    <# if ( is_learndash ) { #>
                    <span class="wbcomessentialelementor-template--tag wbcomessentialelementor-template--ld"><?php echo __( 'LearnDash', 'wbcom-essential' ); ?></span><span class="wbcomessentialelementor-template--sep"></span>
                    <# } #>
                    <# if ( is_lifter ) { #>
                    <span class="wbcomessentialelementor-template--tag wbcomessentialelementor-template--llms"><?php echo __( 'LifterLMS', 'wbcom-essential' ); ?></span><span class="wbcomessentialelementor-template--sep"></span>
                    <# } #>
                <?php } ?>
                <# if ( is_woo ) { #>
                <span class="wbcomessentialelementor-template--tag wbcomessentialelementor-template--woo"><?php echo __( 'WooCommerce', 'wbcom-essential' ); ?></span><span class="wbcomessentialelementor-template--sep"></span>
                <# } #>
				<# if ( is_reign ) { #>
                <span class="wbcomessentialelementor-template--tag wbcomessentialelementor-template--reign"><?php echo __( 'Reign', 'wbcom-essential' ); ?></span><span class="wbcomessentialelementor-template--sep"></span>
                <# } #>
                <# if ( is_tec ) { #>
                <span class="wbcomessentialelementor-template--tag wbcomessentialelementor-template--tec"><?php echo __( 'The Events Calendar', 'wbcom-essential' ); ?></span><span class="wbcomessentialelementor-template--sep"></span>
                <# } #>
            </div>
            <img src="{{ thumbnail }}" alt="{{ title }}">
        </div>
	</div>
</div>
<div class="elementor-template-library-template-controls">
    <button class="elementor-template-library-template-action wbcomessentialelementor-template-insert elementor-button elementor-button-success">
        <i class="eicon-file-download"></i>
        <span class="elementor-button-title"><?php echo __( 'Insert', 'wbcom-essential' ); ?></span>
    </button>
</div>