(function($) {

	"use strict";

	// color picket setup
	$(function () {
		$('.my-color-field').wpColorPicker({
			defaultColor: false,
			palettes: ['#96D754', '#FFFA74', '#FF7150', '#9ABADC', '#FFA74C', '#158A61']
		});
	});

	// convert rgb to hex
	function rgb2hex(rgb) {
	  rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	  function hex(x) {
	      return ("0" + parseInt(x).toString(16)).slice(-2);
	  }
	  return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}

	// modify hex for background usage
	function convertHex(hex,opacity){
	    hex = hex.replace('#','');
	    var r = parseInt(hex.substring(0,2), 16);
	    var g = parseInt(hex.substring(2,4), 16);
	    var b = parseInt(hex.substring(4,6), 16);
	    return 'rgba('+r+','+g+','+b+','+opacity/100+')';
	}

	// highlight each row
	$('.mark-posts-marker').each(function() {
  	    var color = $(this).data('background');
  	    $(this).parent().parent().find('th, td').css('background-color', convertHex(color,25));
  	    $(this).parent().parent().find('.check-column').css('border-left', '4px solid '+color);
  	    $(this).parent().parent().find('.check-column input').css('margin-left', '3px'); // balance border width
	});

	// live preview of new markers
	$('.new-markers').hide();
	$('.js-add-markers').keyup(function(e) { // use keyup instead of keypress for latest char
		$('.new-markers').show();
		var markers = [];
		var make_markers = $(this).val().split(","); // separate marker by comma
		$(make_markers).each(function(e) { // push each new marker to array
			var marker = make_markers[e].replace(/(<([^>]+)>)/ig,""); // remove HTML from name (preview only)
			markers.push('<span class="new-marker">'+marker+'</span>');
		});

		$('.js-new-markers-intro').show();
		$('.js-new-markers').html(markers.join(' ')); // preview new markers

		if($(this).val().length == 0) // hide container
			$('.new-markers').hide();
	});

	// change background color in edit post options
	if($('#mark_posts_options').length > 0) {
		var color = $('select#mark_posts_term_id option:selected').data('color');
		if (color)
			$('#mark_posts_options h3.hndle').css('background-color', convertHex(color,25));
			$('span.mark-posts-color').css('background-color', color);
		$('select#mark_posts_term_id').on('change', function() {
			var color = $('select#mark_posts_term_id option:selected').data('color');
			$('#mark_posts_options h3.hndle').css('background-color', convertHex(color,25));
			$('span.mark-posts-color').css('background-color', color);
		});
	}

	$('a.mark-posts-initial').click(function() {
		var confirm_msg = $(this).data('confirm-msg');
		if (confirm(confirm_msg)) {
			var term_id = $(this).data('term-id');
			window.location = 'options-general.php?page=mark-posts&mark-all-posts-term-id=' + term_id;
		}
		else {
			return false;
		}
		return false;
	});

}(jQuery));