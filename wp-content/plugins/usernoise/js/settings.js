var farbtastic;

(function($){
	$.fn.un_color_picker = function(){
		$(this).each(function(index, picker){
			picker.pickColor = function(a) {
				picker.farbtastic.setColor(a);
				$(picker).find('input[type=text]').val(a);
			}
			console.info($('div.picker', $(picker)).attr('id'));
			picker.farbtastic = $.farbtastic($('div.picker', $(picker)), picker.pickColor);
			picker.pickColor($('input[type=text]', $(picker)).val());
			$('.pickcolor', $(picker)).click( function(e) {
				$('div.picker', $(picker)).show();
				e.preventDefault();
			});
			$('input[type=text]', $(picker)).keyup( function() {
				var a = $('input[type=text]', $(picker)).val(),
					b = a;

				a = a.replace(/[^a-fA-F0-9]/, '');
				if ( '#' + a !== b )
					$('input[type=text]', $(picker)).val(a);
				if ( a.length === 3 || a.length === 6 )
					picker.pickColor( '#' + a );
			});
		});
		$(document).mousedown( function() {
			$('div.picker').hide();
		});
	}

	$(document).ready( function() {
		$('.un-color-picker').un_color_picker();
	});
})(jQuery);