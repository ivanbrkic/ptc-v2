<footer class="footer ">
	<div class="wrapper">
		&copy; <?php echo esc_html( gmdate( 'Y' ) ) . ' ' . esc_html( get_bloginfo() ); ?>
		<?php $header_navigation = ( new Log1x\Navi\Navi() )->build( 'footer_menu' ); ?>
			<?php if ( $header_navigation->isNotEmpty() ) { ?>
				<nav class="footer__navigation mt-1">
					<ul class="list-unstyled d-flex">
						<?php foreach ( $header_navigation->all() as $header_navigation_item ) : ?>
							<li>
								<?php
								$link_classes = [ 'd-block' ];
								?>
								<a class="<?php echo esc_attr( join( ' ', $link_classes ) ); ?>" href="<?php echo esc_url( $header_navigation_item->url ); ?>">
									<?php echo esc_html( $header_navigation_item->label ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php } ?>

	</div>
</footer>
