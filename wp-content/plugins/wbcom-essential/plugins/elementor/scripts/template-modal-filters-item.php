<?php
/**
 * Template Library Filter Item.
 */
?>
<label class="wbcom-essential-template-filter-label">
    <input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="wbcom-essential-template-filter">
    <span>{{ title.replace('&amp;', '&') }}</span>
</label>