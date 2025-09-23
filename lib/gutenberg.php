<?php
/**
 * Gutenberg.
 */

/**
 * Remove Gutenberg features.
 */
add_action(
	'after_setup_theme',
	function() {
		// Disable font sizes.
		add_theme_support( 'disable-custom-font-sizes' );
		add_theme_support( 'editor-font-sizes', [] );

		// Disable color palette.
		add_theme_support( 'disable-custom-colors' );
		add_theme_support( 'editor-color-palette', [] );

		// Disable color gradients.
		add_theme_support( 'disable-custom-gradients' );
		add_theme_support( 'editor-gradient-presets', [] );

		// Disable core block patterns.
		remove_theme_support( 'core-block-patterns' );
	}
);

/**
 * Edit some Gutenberg settings.
 */
add_filter(
	'block_editor_settings_all',
	function( $editor_settings ) {
		$editor_settings['__experimentalFeatures']['typography']            = []; // Disable typography setting.
		$editor_settings['__experimentalFeatures']['typography']['dropCap'] = false; // Disable drop cap.

		return $editor_settings;
	}
);

/**
 * Add Gutenberg admin scripts.
 */
add_action(
	'enqueue_block_editor_assets',
	function() {
		if ( Pg_Assets::asset_exists( 'admin/gutenberg.js' ) ) {
			wp_enqueue_script( 'pg-gutenberg', Pg_Assets::get_asset_uri( 'admin/gutenberg.js' ), [ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'lodash' ], Pg_Assets::get_asset_version( 'admin/gutenberg.js' ), true );
		}
	}
);
