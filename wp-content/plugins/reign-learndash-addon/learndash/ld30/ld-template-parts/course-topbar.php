<?php
$view_to_render = isset( $_COOKIE['learnmate_course_view'] ) ? $_COOKIE['learnmate_course_view'] : 'lm-grid-view';
?>
<div class="lm-course-top switch-layout-container ">
	<div class="lm-course-switch-layout switch-layout">
            <a href="#" class="lm-grid-view <?php if( $view_to_render == 'lm-grid-view') { echo 'switch-active'; } ?>"><span class="fas fa-border-all"></span></a>
            <a href="#" class="lm-list-view <?php if( $view_to_render == 'lm-list-view') { echo 'switch-active'; } ?>"><span class="fas fa-bars"></span></a>
	</div>
	<!-- <div class="course-index">
		<span>Showing 1-9 of 17 results</span>
	</div> -->
	<div class="courses-searching">
		<?php get_reign_ld_course_search_form(); ?>
	</div>
</div>