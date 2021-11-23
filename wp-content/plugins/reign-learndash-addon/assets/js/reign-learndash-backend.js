
jQuery( document ).ready( function() {
	jQuery( '.reign-select2-element' ).select2();
	jQuery( '#custom-course-feature-lists .select2.select2-container.select2-container--default' ).css( 'width', '100%');
	jQuery( '.rla_cs_endtime' ).datepicker({
        dateFormat: 'mm/dd/yy'
    });
	jQuery('#custom-course-feature-lists tbody tr:first .rla-delete-course-feature').hide();
	jQuery('.rla-add-course-feature').on('click', function(event){
		event.preventDefault();
		
		var $tableBody = jQuery('#custom-course-feature-lists').find("tbody"),
		$trLast = $tableBody.find("tr:last"),
		$trNew = $trLast.clone();
		$trNew.find('input').val('');
		$trNew.find('.select2.select2-container.select2-container--default').remove();
		//$trNew.find('select.reign-select2-element').select2('destroy');
		$trLast.after($trNew);
		
		jQuery('.rla-delete-course-feature').show();
		jQuery('#custom-course-feature-lists tbody tr:first .rla-delete-course-feature').hide();
		jQuery( '.reign-select2-element' ).select2();
		jQuery( '#custom-course-feature-lists .select2.select2-container.select2-container--default' ).css( 'width', '100%');
		
	});
	jQuery(document).on('click','.rla-delete-course-feature', function(event){
		event.preventDefault();
		jQuery(this).parent().parent().remove();
		
	});
} );