<?php
/**
 * Template Library Filter Item.
 */
?>
<label class="wbcomessentialelementor-template-filter-label">
    <input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="wbcomessentialelementor-template-filter">
    <span>{{ title.replace('&amp;', '&') }}</span>
</label>