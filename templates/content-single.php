<?php
if ( is_checkout() ) {
	?>
	<div class="secure">
	<?php
	if ( ! is_wc_endpoint_url( 'order-received' ) ) {
		?>
		<img src="/wp-content/themes/ptc/assets/dist/theme/img/secure.svg" alt="secure connection">
		<div>
			<?php _e( 'Secure Checkout', 'ptc' ); ?>
			<div><?php _e( '256-bit, Bank-Grade TLS Encryption', 'ptc' ); ?></div>
		</div>
		<?php
	}
	?>
	</div>
	<?php
}
?>
<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<?php
		$add_class = '';
	if ( is_account_page() || is_cart() || is_checkout() || is_woocommerce() ) {
		$add_class = '';
	} else {
		$add_class = 'mx-auto mw-640';
	}
	?>

	<article>
		<?php
		$header_class = '';
		if ( is_account_page() || is_cart() || is_checkout() || is_woocommerce() ) {
			// $header_class = 'visually-hidden';
		}
		?>
		<header class="post-header py-2 <?php echo $header_class; ?>" data-title="<?php the_title(); ?>">
			<div class="wrapper">
				<div class="<?php echo esc_attr( $add_class ); ?>">
					<h1 class="t-h1"><?php the_title(); ?></h1>
				</div>
			</div>
		</header>
		<div class="wrapper">
		<div <?php post_class( 'pt-2 pb-5 the-content editor ' . $add_class ); ?>>
				<?php the_content(); ?>
			</div>
		</div>
	</article>

<?php endwhile; ?>
