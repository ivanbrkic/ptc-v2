/**
 * Main.
 */

import './modules/menu';
import './modules/checkout-steps';
import './modules/modal';

jQuery(document).ready(function ($) {

	/** Show/hide Company Name and EU business VAT number depending on
		* radio "is your purchase private or business".
		*/
  function showHide(selector = '', action = 'show') {
    if (action == 'show')
      $(selector).show(200, function () {
        $(this).addClass('validate-required');
      });
    else
      $(selector).hide(200, function () {
        $(this).removeClass('validate-required');
      });
    $(selector).removeClass('woocommerce-validated');
    $(selector).removeClass(
      'woocommerce-invalid woocommerce-invalid-required-field'
    );
  }

  var a = 'input[name="billing_notice"]',
    b = 'input[name="billing_notice"]:checked',
    c = '#billing_company_field',
    d = '#woocommerce_eu_vat_number_field';

  if ($(b).val() === 'private') {
		showHide(c, 'hide');
		showHide(d, 'hide');
	} else {
    showHide(c);
    showHide(d);
  }

  $(a).on('change', function () {
    if ($(b).val() === 'private') {
			showHide(c, 'hide');
			showHide(d, 'hide');
			$('#woocommerce_eu_vat_number').val('');
			$('#woocommerce_eu_vat_number').trigger('change');
		} else {
			showHide(c, 'show');
			showHide(d, 'show');
		}
  });

	/** Trigger update_checkout after changing email since
	 * we have custom validation. If the email already exists
	 * >> login link.
	*/
	$('#billing_email').on('change', function () {
		$('body').trigger('update_checkout');
	});
});
