jQuery( document ).ready(function( $ ) {
	
	$( '.reign_load_more').hide();
	if ( jQuery(".reign_load_more").length > 0 ) {
		var $container = $('.wb-post-listing').infiniteScroll({
		  path: '.reign_load_more a',
		  append: 'article.post',
		  button:'.infinite-scroll-request',
		  status: '.page-load-status',
		});
	}
		
});