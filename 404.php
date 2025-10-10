<div class="py-5 the-content">
	<h1><?php echo pg_get_page_header(); ?></h1>
	<p><?php esc_html_e( 'Sorry, but the page you were trying to view does not exist.', 'playground' ); ?></p>
	<a href="<?php echo home_url(); ?>" class="btn btn-primary"><?php esc_html_e( 'Return to homepage', 'playground' ); ?></a>
	<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=T8ZLRTFUFCBLW" class="push" target="_top">Pay with PayPal</a>
</div>
