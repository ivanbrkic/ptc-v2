<?php
/**
 * Functions.
 */

/**
 * Composer.
 */
if ( ! file_exists( trailingslashit( get_template_directory() ) . 'vendor/autoload.php' ) ) {
	/* translators: %s is the command to run. */
	wp_die( sprintf( esc_html__( 'Error locating autoloader. Please run %s in the theme directory.', 'playground' ), '<code>composer install</code>' ) );
}

require trailingslashit( get_template_directory() ) . 'vendor/autoload.php';

/**
 * Theme includes.
 *
 * Include your files here which are not in the lib directory (IE: add 'my-dir/my-file.php' which will include that file also) or if you want to make sure some files load before (IE: add 'lib/my-important-file.php' which will cause 'lib/my-important-file.php' to be included before any other file).
 */
$theme_includes = [];

/**
 * Recursively scans through dir "lib" and includes all files.
 *
 * @param array $files Array of file paths to include.
 * @param string $path Path before the file name.
 * @return array Array of all files from dir "lib" and its subdirectories.
 */
function pg_get_lib_files( array $files = [], string $path = 'lib/' ): array {
	$includes = array_diff( scandir( trailingslashit( get_template_directory() ) . $path ), [ '..', '.' ] );

	if ( $includes ) {
		foreach ( $includes as $name ) {
			if ( is_dir( trailingslashit( get_template_directory() ) . $path . $name ) ) {
				$files = pg_get_lib_files( $files, $path . trailingslashit( $name ) );
			} else {
				$files[] = $path . $name;
			}
		}
	}

	return $files;
}

/**
 * Merge all theme files and remove duplicates.
 */
$theme_includes = array_unique( array_merge( $theme_includes, pg_get_lib_files() ) );

/**
 * Load the files.
 */
foreach ( $theme_includes as $file ) {
	if ( ! locate_template( $file, true ) ) {
		/* translators: %s is the file path. */
		wp_die( sprintf( esc_html__( 'Error locating %s for inclusion.', 'playground' ), '<code>' . esc_html( $file ) . '</code>' ) );
	}
}

/**
 * Clean up.
 */
unset( $theme_includes, $file );
