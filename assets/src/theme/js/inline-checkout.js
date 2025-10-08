jQuery(function ($) {
	'use strict';

	addInlineMessages();

	// Implementation

	// Listen to js event
	$(document.body).on('updated_checkout', function () {
		addInlineMessages();
	});

	function addInlineMessages() {
		var woocommerceErrorsWrapper = $('.woocommerce-error');
		// include success messages (.woocommerce-message) and errors, but not notices
		var woocommerceInlineErrors = $('li', woocommerceErrorsWrapper).add('.woocommerce-message');
		var inlineErrorMessages = $('.js-custom-error-message');

		// as we use ajax submitting hide old validation messages
		if (inlineErrorMessages.length) {
			inlineErrorMessages.hide();
		}
		// show inline with data-id set
		if (woocommerceInlineErrors.length) {
			woocommerceInlineErrors.each(function () {
				var errorEl = $(this);
				var errorText = $.trim(errorEl.text());
				var isMsgNotAnError = errorEl.hasClass('woocommerce-message');
				if (isMsgNotAnError) {
					var errorText = $.trim(errorEl.html());
				}

				var targetFieldId = errorEl.data('id');

				if (errorText && targetFieldId) {
					var targetFieldEl = $('#' + targetFieldId);
					var errorMessageField = $('.js-custom-error-message', targetFieldEl.parent());

					if (targetFieldEl.length && errorMessageField.length) {
						targetFieldEl.removeClass('woocommerce-validated');
						targetFieldEl.addClass('woocommerce-invalid');

						errorMessageField.html(errorText);
						errorMessageField.show();
						if (isMsgNotAnError){
							errorMessageField.addClass('is-message');
						}
						errorEl.hide();
					}
				}
			});

			// if there is no error left unprocessed, hide the error wrapper and scroll to the first one, if it isn't vat
			if (woocommerceInlineErrors.filter(':visible').length === 0) {
				woocommerceErrorsWrapper.hide();


				if (inlineErrorMessages.filter(':visible').length > 0) {
					// scroll to first visible error message
					scrollToElement(inlineErrorMessages.not($( ".is-message" )).filter(':visible').first());
				}

			} else { // if there are errors left on top, scroll to them
				jQuery('html, body').stop();
				scrollToElement(woocommerceErrorsWrapper);
			}

		}
	}

	function scrollToElement(el) {
		console.log('scrolling', el);

		if (el.length) {
			jQuery( 'html, body' ).stop();
			$([document.documentElement, document.body]).animate({
				scrollTop: el.offset().top - 150
			}, 2000);
		}
	}

	// event listeners
	$(document.body).on('checkout_error', function (event) {
		addInlineMessages();
	});
});
