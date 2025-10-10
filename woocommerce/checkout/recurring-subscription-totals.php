<?php
/**
 * Recurring cart subtotals totals
 *
 * @author  WooCommerce
 * @package WooCommerce Subscriptions/Templates
 * @version 1.0.0 - Migrated from WooCommerce Subscriptions v3.1.0
 */

defined( 'ABSPATH' ) || exit;
$display_heading = true;

foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) { ?>
	<tr class="order-total recurring-total">
		<td colspan="2" data-title="<?php esc_attr_e( 'Recurring total', 'woocommerce-subscriptions' ); ?>">
		<?php

		echo wp_kses_post( wcs_add_cart_first_renewal_payment_date( '', $recurring_cart ) );
		?>
	</td>

	</tr> 
	<?php
}
