// generate step by step checkout toggles & buttons

let $h3 = jQuery('#customer_details .col-1 > h3');

$h3.each(function (index) {
  jQuery(this).addClass('toggle__trigger');
	// wrap h3 and following nodes before another h3
  let $col = jQuery(this)
    .next()
    .nextUntil('h3')
    .addBack()
    .wrapAll('<div class="toggle__content is-open" />');
});
jQuery('.toggle__content').slice(1).slideToggle().removeClass('is-open');
let next = 'Next step';
if (typeof ptc !== 'undefined') {
  next = ptc.next;
}
let len = jQuery('.toggle__content').length;
jQuery('.toggle__content')
  .slice(0, len - 1)
  .append(
    '<button type="button" class="button alt toggle__next">' +
      next +
      '</button>'
  );
jQuery('.toggle__trigger').on('click', function () {
	let $cont = jQuery(this).next('.toggle__content');
	if ($cont.hasClass('is-open')){
		$cont.slideUp();
		$cont.removeClass('is-open');
	} else {
		$cont.slideDown();
		$cont.addClass('is-open');
	}
});

jQuery('.toggle__next').on('click', function () {
	jQuery('.toggle__content.is-open').removeClass('is-open');
	jQuery('.toggle__content').slideUp();
	jQuery(this).closest('.toggle__content').nextAll('.toggle__trigger').first().trigger('click');
	
});

jQuery(document).on('checkout_error', function(){
	//open all
	jQuery('.toggle__content').slideDown().addClass('is-open');
});
