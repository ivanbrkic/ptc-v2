<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<?php get_template_part( 'templates/head' ); ?>

	<body class="style-guide">
		<?php
		/**
		 * Use wp_body_open() so other plugins can hook in to wp_body_open hook.
		 *
		 * @hooked pg_get_sprite()
		 */
		wp_body_open();

		/**
		 * Add get_header hook so other plugins can hook in.
		 * We have to add it manually since we're not using get_header() because of our wrapper.
		 */
		do_action( 'get_header' );
		?>

		<header class="style-guide-header">
			<h1>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
					/* translators: %s is the site name. */
					printf( esc_html__( '%s Style Guide', 'playground' ), esc_html( get_bloginfo( 'name' ) ) );
					?>
				</a>
			</h1>
		</header>

		<div class="style-guide-content">
			<aside class="style-guide-sidebar">
				<nav class="style-guide-sidebar__nav" aria-label="<?php esc_attr_e( 'Style guide navigation', 'playground' ); ?>">
					<ul class="style-guide-sidebar__list">
						<?php foreach ( Pg_Style_Guide::get_sections() as $group_id => $group_sections ) : ?>
							<li>
								<div class="style-guide-sidebar__list-header">
									<?php echo esc_html( ucfirst( str_replace( '-', ' ', $group_id ) ) ); ?>
								</div>
								<ul class="style-guide-sidebar__sub-list">
									<?php foreach ( $group_sections as $section ) : ?>
										<?php $section_id = $group_id . '-' . $section['id']; ?>
										<li><a href="#<?php echo esc_attr( $section_id ); ?>"><?php echo esc_html( $section['title'] ); ?></a></li>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			</aside>
			<main class="style-guide-main">
				<?php foreach ( Pg_Style_Guide::get_sections() as $group_id => $group_sections ) : ?>
					<?php foreach ( $group_sections as $section ) : ?>
						<?php $section_id = $group_id . '-' . $section['id']; ?>

						<section id="<?php echo esc_attr( $section_id ); ?>" class="style-guide-section">
							<h1>
								<a href="#<?php echo esc_attr( $section_id ); ?>"><?php echo esc_html( $section['title'] ); ?></a>
							</h1>

							<?php Pg_Style_Guide::get_section_template_part( $section ); ?>
						</section>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</main>
		</div>

		<footer class="style-guide-footer">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ) . ' ' . esc_html( get_bloginfo() ); ?></p>
		</footer>

		<?php
		/**
		 * Add get_footer hook so other plugins can hook in.
		 * We have to add it manually since we're not using get_footer() because of our wrapper.
		 */
		do_action( 'get_footer' );

		wp_footer();
		?>
	</body>
</html>
