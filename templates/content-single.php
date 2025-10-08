<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<?php
		$article_class = 'mx-auto the-content py-5';
		if ( is_account_page() || is_cart() || is_checkout() || is_woocommerce() ) {
			$article_class .= ' is-woo';
		} else {
			$article_class .= ' mw-640';
		}
		?>
	<article <?php post_class($article_class); ?>>
		<?php
		$header_class = '';
		if ( is_account_page() || is_cart() || is_checkout() || is_woocommerce() ) {
			$header_class = 'visually-hidden';
		}
		?>
		<header class="<?php echo $header_class; ?>" data-title="<?php the_title(); ?>">
			<h1><?php the_title(); ?></h1>
		</header>

		<div class="editor">
			<?php the_content(); ?>
		</div>
	</article>
<?php endwhile; ?>
