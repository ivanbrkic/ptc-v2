<header class="header">
	<div class="container-fluid">
		<div class="header__inner">
			<a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				echo Pg_Assets::get_theme_img(
					'logo.png',
					[
						'loading'  => 'eager',
						'decoding' => 'auto',
						/* translators: %s is the site name. */
						'alt'      => sprintf( esc_attr__( '%s logo', 'playground' ), esc_attr( get_bloginfo( 'name' ) ) ),
					]
				);
				?>
			</a>

			<?php $header_navigation = ( new Log1x\Navi\Navi() )->build( 'header_navigation' ); ?>
			<?php if ( $header_navigation->isNotEmpty() ) { ?>
				<nav class="header__navigation">
					<ul class="list-unstyled d-flex">
						<?php foreach ( $header_navigation->all() as $header_navigation_item ) : ?>
							<li>
								<?php
								$link_classes = [ 'd-block', 'p-2' ];
								if ( $header_navigation_item->active ) {
									$link_classes[] = 'text-black';
								} else {
									$link_classes[] = 'text-white';
								}
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
	</div>
</header>
