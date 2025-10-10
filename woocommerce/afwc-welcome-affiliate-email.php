<?php
/**
 * Welcome email for affiliate (Affiliate - Welcome Email)
 *
 * @package     affiliate-for-woocommerce/templates/
 * @since       2.4.0
 * @version     1.1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Affiliate's first name */ ?>
<p><?php echo sprintf( esc_html__( 'Hi %s,', 'affiliate-for-woocommerce' ), esc_html( $user_name ) ); ?></p>

<p><?php echo esc_html__( 'Your affiliate request has been approved.', 'affiliate-for-woocommerce' ); ?></p>
<p><?php echo esc_html__( 'We are excited to have you as our affiliate partner. Here are the details you will need to get started:', 'affiliate-for-woocommerce' ); ?></p>
<p><strong><?php echo esc_html_x( 'Your affiliate dashboard', 'affiliate dashboard page text', 'affiliate-for-woocommerce' ); ?></strong></p>
<p>
	<?php
		/* translators: %1$s: Opening a tag for affiliate my account link %2$s: closing a tag for affiliate my account link */
		echo sprintf( esc_html_x( 'Log in to %1$syour affiliate dashboard%2$s regularly. You will see marketing assets and a complete record of your referrals and payouts there. You can fully manage your account from the dashboard.', 'Custom affiliate email', 'ptc' ), '<a href="' . esc_url( $my_account_afwc_url ) . '">', '</a>' );
	?>
</p>
<p><?php echo esc_html_x( 'Sales are matched to your account when your discount code is used.', 'Custom affiliate email', 'ptc' ); ?></p>

<p><strong><?php echo esc_html_x( 'Our products and discount codes', 'Custom affiliate email', 'ptc' ); ?></strong></p>
<p>
	<?php
		echo sprintf( esc_html_x( 'You can refer people using your affiliate link. ', 'Custom affiliate email', 'ptc' ) );
	if ( ! empty( $shop_page ) ) {
		/* translators: %1$s: Opening a tag for link %2$s: closing a tag for link */
		echo sprintf( esc_html_x( 'Here is the %1$slink to all our course products%2$s.', 'Custom affiliate email', 'ptc' ), '<a href="' . esc_url( $shop_page ) . '">', '</a>' );
	}
	echo sprintf( esc_html_x( 'For each course round there are two products, the course paid in full and an option with monthly payments. Your coupon code works for both products and is $,100-', 'Custom affiliate email', 'ptc' ) );
	?>
</p>
<?php
// get coupons for affiliate where afwc_referral_coupon_of post_meta is affiliate id
$coupons = new WP_Query(
	[
		'post_type'      => 'shop_coupon',
		'posts_per_page' => 1,
		'meta_query'     => [
			[
				'key'   => 'afwc_referral_coupon_of',
				'value' => $affiliate_id,
			],
		],
	]
);

if ( $coupons->have_posts() ) {
	while ( $coupons->have_posts() ) {
		$coupons->the_post();
		$coupon_code = get_the_title();
		?>
		<p><strong><?php echo esc_html_x( 'Your code:', 'Custom affiliate email', 'ptc' ); ?> <?php echo $coupon_code ?></strong></p>

		<?php
		break;
	}
	wp_reset_postdata();
} else {
	?>
	<p><?php echo esc_html_x( 'You can find your code inside your affiliate dashboard inside your profile.', 'Custom affiliate email', 'ptc' ); ?></p>
	<?php
}
?>
<p><?php echo esc_html_x( 'If you cannot find your code, you can contact info@mennohenselmans.com.', 'Custom affiliate email', 'ptc' ); ?></p>
<p><strong><?php echo esc_html_x( 'Partnership and communication are important to us', 'Custom affiliate email', 'ptc' ); ?></strong></p>
<p><?php echo esc_html_x( 'We are happy to assist any time. You are welcome to reach out to us anytime.', 'Custom affiliate email', 'ptc' ); ?></p>
<p><strong><?php echo esc_html_x( 'Personal note before signing off', 'Custom affiliate email', 'ptc' ); ?></strong></p>
<p><?php echo esc_html_x( 'The most important thing I have learned working with our partners is that the best way to succeed is to start active promotions quickly. If you take quick action, you may as well become one of our superstar partners! Looking forward to working closely with you.', 'Custom affiliate email', 'ptc' ); ?></p>
<p><?php echo esc_html_x( '- Vincent (Henselmans team)', 'Custom affiliate email', 'ptc' ); ?></p>
<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/**
 * Output the email footer
 *
 * @hooked WC_Emails::email_footer() Output the email footer.
 * @param string $email.
 */
do_action( 'woocommerce_email_footer', $email );
