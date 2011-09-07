jQuery(function($){
	if ($.browser.msie && $.browser.version == '6.0')
		return;
	if ($('#facebox').is(':visible')){
		$('#facebox, #facebox_overlay, #un-button').hide().remove();
	}
	
	function UsernoiseButton(){
		var self = this;
		var $button = $('<a href="#" id="un-button" />').appendTo($('body'));
		var property;
		$button.text(usernoise.buttonText);
		$button.addClass(usernoise.buttonClass);
		$button.attr('style', usernoise.buttonStyle);
		$button.click(function(){
			$(document).trigger('show#window.un');
			return false;
		});
		if ($button.is('.bottom') || $button.is('.top'))
			$button.css('margin-left', "-" + ($('#un-button').width()) + "px");
		if ($button.is('.left'))
			property = 'left';
		else if ($button.is('.right'))
			property = 'right';
		else if ($button.is('.bottom'))
			property = 'bottom';
		else
			property = 'top';
		if ($.browser.msie && $.browser.version == '7.0')
			$button.addClass('ie7');
		else if ($.browser.msie && $.browser.version == '8.0'){
			$button.addClass('ie8');
		} else {
			$button.addClass('css3');
		}
		if ($button.is('.right.css3'))
			$button.css('margin-top', '-' + ($button.width()) + "px");
		if ($button.is('.left.css3'))
			$button.css('margin-top', ($button.width() / 2)  + "px");
		var propOnStart = {};
		var propOnIn = {opacity: 1};
		var propOnOut = {opacity: 0.96};
		propOnStart[property] = '+=40px';
		propOnIn[property] = '+=3px';
		propOnOut[property] = '-=3px';
		$button.css('display', 'block').animate(propOnStart).hover(
			function(){$button.animate(propOnIn, 100)},
			function(){$button.animate(propOnOut, 100)});
	}
	
	function FeedbackForm($wrapper){
		var self = this;
		var $wrapper = $wrapper;
		var $form = $wrapper.find('.un-feedback-form');
		$form.find('.text').unAutoPlaceholder();
		$form.find('.un-types-wrapper a').click(selectTypeHandler);
		$form.attr('action', $form.attr('data-action'));
		$form.submit(submitHandler);
		$('#un-feedback-close').click(function(){
			usernoise.window.close();
		});
		function selectTypeHandler(){
			var $selector = $(this).parent();
			$selector.find('a').removeClass('selected');
			$(this).addClass('selected');
			var type = $(this).attr('id').replace('un-type-', '');
			$selector.find('input[type=hidden]').val(type);
			$(document).trigger('typeselected#feedbackform#window.un', type);
			return false;
		}
		
		function submitHandler(){
			var data  = $form.unSerializeObject();
			$(document).trigger('submitting.feedbackform.window.un', data);
			self.lock();
			$form.find('.loader').show();
			self.errors.hide();
			$.post($form.attr('action'), data , function(response){
				$form.find('.loader').hide();
				self.unlock();
				response = $.parseJSON ? $.parseJSON(response) : eval(response);
				if (response.success){
					$wrapper.fadeOut('fast', function(){
						$(document).trigger('show#thankyouscreen#window.un', response.html);
					});
				} else {
					self.errors.show(response.errors);
				}
			});
			return false;
		}
		
		$.extend(self, {
			unlock: function(){
				$(document).trigger('unlocking#feedbackform#window.un');
				$form.find('input, select, textarea').removeAttr('disabled');
				$form.find('.un-types-wrapper a').click(selectTypeHandler);
			}, 
			lock: function(){
				$form.find('*').unbind('click');
				$(document).trigger('locking#feedbackform#window.un');
				$form.find('input, select, textarea').attr('disabled', 'disabled');
			},
			errors: new Errors($form.find('.un-errors-wrapper'))
		});
		$(document).trigger('created#feedbackform#window.un', self);
	}
	
	function Errors($errors){
		var self = this;
		var $errorsWrapper = $errors;
		var $errors = $errorsWrapper.find('.un-errors');
		$.extend(self, {
			show: function(errors){
				$(errors).each(function(index, error){
					$errors.append($("<p />").text(error));
				});
				$errorsWrapper.fadeIn('fast');
			}, 
			hide: function(errors){
				$errorsWrapper.fadeOut('fast', function(){
					$errors.html(''); 
					$errorsWrapper.hide();
				});
			}
		});
	}
	
	function ThankYouScreen($screen){
		var self = this;
		var $screen = $screen;
		$.extend(self, {
			show: function(html){
					$('#un-feedback-content').append($(html));
					$('#un-reset-feedback').click(function(){
						$(document).trigger('close#window.un');
					});
			}
		});
	}
	
	function UsernoiseWindow(windowSelector){
		var self = this;
		var $window;
		var windowHTML;
		var faceboxSettingsBackup;
		
		function detectBrowser(){
			if (!$('#facebox').hasClass('un')) return;
			$('#facebox').addClass('un');
			if ($.browser.msie && $.browser.version == '7.0')
				$('#facebox').addClass('un-ie7');
			if ($.browser.msie && $.browser.version == '8.0')
				$('#facebox').addClass('un-ie8');
		}
		
		function showThankYouHandler(event, html){
			self.thankYouScreen = new ThankYouScreen($window.find('.thank-you-screen'));
			self.thankYouScreen.show(html);
		}
		
		function initFacebox(){
			faceboxSettingsBackup = $.facebox.settings;
			$.facebox.settings = $.extend({}, $.facebox.settings);
			$.extend($.facebox.settings, {
				closeImage: usernoise.closeImageUrl,
				opacity: "0.6",
				loadingImage: usernoise.ajaxLoaderUrl,
				faceboxHtml : '\
		<div id="facebox" style="display:none;"> \
		  <div class="popup"> \
		    <div class="content"> \
		    </div> \
		    <a href="#" class="close"><img src="" title="close" class="close_image" /></a> \
		  </div> \
		</div>'
			});
		}
		
		$.extend(self, {
			show: function(){
				initFacebox();
				$.facebox($(windowHTML));
				$('#facebox').addClass('un');
				$window = $(windowSelector);
				self.feedbackForm = new FeedbackForm($window.find('#un-feedback-form-wrapper'));
				$(document).bind('loading.facebox', detectBrowser);
				$(document).bind('afterClose.facebox', function(){self.remove();});
				$(document).trigger('shown#window.un');
			},
			close: function(){
				$(document).trigger('close.facebox');
			},
			remove: function(){
				$.facebox.settings = faceboxSettingsBackup;
				$('#facebox').removeClass('un').removeClass('un-ie7').removeClass('un-ie8');
				$window.remove();
			}
		});
		$(document).bind('show#window.un', function(){self.show(); return false;});
		$(document).bind('close#window.un', function(){self.close(); return false;});
		$(document).bind('show#thankyouscreen#window.un', showThankYouHandler);
		windowHTML = $('#un-window-wrapper').html();
		$('#un-window-wrapper').remove();
	}
	
	
	$.fn.unAutoPlaceholder = function(){
		$(this).each(function(){
			$(this).attr('data-default', $(this).val());
			$(this).focus(function(){
				if ($(this).val() == $(this).attr('data-default')){
					$(this).val('');
					$(this).addClass('text-normal');
					$(this).removeClass('text-empty');
				}
			});
			$(this).blur(function(){
				if ($(this).val() == ''){
					$(this).val($(this).attr('data-default'));
					$(this).removeClass('text-normal');
					$(this).addClass('text-empty');
					
				}
			});
		});
	};
	
	$.fn.unSerializeObject = function(){
		var o = {};
		var a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name]) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
	};
	
	usernoise.window = new UsernoiseWindow('#un-window');
	usernoise.button = new UsernoiseButton();
	
});