<?php
$contents = WC()->cart->get_cart_contents();
$item_sku  = false;
if ( $contents ) {
	foreach ( $contents as $item ) {
		$item_sku = $item['data']->get_sku();
		break;
	}
}
if ( $item_sku && strpos( $item_sku, 'PDC' ) !== false  ) { // PDC
	?>
	<div blog-id="<?php echo get_current_blog_id(); ?>">
	<div class="boxed testimonial my-1">
		<div>
		"The vast amount of science-based and researched information presented by Menno in the course was above and beyond what one would expect."
			<br><br>
			<b>- Dr. Larry Feinman</b>
		</div>
	</div>
	<div class="boxed mb-1">
		<div class="flex-line justify-content-between mb-1">
			<h3 class="t-title">

			Satisfaction guarantee
			</h3>
			<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/badge.svg" alt="">
		</div>
		There's no risk. Don't agree with the 99% satisfied students? You can leave the course at any time and stop paying. See <a href="<?php echo get_permalink(wc_terms_and_conditions_page_id()) ?>">terms and conditions</a>.
	</div>
	<div class="boxed">
		<h3 class="mb-1 t-title">
			Need help?
		</h3>
		Email <a href="mailto:info@mennohenselmans.com">info@mennohenselmans.com</a> or chat with us on WhatsApp.
<br>
		<a class="flex-line button-whatsapp mt-1" target="_blank" href="https://wa.me/31613911140">
			<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/wa.svg" alt="">
			Chat on WhatsApp</a>
	</div>
</div>
	<?php
} else {
?>
	<div blog-id="<?php echo get_current_blog_id(); ?>">
	<div class="boxed testimonial my-1">
		<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/testimonial.jpg" alt="">
		<div>
			"Having been in the fitness industry for 10 years, the Henselmans PT Course has been by far <b>the best course I have completed</b>, both for quality and quantity of information and value for
			money."
			<br><br>
			- Paul Stevenson, Personal trainer
		</div>
	</div>
	<div class="boxed mb-1">
		<div class="flex-line justify-content-between mb-1">
			<h3 class="t-title">

				No-results-money-back guarantee
			</h3>
			<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/badge.svg" alt="">
		</div>
		If after successfully graduating from the course, you feel you haven't become a better coach or you haven't learned anything, we'll give you a full refund. Just tell our administration and you'll
		get all your money back. You'll stay certified.
	</div>
	<div class="boxed">
		<h3 class="mb-1 t-title">
			Need help?
		</h3>
		<p class="mb-1">
			Send an email to <a href="mailto:info@mennohenselmans.com">info@mennohenselmans.com</a>
		</p>
		<span class="d-block">
			Our study advisors are here to help:
		</span>
		<a class="flex-line button-whatsapp mt-05" target="_blank" href="https://wa.me/31613911140">
			<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/wa.svg" alt="">
			Chat on WhatsApp</a>
	</div>
</div>
<?php
}
