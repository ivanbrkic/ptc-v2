<?php
/**
 * Cleanup.
 */

/**
 * Disable the emoji's.
 */
add_action(
	'init',
	function() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter(
			'tiny_mce_plugins',
			function( $plugins ) {
				return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
			}
		);
	}
);

/**
 * Remove WordPress head tags.
 */
add_action(
	'init',
	function() {
		add_filter( 'show_recent_comments_widget_style', '__return_false' );
		add_filter( 'the_generator', '__return_false' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}
);

/**
 * Disable Gutenberg assets.
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		// Block CSS.
		wp_dequeue_style( 'wp-block-library' );
		// Inline CSS.
		wp_dequeue_style( 'global-styles' );
		// Classic theme CSS.
		wp_dequeue_style( 'classic-theme-styles' );
	}
);

/**
 * Disable gallery block inline CSS.
 */
remove_action( 'init', 'register_block_core_gallery' );

/**
 * Disable Gutenberg inline CSS in footer.
 */
add_action(
	'wp_footer',
	function() {
		wp_dequeue_style( 'core-block-supports' );
	}
);

/**
 * Remove update nag on all environments except development.
 */
add_action(
	'admin_init',
	function() {
		if ( ! defined( 'WP_ENV' ) || 'development' === WP_ENV ) {
			return;
		}

		remove_action( 'admin_notices', 'update_nag', 3 );
		remove_action( 'network_admin_notices', 'update_nag', 3 );
	}
);
