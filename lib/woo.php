<?php

// add to cart rename
add_filter( 'woocommerce_product_single_add_to_cart_text', 'atc_text' );
add_filter( 'woocommerce_product_add_to_cart_text', 'atc_text' );
// Changing Add to Cart text to Buy Now!
function atc_text() {
	return __( 'Buy Now!', 'ptc' );
}

add_filter(
	'woocommerce_add_to_cart_redirect',
	function() {
		return wc_get_checkout_url();
	}
);

 // Set taxes as our expense! fixed 1899 = price + variable tax
 add_filter( 'woocommerce_adjust_non_base_location_prices', '__return_false' );

 // force zero-rate if VAT plugin has set vat-exempt
	add_filter( 'woocommerce_product_get_tax_class', 'wc_diff_rate_for_user', 10000, 2 );
function wc_diff_rate_for_user( $tax_class, $product ) {
	$customer = WC()->customer;
	if ( $customer && WC()->customer->is_vat_exempt() ) {
		return 'zero-rate';
	}
	return $tax_class;
}

// Keep only last cart item in cart
add_action( 'woocommerce_before_calculate_totals', 'keep_only_last_cart_item', 30, 1 );
function keep_only_last_cart_item( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
		return;
	}

	$cart_items = $cart->get_cart();

	if ( count( $cart_items ) > 1 ) {
		$cart_item_keys = array_keys( $cart_items );
		$cart->remove_cart_item( reset( $cart_item_keys ) );
	}
}

add_filter( 'woocommerce_add_to_cart_validation', 'remove_cart_item_before_add_to_cart', 20, 3 );
function remove_cart_item_before_add_to_cart( $passed, $product_id, $quantity ) {
	if ( ! WC()->cart->is_empty() ) {
		WC()->cart->empty_cart();
	}
	return $passed;
}

// disable added to cart notice
add_filter( 'wc_add_to_cart_message_html', '__return_false' );

add_filter(
	'woocommerce_order_button_text',
	function( $button_text ) {
		return __( 'Enroll me now!', 'ptc' );
	}
);



add_action(
	'head',
	function() {

	}
);
add_action(
	'woocommerce_review_order_after_payment',
	function () {
		?>
<div class="trust-pilot">
	<!-- TrustBox script -->
	<script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
	<!-- End TrustBox script -->
		<?php if ( get_locale() === 'nl_NL' ) : ?>
	<!-- TrustBox widget - Micro Review Count -->
	<div class="trustpilot-widget" data-locale="nl-NL" data-template-id="5419b6a8b0d04a076446a9ad" data-businessunit-id="5d8f5aaa9dca160001188a1b" data-style-height="24px" data-style-width="100%"
		data-theme="light" data-style-alignment="center">
		<a href="https://nl.trustpilot.com/review/mennohenselmans.com" target="_blank" rel="noopener">Trustpilot</a>
	</div>
	<!-- End TrustBox widget -->
			<?php elseif ( get_locale() === 'de_DE' ) : ?>
	<!-- TrustBox widget - Micro Review Count -->
	<div class="trustpilot-widget" data-locale="de-DE" data-template-id="5419b6a8b0d04a076446a9ad" data-businessunit-id="5d8f5aaa9dca160001188a1b" data-style-height="24px" data-style-width="100%"
		data-theme="light" data-style-alignment="left">
		<a href="https://www.trustpilot.com/review/mennohenselmans.com" target="_blank" rel="noopener">Trustpilot</a>
	</div>
	<!-- End TrustBox widget -->
	<?php else : ?>
	<!-- TrustBox widget - Micro Review Count -->
	<div class="trustpilot-widget" data-locale="en-US" data-template-id="5419b6a8b0d04a076446a9ad" data-businessunit-id="5d8f5aaa9dca160001188a1b" data-style-height="24px" data-style-width="100%"
		data-theme="light" data-style-alignment="left">
		<a href="https://www.trustpilot.com/review/mennohenselmans.com" target="_blank" rel="noopener">Trustpilot</a>
	</div>
	<!-- End TrustBox widget -->
	<?php endif; ?>
</div>
			<?php
	}
);

/**
 * for some reason woocommerce.js hides field description and then animates it on focus,
 * this is here to prevent that.
 * */

add_filter(
	'woocommerce_form_field',
	function( $field, $key, $args, $value ) {
		$val = strpos( $field, 'description' );
		if ( $val ) {
			$field = str_replace( 'description', 'description-no-hide', $field );
		}
		return $field;
	},
	10,
	4
);


// Add action to the Order actions dropdown (Edit Order).
add_filter(
	'woocommerce_order_actions',
	function( $actions, $order ) {
		if ( $order && is_a( $order, 'WC_Order' ) && $order->has_status( wc_get_is_paid_statuses() ) ) {
			$actions['view_thankyou'] = 'Display thank you page';
		}
		return $actions;
	},
	9999,
	2
);


add_action(
	'woocommerce_order_action_view_thankyou',
	function( $order ) {
		if ( ! $order || ! is_a( $order, 'WC_Order' ) ) {
			return;
		}
		$url = $order->get_checkout_order_received_url();
		$url = add_query_arg( 'adm', $order->get_customer_id(), $url );
		wp_safe_redirect( $url );
		exit;
	}
);

add_filter(
	'determine_current_user',
	function( $user_id ) {
		if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) && ! empty( $_GET['adm'] ) ) {
			// Use absint for safety; Woo only needs a numeric user id; 0 means “guest”.
			$maybe_customer_id = absint( wp_unslash( $_GET['adm'] ) );
			if ( $maybe_customer_id > 0 ) {
					return $maybe_customer_id;
			}
			// For true guest orders (customer_id = 0), we do not impersonate; Woo allows viewing via order key.
			// If a login prompt still appears for guests, ensure the URL retains the ?key=wc_order_xxx part.
		}
		return $user_id;
	},
	10
);

add_filter( 'woocommerce_order_received_verify_known_shoppers', '__return_false' );

add_action(
	'woocommerce_before_thankyou',
	function( $order_id ) {
		$order    = wc_get_order( $order_id );
		$pm       = $order->get_payment_method();
		$button   = false;
		$item     = reset( $order->get_items() );
		$item_sku = $item->get_product()->get_sku();
		$is_pdc   = strpos( $item_sku, 'PDC' ) !== false;
		$is_nsca_voucher   = strpos( $item_sku, 'Voucher' ) !== false;
		$ty_page  = get_page_by_path( 'registration-success' );
		if ( $is_pdc ) {
			$ty_page = get_page_by_path( 'registration-success-pdc' );
		}
		if ( $pm === 'bacs' ) {
			$ty_page = get_page_by_path( 'registration-success/banktransfer' );
			if ( $is_pdc ) {
				$ty_page = get_page_by_path( 'registration-success-pdc/banktransfer' );
			}
		}
		if ( $pm === 'paypal_ptc' ) {
			$ty_page = get_page_by_path( 'registration-success/paypal' );
			if ( $is_pdc ) {
				$ty_page = get_page_by_path( 'registration-success-pdc/paypal' );
			}
			if ( get_locale() === 'en_US' ) {
				if ( $item->get_product()->get_type() === 'subscription' ) {
					$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=T8ZLRTFUFCBLW" class="push" target="_top">Pay with PayPal</a>';
					if ( $is_pdc ) {
						$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G698GX7ZX2J9A" class="push" target="_top">Pay with PayPal</a>';
					}
					if ( str_contains( $item->get_product()->get_sku(), 'ment' ) ) {
						$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5D2VGCMW8UN3G" class="push" target="_top">Pay with PayPal</a>';
						if ( $is_pdc ) {
							$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CB3U22FX2JKBG" class="push" target="_top">Pay with PayPal</a>';
						}
					}
				} else {
					 $button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7VDSELUTLP7HU" class="push" target="_top">Pay with PayPal</a>';
					if ( $is_pdc ) {
						$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UBC4EWW7W7VV4" class="push" target="_top">Pay with PayPal</a>';
					}
				}
			} elseif ( get_locale() === 'nl_NL' ) {
				if ( $item->get_product()->get_type() === 'subscription' ) {
					$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8BRDFVTCWHBBN" class="push" target="_top">Betaal met PayPal</a>';
					if ( str_contains( $item->get_product()->get_sku(), 'ment' ) ) {
						$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7227EABJ57PPA" class="push" target="_top">Betaal met PayPal</a>';
					}
				} else {
					 $button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KR95UC3QZM4GE" class="push" target="_top">Betaal met PayPal</a>';
				}
			} elseif ( get_locale() === 'ar' ) {
				if ( $item->get_product()->get_type() === 'subscription' ) {
					$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F7VB9JV7KQHJ4" class="push" target="_top">الدفع عن طريق باي بال</a>';
					if ( str_contains( $item->get_product()->get_sku(), 'ment' ) ) {
						$button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J4XQJW4SHWSPJ" class="push" target="_top">الدفع عن طريق باي بال</a>';
					}
				} else {
					 $button = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UX3RCPFA9366C" class="push" target="_top">الدفع عن طريق باي بال</a>';
				}
			}
		}

		if ( $is_nsca_voucher ) {
			$ty_page = get_page_by_path( 'registration-success/voucher' );
			if ( $pm === 'bacs' ) {
				$ty_page = get_page_by_path( 'registration-success/voucher-banktransfer' );
			}
		}
		if ( $ty_page ) {

			?>

		<hr>
		<div style="max-width:600px">
		<header class="entry-header ast-no-thumbnail">
			<h1 class="entry-title" itemprop="headline">

					<?php echo get_the_title( $ty_page ) ?>
			</h1>
		</header>
					<?php
							$content = get_the_content( null, false, $ty_page );
							$content = apply_filters( 'the_content', $content );
						$content     = str_replace( ']]>', ']]&gt;', $content );
						echo $content;
						echo $button;
					?>
					</div>
		<hr class="my-4">
					<?php
		}

	},
	1
);

remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );


// payment gateway col
add_filter( 'manage_edit-shop_order_columns', 'payment_gateway_orders_column' );
function payment_gateway_orders_column( $columns ) {
	 $new_columns = [];

	foreach ( $columns as $column_key => $column_label ) {
		if ( 'order_total' === $column_key ) {
			$new_columns['transaction_status'] = __( 'Gateway', 'woocommerce' );
		}

		$new_columns[ $column_key ] = $column_label;
	}
	 return $new_columns;
}

add_action( 'manage_shop_order_posts_custom_column', 'payment_gateway_orders_column_content' );
function payment_gateway_orders_column_content( $column ) {
	 global $the_order;

	if ( $column == 'transaction_status' ) {
		// Display
		echo '<div>' . $the_order->get_payment_method() . '</div>';
	}
}

/**
 * Add the fee during cart calculation at checkout (only applies to cart, not order creation).
 */
add_action(
	'woocommerce_cart_calculate_fees',
	function() {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		$payment_method = WC()->session->get( 'chosen_payment_method' );

		// Change bacs to another payment method ID where necessary
		if ( $payment_method === 'bacs' || $payment_method === 'paypal_ptc' ) {
			$percentage = 0.03;
			$cart       = WC()->cart;
			$cart_total = WC()->cart->cart_contents_total; // Ensures subtotal, shipping, and tax are included

			$surcharge = floatval( $cart_total * $percentage );
			if ( $surcharge > 0 ) {
				WC()->cart->add_fee( __( '3% service fee', 'woocommerce' ), $surcharge, true );
			}
		}
	},
	1,
	0
);

/**
 * Ensure the fee is correctly stored in the order, but prevent double addition.
 */
add_action(
	'woocommerce_checkout_create_order',
	function( $order, $data ) {
		$payment_method = $order->get_payment_method();

		// Prevent double-charging by checking if the fee already exists
		foreach ( $order->get_items( 'fee' ) as $fee ) {
			if ( $fee->get_name() === '3% service fee' ) {
				return; // Exit to avoid adding the fee again
			}
		}

		// Apply the fee only for selected payment methods
		if ( $payment_method === 'bacs' || $payment_method === 'paypal_ptc' ) {
			$percentage     = 0.03;
			$order_subtotal = $order->get_subtotal(); // Includes subtotal, shipping, and tax
			$surcharge      = floatval( $order_subtotal * $percentage );

			if ( $surcharge > 0 ) {
				$fee = new WC_Order_Item_Fee();
				$fee->set_name( __( '3% service fee', 'woocommerce' ) );
				$fee->set_total( $surcharge );
				$fee->set_tax_class( '' ); // Standard tax rate
				$fee->set_tax_status( 'taxable' );
				$order->add_item( $fee );

				// Ensure the total is recalculated after adding the fee
				$order->calculate_totals();
				$order->save();
			}
		}
	},
	10,
	2
);


// Add the fee to recurring orders too - you can test this from admin by manually creating a renewal order
add_filter(
	'wcs_new_order_created',
	function( $renewal_order, $subscription, $type ) {
			// Check the payment method of the subscription
			$payment_method = $subscription->get_payment_method();

			// Prevent double-charging by checking if the fee already exists
		foreach ( $renewal_order->get_items( 'fee' ) as $fee ) {
			if ( $fee->get_name() === '3% service fee' ) {
				return $renewal_order; // Exit to avoid adding the fee again
			}
		}

			// Add the fee only if the payment method matches
		if ( $payment_method === 'bacs' || $payment_method === 'paypal_ptc' ) {
				$percentage     = 0.03;
				$order_subtotal = $renewal_order->get_subtotal(); // Total of the renewal order
				$surcharge      = floatval( $order_subtotal * $percentage );

			// Add the fee to the renewal order
			if ( $surcharge > 0 ) {
				$fee = new WC_Order_Item_Fee();
				$fee->set_name( __( '3% service fee', 'woocommerce' ) );
				$fee->set_total( $surcharge );
				$fee->set_tax_class( '' ); // Standard tax rate
				$fee->set_tax_status( 'taxable' );
				$renewal_order->add_item( $fee );

				// Ensure the total is recalculated after adding the fee
				$renewal_order->calculate_totals();
				$renewal_order->save();
			}
		}

		return $renewal_order;
	},
	10,
	3
);

/**
 * Remove service fee if the payment method is changed to a non-applicable method.
 * I removed it because it still requires manual work, so fee applies.
 */
add_action(
	'xxwoocommerce_checkout_update_order_review',
	function( $post_data ) {
		parse_str( $post_data, $data );

		if ( ! isset( $data['payment_method'] ) ) {
			return;
		}

		$payment_method = sanitize_text_field( $data['payment_method'] );
		$order_id       = WC()->session->get( 'order_awaiting_payment' );

		if ( ! $order_id ) {
			return;
		}

		$order = wc_get_order( $order_id );

		// If the payment method is no longer 'bacs' or 'paypal_ptc', remove the fee
		if ( $payment_method !== 'bacs' && $payment_method !== 'paypal_ptc' ) {
			foreach ( $order->get_items( 'fee' ) as $fee_id => $fee ) {
				if ( $fee->get_name() === '3% service fee' ) {
						$order->remove_item( $fee_id );
				}
			}

			$order->calculate_totals();
			$order->save();
		}
	},
	10,
	1
);

// Trigger update_checkout on payment method change
add_action(
	'woocommerce_after_checkout_form',
	function() {
		wc_enqueue_js(
			"
		 $( 'form.checkout' ).on( 'change', 'input[name^=\'payment_method\']', function() {
				 $('body').trigger('update_checkout');
				 console.log('update_checkout');
			});
 "
		);
	}
);


remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action(
	'woocommerce_checkout_billing',
	function() {
		?>
	<h3><?php _e( 'Payment', 'woocommerce' ) ?></h3>
		<?php
	},
	20
);
add_action( 'woocommerce_checkout_billing', 'woocommerce_checkout_payment', 20 );



add_action(
	'woocommerce_checkout_after_order_review',
	function() {
		if ( get_locale() === 'en_US' ) :
			get_template_part( 'templates/checkout-testimonial/en_US' );
		elseif ( get_locale() === 'nl_NL' ) :
			get_template_part( 'templates/checkout-testimonial/nl_NL' );
		elseif ( get_locale() === 'ar' ) :
			get_template_part( 'templates/checkout-testimonial/ar' );
		endif;
	}
);

// Good tutorial:
// https://stackoverflow.com/questions/75457769/conditionally-required-woocommerce-checkout-field

add_action(
	'woocommerce_checkout_process',
	function() {
		// validate the woocommerce nonce
		if ( ! isset( $_POST['woocommerce-process-checkout-nonce'] ) || ! wp_verify_nonce( $_POST['woocommerce-process-checkout-nonce'], 'woocommerce-process_checkout' ) ) {
			 wc_add_notice( __( 'Checkout Validation Error, Please Try Refreshing The Page' ), 'ptc' );
			 return;
		}
		// ADD message to VAT field if empty
		if (
			isset( $_POST['billing_notice'] ) &&
			'business' === $_POST['billing_notice'] &&
			class_exists( 'WC_EU_VAT_Number' ) &&
			isset( $_POST['billing_country'] ) &&
			in_array( $_POST['billing_country'], WC_EU_VAT_Number::get_eu_countries(), true ) &&
			isset( $_POST['billing_vat_number'] ) &&
			empty( $_POST['billing_vat_number'] )
			) {
				wc_add_notice( _x( 'Please enter your VAT number so we can invoice your company and you can declare the course as a business expense.', 'Checkout validation msg', 'ptc' ), 'success', [ 'id' => 'woocommerce_eu_vat_number' ] );
		}
	}
);

add_action(
	'woocommerce_checkout_update_order_review',
	function ( $x ) {
		if ( get_locale() === 'en_US' ) :
			wp_parse_str( $x, $args );
			if ( array_key_exists( 'billing_country', $args ) && ( $args['billing_country'] === 'NL' || $args['billing_country'] === 'BE' ) ) {
				$contents = WC()->cart->get_cart_contents();
				$item_sku = false;
				if ( $contents ) {
					foreach ( $contents as $item ) {
						$item_sku = $item['data']->get_sku();
						break;
					}
				}
				if ( $item_sku && strpos( $item_sku, 'PTC' ) !== false ) {
					wc_add_notice( 'Het lijkt erop dat je uit Nederland of België komt. Wist je dat er ook een <a href="https://mennohenselmans.com/online-pt-cursus-nederlands/" target="_blank">Nederlandse cursus</a> is?', 'success', [ 'id' => 'billing_country' ] );
				}
			}
			if ( array_key_exists( 'billing_email', $args ) ) {
				if ( email_exists( $args['billing_email'] ) && ! is_user_logged_in() ) {
						wc_add_notice( 'Email already exists, please log in at the top of the page.', 'success', [ 'id' => 'billing_email' ] );
				}
			}
		endif;
		if ( get_locale() === 'ar' ) :
			wp_parse_str( $x, $args );
			// United Arab Emirates (UAE): AE
			// Qatar: QA
			// Saudi Arabia: SA
			// Oman: OM
			// Bahrain: BH
			// Jordan: JO
			if ( array_key_exists( 'billing_country', $args ) && (
					$args['billing_country'] === 'AE' ||
					$args['billing_country'] === 'QA' ||
					$args['billing_country'] === 'SA' ||
					$args['billing_country'] === 'OM' ||
					$args['billing_country'] === 'BH' ||
					$args['billing_country'] === 'JO'
					)
				) {
				$contents = WC()->cart->get_cart_contents();
				$item_sku = false;
				if ( $contents ) {
					foreach ( $contents as $item ) {
						$item_sku = $item['data']->get_sku();
						break;
					}
				}
				if ( $item_sku && strpos( $item_sku, 'PTC' ) !== false ) {
					wc_add_notice( 'يبدو أنك من دولة ناطقة باللغة العربية. هل تعلم أنه يمكنك أيضاً التسجيل في <a href="https://mennohenselmans.com/online-pt-course-ar/" target="_blank">الدورة العربية</a>؟', 'success', [ 'id' => 'billing_country' ] );
				}
			}
		endif;
	}
);


// pass strength medium
add_filter(
	'woocommerce_min_password_strength',
	function ( $strength ) {
		// 3 => Strong (default) | 2 => Medium | 1 => Weak | 0 => Very Weak (anything).
		return 2;
	}
);




/**
 * UTM tags forward to checkout url
 *
 * A custom endopoint website.com/?atc_id=101&other_param=388 where atc_id is the
 * woocommerce product ID. When a user arrives there, the Woocommerce cart is
 * emptied, product with id 101 is added to cart and the user is redirected to
 * the checkout page, with the query string intact so:
 * website.com/checkout/?other_param=388
 */
add_action( 'init', 'custom_endpoint_handler' );

function custom_endpoint_handler() {
	if ( ! isset( $_GET['atc_id'] ) || empty( $_GET['atc_id'] ) ) {
		return;
	}

	// Sanitize and validate product ID
	$product_id = intval( sanitize_key( $_GET['atc_id'] ) );
	if ( ! wc_get_product( $product_id ) ) {
		// Handle invalid product ID here, for example redirect to homepage
		echo 'Product does not exist';
		exit;
	}

	if ( ! WC()->session->has_session() ) {
		WC()->session->set_customer_session_cookie( true );
	}

	// Empty the cart
	if ( ! WC()->cart->is_empty() ) {
		WC()->cart->empty_cart();
	}
	// Add product to cart
	WC()->cart->add_to_cart( $product_id, 1 );

	// Get original query parameters (excluding id)
	$other_params = $_GET;
	unset( $other_params['atc_id'] );

	// Build redirect URL to checkout with query parameters

	$url = wc_get_checkout_url();
	if ( ! empty( $other_params ) ) {
		$url .= '?' . http_build_query( $other_params );
	}
	// Redirect to checkout
	wp_safe_redirect( $url );
	exit;
}

// Xero VAT number meta
add_filter(
	'woocommerce_xero_vat_number_meta_key',
	function() {
		return '_billing_vat_number';
	}
);

add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );

// add message to checkout blockUI
add_action(
	'wp_head',
	function() {
		echo '<style>
 form.checkout > .blockUI.blockOverlay::before {
    translate: 0 80px;
}
form.checkout > .blockUI.blockMsg {
    position: fixed !important;
    left: 50% !important;
    top: 50% !important;
    transform: translate(-50%, -50%);
}
</style>';
	},
	100
);

// Disable paypal for vouchers
add_filter(
	'woocommerce_available_payment_gateways',
	function( $available_gateways ) {
		if ( is_admin() || ! is_checkout() ) {
			return $available_gateways; // Ensure it only runs on checkout
		}

		$gateways_to_disable = [ 'paypal_ptc' ];

		// Check if any product in the cart has a restricted SKU
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product     = $cart_item['data'];
			$product_sku = $product->get_sku();
			if ( str_contains( $product_sku, 'NSCA' ) ) {
				foreach ( $gateways_to_disable as $gateway ) {
					if ( isset( $available_gateways[ $gateway ] ) ) {
						unset( $available_gateways[ $gateway ] ); // Remove the gateway
						return $available_gateways;
					}
				}
			}
		}
		return $available_gateways;
	}
);

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
