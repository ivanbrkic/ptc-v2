<?php
// create WooCommerce coupons for affiliate user before welcome email is sent.
add_action( 'afwc_email_welcome_affiliate', 'ptc_create_affiliate_coupon', 9, 1 );

function ptc_create_affiliate_coupon( $args_array ) {
	$user_id = $args_array['affiliate_id'];
	$user    = get_userdata( $user_id );
	// user first name + user id

	$coupon_code   = sanitize_title( $user->first_name ) . $user_id;
	$amount        = 100;
	$discount_type = 'fixed_product'; // Type: fixed_cart, percent, fixed_product, percent_product

	// check if WooCommerce coupon with this $coupon_code exists
	$coupons = new WP_Query(
		[
			'post_type'      => 'shop_coupon',
			'posts_per_page' => 1,
			'title'          => $coupon_code,
		]
	);

	// if coupon already exists, do nothing
	if ( $coupons->have_posts() ) {
		return;
	}

	$coupon        = [
		'post_title'   => $coupon_code,
		'post_content' => '',
		'post_status'  => 'publish',
		'post_author'  => 1,
		'post_type'    => 'shop_coupon',
	];
	$new_coupon_id = wp_insert_post( $coupon );
	// Add meta
	update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
	update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
	update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
	update_post_meta( $new_coupon_id, 'usage_limit', '0' );
	update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
	update_post_meta( $new_coupon_id, 'limit_usage_to_x_items', '0' );
	update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
	update_post_meta( $new_coupon_id, 'exclude_sale_items', 'no' );
	update_post_meta( $new_coupon_id, 'afwc_referral_coupon_of', $user_id );
}
