
( function ( document, window, index )
{
	var inputs = document.querySelectorAll( '.uploadfiles' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
			labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
				fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.querySelector( 'span' ).innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});

		// Firefox bug fix
		input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
		input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
	});
}( document, window, 0 ));

jQuery( document ).ready( function() {


	jQuery( 'body.lm-ld-profile-expanded #learndash_profile .expand_collapse a' ).first().trigger( 'click' );
	jQuery('.rld_course_bp_search').remove();

	jQuery( document.body ).on( 'click', '.rtm-ld-common-buy-now #btn-join', function( event ) {
		var product_id = jQuery( '#rtm_ld_associated_pro_id' ).val();
		var redirect_url = jQuery( '#rtm_ld_atc_redirect_url' ).val();
		var buy_using = jQuery( '#rtm_ld_buy_using' ).val();
		if( product_id && redirect_url ) {
			event.preventDefault();
			event.stopPropagation();

			jQuery.ajax({
				url : reign_learndash_js_params.ajax_url,
				type : 'post',
				data : {
					action : 'rtm_ld_atc_woo_product',
					product_id : product_id,
					buy_using : buy_using,
				},
				success : function( response ) {
					window.location.href = redirect_url;
				}
			});
		}
	} );



	jQuery( '.reign-select2-element' ).select2();

 	var rla_cs_endtime = jQuery( '#rla_cs_endtime' ).val();
 	deadline = new Date( rla_cs_endtime );
	initializeClock('reign-ld-cs-clock-wrapper', deadline);

	function initializeClock(id, endtime) {
		var clock = document.getElementById(id);
		if( !clock ) {
			return;
		}
		var daysSpan = clock.querySelector('.days');
		var hoursSpan = clock.querySelector('.hours');
		var minutesSpan = clock.querySelector('.minutes');
		var secondsSpan = clock.querySelector('.seconds');
		function updateClock() {
			var t = getTimeRemaining(endtime);
			daysSpan.innerHTML = t.days;
			hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
			minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
			secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
			if (t.total <= 0) {
				clearInterval(timeinterval);
			}
		}
		updateClock();
		var timeinterval = setInterval(updateClock, 1000);
	}

	function getTimeRemaining(endtime) {
		var t = Date.parse(endtime) - Date.parse(new Date());
		var seconds = Math.floor((t / 1000) % 60);
		var minutes = Math.floor((t / 1000 / 60) % 60);
		var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
		var days = Math.floor(t / (1000 * 60 * 60 * 24));
		if ( 0 == t || 0 > t ) {
			seconds = 0;
			minutes = 0;
			hours   = 0;
			days    = '00';
		}
		return {
			'total': t,
			'days': days,
			'hours': hours,
			'minutes': minutes,
			'seconds': seconds
		};
	}


	jQuery( '.lm-distraction-free-toggle, .lm-board-popup-close' ).click( function( event ) {
		event.preventDefault();
		jQuery( 'body' ).toggleClass( 'lm-distraction-free-reading-active' );
		jQuery( '#lm-distraction-free-reading' ).toggleClass( 'lm-distraction-free-reading' );
		if( jQuery( '#lm-distraction-free-reading' ).hasClass( 'lm-distraction-free-reading' ) ) {
			learnmate_setCookie( 'learnmate_distraction_free_reading', 'enable', 100 );
			localStorage.setItem("distraction_free_reading", "enable");
		}
		else {
			learnmate_setCookie( 'learnmate_distraction_free_reading', 'disable', 100 );
			localStorage.setItem("distraction_free_reading", "disable");
		}
	} );
	jQuery( '#learndash_next_prev_link .prev-link, #learndash_next_prev_link .next-link' ).click( function( event ) {
		if( jQuery( '#lm-distraction-free-reading' ).hasClass( 'lm-distraction-free-reading' ) ) {
			localStorage.setItem("distraction_free_reading", "enable");
		}
		else {
			localStorage.setItem("distraction_free_reading", "disable");
		}
	} );


	jQuery( '.header_icon_free' ).click( function( event ) {
		event.preventDefault();
		jQuery( '#lm-distraction-free-reading' ).toggleClass( 'lm-board-full-width' );
		if( jQuery( '#lm-distraction-free-reading' ).hasClass( 'lm-board-full-width' ) ) {
			jQuery( this ).html( '<i class="fa fa-arrow-right"></i>' );
		}
		else {
			jQuery( this ).html( '<i class="fa fa-bars"></i>' );
		}
	} );


	jQuery( 'select#rtm-ld-lesson-selector' ).change( function() {
		window.location = jQuery( this ).val();
	} );


	/*
	* Manage toggle on learndash profile page.
	*/
	// jQuery( '#learndash_profile #course_list .learndash-course-link a' ).click( function( event ) {
	// 	event.preventDefault();
	// 	event.stopPropagation();
	// 	jQuery( this ).parent().parent().prev( '.list_arrow.flippable' ).trigger( 'click' );
	// } );


	jQuery( '.lm-course-switch-layout a' ).click( function( event ) {
		event.preventDefault();
		jQuery( '#lm-course-archive-data' ).removeClass().addClass( jQuery( this ).attr( 'class' ) );
		jQuery( '.lm-course-switch-layout a' ).toggleClass( 'switch-active' );
		learnmate_setCookie( 'learnmate_course_view', jQuery( jQuery( '#lm-course-archive-data' ) ).attr( 'class' ), 100 );
	} );

	jQuery( '.lm-course-tabs-wrapper ul.lm-coure-tabs a' ).click( function( event ) {
		event.preventDefault();
		jQuery( '.lm-course-tabs-wrapper ul.lm-coure-tabs li' ).removeClass( 'active' );
		jQuery( this ).parent().addClass( 'active' );
		var data_tab_slug = jQuery( this ).parent().attr( 'data-tab-slug' );
		jQuery( '.lm-tab-content-wrapper .lm-tab-content' ).removeClass( 'active' );
		jQuery( '.lm-tab-content-wrapper .lm-tab-content.lm-tab-content-'+data_tab_slug ).addClass( 'active' );
	} );


	jQuery( '.lm-course-hierarchy .lm-lesson .lm-lesson-section-header .lm-topics-toggle' ).click( function( event ) {
		event.preventDefault();
		if( 0 !== jQuery( this ).parent().next( '.lm-topics-list' ).length ) {
			if( jQuery( this ).find( 'i' ).hasClass( 'fa-chevron-down' ) ) {
				jQuery( this ).find( 'i' ).removeClass().addClass( 'fa fa-chevron-up' );
			}
			else {
				jQuery( this ).find( 'i' ).removeClass().addClass( 'fa fa-chevron-down' );
			}
			jQuery( this ).parent().next( '.lm-topics-list' ).slideToggle();
		}
	} );


	jQuery( '.lm-course-hierarchy .lm-lesson.learnmate-current-menu-item .lm-lesson-section-header .lm-topics-toggle i.fa.fa-chevron-down' ).trigger( 'click' );




	jQuery( '.lm-course-hierarchy a-dev' ).click( function( event ) {

		event.preventDefault();
		event.stopPropagation();

		learnmate_showPopup_board();
		learnmate_showPopup_loader();

		var object_id = jQuery( this ).attr( 'data-object-id' );
		jQuery.ajax({
			url : learnmate_ld_js_params.ajax_url,
			type : 'post',
			data : {
				action : 'get_topic_content',
				object_id : object_id,
			},
			success : function( response ) {
				jQuery( '.lm-board-popup-main' ).html( response );
				learnmate_hidePopup_loader();
			}
		});

	} );

	/*
	 * Activate Review tab when user submit comment
	 */
	if ( jQuery( '.ld-tabs-navigation' ).length != 0 ) {
		var hash  = window.location.hash;
		var url   = window.location.href;
		var $tabs = jQuery( 'ld-tabs-navigation .ld-tab' ).first();
		jQuery('.ld-tabs-navigation .ld-tab').each(function(){
			var ld_tab = jQuery(this).data( 'ld-tab') ;
			if (  ld_tab.toLowerCase().indexOf( 'ld-tab-review-' ) >= 0 && ( hash.toLowerCase().indexOf( 'comment-' ) >= 0 || hash === '#reviews' ) ) {
				jQuery('.ld-tabs-navigation .ld-tab.ld-active').removeClass('ld-active');
				jQuery(this).addClass('ld-active');
				jQuery('.ld-tabs-content .ld-tab-content.ld-visible').removeClass('ld-visible');
				jQuery( '#' + ld_tab ).addClass('ld-visible');
			}
		} );

	}
        
        // Single Course Sidebar Position 
        var courseBannerHeight = jQuery( '.learndash-course-layout-udemy .learndash-single-course-header' ).height() + 30;
        var courseBannerVideo = jQuery( '.learndash-course-widget .lm-course-thumbnail' );
        if ( courseBannerVideo.length ) {
            var thumbnailContainerHeight = courseBannerVideo.height();
        } else {
            var thumbnailContainerHeight = 0;
        }
        var sidebarOffset = ( courseBannerHeight/2 ) + ( thumbnailContainerHeight/2 );
        if ( jQuery(window).width() > 992 ) {
            jQuery( '.learndash-course-layout-udemy .learndash-course-widget' ).css( { 'margin-top' : '-' + sidebarOffset + 'px' } );
        }
} );

function learnmate_showPopup_board() {
	jQuery( '' ).show();
}

function learnmate_showPopup_loader() {
	jQuery( '' ).show();
}

function learnmate_removePopup_loader() {
	jQuery( '' ).hide();
}

function learnmate_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function learnmate_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

jQuery(
	function () {
		var status = localStorage.getItem( 'distraction_free_reading' );
		if ( status != null ) {
			if( status == 'enable' ){
				jQuery( 'body' ).addClass( 'lm-distraction-free-reading-active' );
				jQuery( '#lm-distraction-free-reading' ).addClass( 'lm-distraction-free-reading' );
			}else if( status == 'disable' ){
				jQuery( 'body' ).removeClass( 'lm-distraction-free-reading-active' );
				jQuery( '#lm-distraction-free-reading' ).removeClass( 'lm-distraction-free-reading' );
			}
			localStorage.setItem( "distraction_free_reading", null );
		}
	}
);
