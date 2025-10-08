<?php

// Add name and last name to register
add_action(
	'woocommerce_register_form_start',
	function() {
		?>

<p class="form-row form-row-first">
	<label for="reg_billing_first_name"><?php _ex( 'First name', 'Checkout field', 'ptc' ); ?><span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="
		<?php
		if ( ! empty( $_POST['billing_first_name'] ) ) {
			esc_attr_e( $_POST['billing_first_name'] );}
		?>
		" />
</p>
<p class="form-row form-row-last">
	<label for="reg_billing_last_name"><?php _ex( 'Last name', 'Checkout field', 'ptc' ); ?><span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="
		<?php
		if ( ! empty( $_POST['billing_last_name'] ) ) {
			esc_attr_e( $_POST['billing_last_name'] );}
		?>
		" />
</p>
<div class="clear"></div>
		<?php
	}
);

// validate register fields
add_action(
	'woocommerce_register_post',
	function( $username, $email, $validation_errors ) {
		if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
				 $validation_errors->add( 'billing_first_name_error', _x( '<strong>Error</strong>: First name is required!', 'Checkout field validation', 'ptc' ) );
		}
		if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
				 $validation_errors->add( 'billing_last_name_error', _x( '<strong>Error</strong>: Last name is required!.', 'Checkout field validation', 'ptc' ) );
		}
		 return $validation_errors;
	},
	10,
	3
);

/**
* Below code saves extra fields.
*/
add_action(
	'woocommerce_created_customer',
	function( $customer_id ) {
		if ( isset( $_POST['billing_first_name'] ) ) {
				 // First name field which is by default
				 update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
				 // First name field which is used in WooCommerce
				 update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
		}
		if ( isset( $_POST['billing_last_name'] ) ) {
				 // Last name field which is by default
				 update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
				 // Last name field which is used in WooCommerce
				 update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
		}
	}
);


// Add a second password field to the checkout page in WC 3.x.
add_filter( 'woocommerce_checkout_fields', 'wc_add_confirm_password_checkout', 10, 1 );
function wc_add_confirm_password_checkout( $checkout_fields ) {
	if ( get_option( 'woocommerce_registration_generate_password' ) == 'no' ) {
		$checkout_fields['account']['account_password2'] = [
			'type'        => 'password',
			'label'       => _x( 'Confirm password', 'Checkout field label', 'ptc' ),
			'required'    => true,
			'placeholder' => _x( 'Confirm Password', 'Checkout field label placeholder', 'ptc' ),
		];
	}

	return $checkout_fields;
}

// Check the password and confirm password fields match before allow checkout to proceed.
add_action( 'woocommerce_after_checkout_validation', 'wc_check_confirm_password_matches_checkout', 10, 2 );
function wc_check_confirm_password_matches_checkout( $posted ) {
	$checkout = WC()->checkout;
	if ( ! is_user_logged_in() && ( $checkout->must_create_account || ! empty( $posted['createaccount'] ) ) ) {
		if ( strcmp( $posted['account_password'], $posted['account_password2'] ) !== 0 ) {
			wc_add_notice( _x( 'Passwords do not match.', 'Checkout field validation', 'ptc' ), 'error', [ 'id' => 'account_password2' ] );
		}
	}
}

add_filter(
	'woocommerce_checkout_fields',
	function( $fields ) {
		if ( is_array( $fields ) && array_key_exists( 'account', $fields ) && array_key_exists( 'account_username', $fields['account'] ) ) {
			$fields['account']['account_username']['description'] = _x( "This is the username that you'll use to log in to the course's e-learning environment. Your username should not have special characters, spaces, or be your email address.", 'Checkout field description', 'ptc' );
		}
		return $fields;

	}
);
