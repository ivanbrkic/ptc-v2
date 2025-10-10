<?php
$contents = WC()->cart->get_cart_contents();
$item_sku  = false;
if ( $contents ) {
	foreach ( $contents as $item ) {
		$item_sku = $item['data']->get_sku();
		break;
	}
}
?>
	<div blog-id="<?php echo get_current_blog_id(); ?>">
	<div class="boxed testimonial my-1">
		<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/testimonial.jpg" alt="">
		<div>
		بعد أن عملت في مجال التدريب الشخصي لمدة 10 سنوات، كانت دورة هنسيلمانز في اللياقة البدنية هي أفضل دورة تدريبية أكملتها إلى الآن، سواء من حيث جودة وكمية المعلومات أو من حيث القيمة التي تحصل عليها مقابل المال.
			<br><br>
			بول ستيفنسون، مدرب شخصي
		</div>
	</div>
	<div class="boxed mb-1">
		<div class="flex-line justify-content-between mb-1">
			<h3 class="t-title">

			ضمان استرداد الأموال بدون نتائج
			</h3>
			<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/badge.svg" alt="">
		</div>
		إذا شعرت بعد تخرجك من الدورة التدريبية بنجاح أنك لم تصبح مدرباً أفضل أو أنك لم تتعلم شيئاً، فسنعيد لك أموالك كاملة. ما عليك سوى إخبار إدارتنا وستسترد جميع أموالك. و ستظل معتمداً من المنظمة.
	</div>
	<div class="boxed">
		<h3 class="mb-1 t-title">
		هل تحتاج إلى مساعدة؟
		</h3>
		<p class="mb-1">
		قم بإرسال بريد إلكتروني إلى <a href="mailto:info@mennohenselmans.com">info@mennohenselmans.com</a>
		</p>
		<span class="d-block">
		مستشارونا الدراسيون جاهزون لمساعدتك
		</span>
		<a class="flex-line button-whatsapp mt-05" target="_blank" href="https://wa.me/31613911140">
			<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/wa.svg" alt="">
			تواصل معنا على واتساب
		</a>
	</div>
</div>
<?php
