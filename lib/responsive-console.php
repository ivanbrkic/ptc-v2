<?php
/**
 * Responsive console.
 */

/**
 * Check if SHOW_RESPONSIVE_CONSOLE is set.
 */
if ( ! ( defined( 'SHOW_RESPONSIVE_CONSOLE' ) && SHOW_RESPONSIVE_CONSOLE ) ) {
	return;
}

/**
 * Add responsive console HTML.
 */
add_action(
	'wp_footer',
	function() {
		echo '
		<div class="responsive-console">
			<div class="responsive-console__marker"></div>
			<div class="responsive-console__svg-wrapper">
				<svg class="responsive-console__svg" viewBox="0 -300 800 300" preserveAspectRatio="none"></svg>
			</div>
		</div>';
	}
);

/**
 * Add responsive console assets.
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		if ( Pg_Assets::asset_exists( 'responsive-console/responsive-console.js' ) ) {
			wp_enqueue_script( 'pg-responsive-console', Pg_Assets::get_asset_uri( 'responsive-console/responsive-console.js' ), [ 'jquery' ], Pg_Assets::get_asset_version( 'responsive-console/responsive-console.js' ), true );
		}

		if ( Pg_Assets::asset_exists( 'responsive-console/responsive-console.css' ) ) {
			wp_enqueue_style( 'pg-responsive-console', Pg_Assets::get_asset_uri( 'responsive-console/responsive-console.css' ), [], Pg_Assets::get_asset_version( 'responsive-console/responsive-console.css' ) );
		}
	}
);
