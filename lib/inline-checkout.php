<?php

	// https://gist.github.com/lucasjahn/2da5405d3bd7d3e12555e902c25ed784
	/**
	 * You can trigger ajax update with $( 'body' ).trigger( 'update_checkout' );
	 * hook to woocommerce_checkout_update_order_review to add a notice
	 * notices display inline - make sure to add id so the inliner knows where to put the notice
	 * ex. wc_add_notice( 'Ha', 'error', ['id' => 'billing_email'] );
	 */

	/**
	 * adds custom js file to handle inline error messages
	 */

	add_action( 'woocommerce_after_checkout_form', 'add_custom_validation_script', 20 );

	function add_custom_validation_script() {
		wp_enqueue_script(
			'inline_validation_script',
			get_stylesheet_directory_uri() . '/assets/dist/theme/js/inline-checkout.js',
			[ 'jquery' ], 678
		);
	}



	/**
	 * adds error message field element to get inline error messages working
	 *
	 * @param array $fields
	 * @param object $errors
	 */

	add_filter( 'woocommerce_form_field', 'add_inline_error_messages_element', 10, 4 );

	function add_inline_error_messages_element( $field, $key, $args, $value ) {
		if ( strpos( $field, '</span></p>' ) !== false ) {
				$error = '<span class="js-custom-error-message" style="display:none"></span>';
			$field     = substr_replace( $field, $error, strpos( $field, '</span></p>' ), 0 );
		}
		return $field;
	}
