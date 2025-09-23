<?php
/**
 * Theme setup.
 */

/**
 * Init theme features.
 */
add_action(
	'after_setup_theme',
	function() {
		// Load text domain.
		load_theme_textdomain( 'playground', get_template_directory() . '/languages' );

		// Theme supports.
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ] );

		// Register menus.
		register_nav_menus(
			[
				'header_navigation' => __( 'Header Navigation', 'playground' ),
			]
		);
	}
);

/**
 * Theme assets.
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		// Use new version of jQuery on CDN and move it to the footer.
		if ( ! is_admin() ) {
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', 'https://code.jquery.com/jquery-3.4.1.min.js', [], '3.4.1', true );
			wp_enqueue_script( 'jquery' );
		}

		// Theme vendors JavaScript.
		if ( Pg_Assets::asset_exists( 'theme/js/vendor.js' ) ) {
			wp_enqueue_script( 'pg-vendor', Pg_Assets::get_asset_uri( 'theme/js/vendor.js' ), [], Pg_Assets::get_asset_version( 'theme/js/vendor.js' ), true );
		}

		// Theme main JavaScript.
		if ( Pg_Assets::asset_exists( 'theme/js/main.js' ) ) {
			wp_enqueue_script( 'pg-main', Pg_Assets::get_asset_uri( 'theme/js/main.js' ), [], Pg_Assets::get_asset_version( 'theme/js/main.js' ), true );
		}

		// Theme main CSS.
		if ( Pg_Assets::asset_exists( 'theme/css/main.css' ) ) {
			wp_enqueue_style( 'pg-main', Pg_Assets::get_asset_uri( 'theme/css/main.css' ), [], Pg_Assets::get_asset_version( 'theme/css/main.css' ) );
		}
	},
	PHP_INT_MAX
);

/**
 * Head scripts.
 */
add_action(
	'wp_head',
	function() {
		// Check if JavaScript is enabled (https://www.paulirish.com/2009/avoiding-the-fouc-v3/).
		echo '<script>(function(el){el.classList.replace("no-js","js")})(document.documentElement)</script>';
	},
	1
);

/**
 * Get sprite SVG file.
 *
 * @return void
 */
function pg_get_sprite() {
	// Insert sprite to HTML after opening body tag.
	echo Pg_Assets::get_sprite();
}
add_action( 'wp_body_open', 'pg_get_sprite', 0 );

/**
 * Edit excerpt limit.
 *
 * @param int $length
 * @return int
 */
add_filter(
	'excerpt_length',
	function ( $length ) {
		$length = 30;

		return $length;
	},
	PHP_INT_MAX
);

/**
 * Edit excerpt more text.
 *
 * @param string $more_string
 * @return string
 */
add_filter(
	'excerpt_more',
	function ( $more_string ) {
		$more_string = ' &hellip;';

		return $more_string;
	},
	PHP_INT_MAX
);

/**
 * Disable prefixes on archive titles.
 */
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
