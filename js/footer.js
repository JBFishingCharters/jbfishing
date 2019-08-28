(function($){
	
	var $win = $(window),
		$html = $('html'),
		$head = $('head'),
		$body = $('body');
	
	// Add class to body when mobile nav is open
	(function(){
		var $mobileNav = $('#et_mobile_nav_menu .mobile_nav');
		$mobileNav.find('.mobile_menu_bar_toggle').click(function(e){
			$body.toggleClass('mobile-menu-open', !$mobileNav.hasClass('opened'));
		});
	})();
	
	// Home body logo & header swap
	(function(){

		if ( $body.hasClass('home') ) {
			var $mod = $('.mod-intro'),
				$logoLabel = $mod.find('.logo-image').first(),
				$barWPAdmin = $('#wpadminbar'),
				$barTopHeader = $('#top-header'),
				$barMainHeader = $('#main-header'),
				getBarHeight = function($el){
					return ($el.length && $el.is(':visible')) ? $el.innerHeight() : 0;
				},
				calcOffset = function(){
					return getBarHeight($barWPAdmin) + getBarHeight($barTopHeader) + getBarHeight($barMainHeader) - getBarHeight($logoLabel)
				},
				logoWaypoint,
				check = function(){
					if(logoWaypoint){
						logoWaypoint.destroy();
					}
					if( $logoLabel.length ){
						logoWaypoint = new Waypoint({
							element: $logoLabel.get(0),
							handler: function(direction) {
								$body.toggleClass('show-logo', (direction==='down'));
							},
							offset: calcOffset()
						});
					}
				};

			check();
			$win.resize(check);
		}
	})();


	// Check for Background-Blend-Mode support and if is Chrome
	var supportsBackgroundBlendMode = window.getComputedStyle(document.body).backgroundBlendMode,
		isChrome = (/Chrome/i.test(navigator.userAgent));
	$html.toggleClass('hasBackgroundBlends', !(typeof supportsBackgroundBlendMode === "undefined"))
		.toggleClass('chrome', isChrome);
	
	
	// Lazy-loading BG Images.
	$('.et_pb_section_parallax:not(.no-lazy) > .et_parallax_bg').each(function(idx, bgElement){
		var waypoint = new Waypoint({
			element: bgElement,
			handler: function(direction) {
				if( direction==='down' ){
					$(bgElement).addClass('lazy-loaded');
					waypoint.destroy();
					waypoint = null;
				}
			},
			offset: 572
		});
	});
	
	
	var $datePickers = $('.contact-form input[data-original_id="date"]');
	if( $datePickers.length ){
		$head.append( $('<link rel="stylesheet" type="text/css" />').attr('href', jb_fishing.theme_path + '/css/lib/pickadate/pickadate.css') );
		$.getScript(jb_fishing.theme_path + '/js/lib/pickadate/pickadate-min.js', function( data, textStatus, jqxhr ) {
			$datePickers.each(function(idx, input){
				var $input = $(input).pickadate({
					weekdaysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
					showMonthsShort: true
				}).addClass('ui-datepicker-input');
				
				$input.pickadate('picker').$root.addClass('ui-datepicker');
			});
		});
	}
	
	
})(jQuery);