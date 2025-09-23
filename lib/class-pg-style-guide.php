<?php
/**
 * Pg_Style_Guide helper class.
 */

/**
 * Pg_Style_Guide class.
 */
final class Pg_Style_Guide {
	/**
	 * Style guide directory name.
	 *
	 * @var string
	 */
	private static $directory_name = 'style-guide';

	/**
	 * Style guide slug.
	 *
	 * @var string
	 */
	private static $slug = 'style-guide';

	/**
	 * Sections directory name.
	 *
	 * @var string
	 */
	private static $sections_directory_name = 'sections';

	/**
	 * Test data directory name.
	 *
	 * @var string
	 */
	private static $test_data_directory_name = 'test-data';

	/**
	 * Style guide directory path.
	 *
	 * @var string
	 */
	private static $directory_path;

	/**
	 * Sections directory path.
	 *
	 * @var string
	 */
	private static $sections_path;

	/**
	 * Test data directory path.
	 *
	 * @var string
	 */
	private static $test_data_directory_path;

	/**
	 * Style guide sections.
	 *
	 * @var array
	 */
	private static $sections = [];

	/**
	 * SCSS variables.
	 *
	 * @var array
	 */
	private static $scss_variables = [];

	/**
	 * Init the class.
	 *
	 * @return void
	 */
	public static function init(): void {
		self::set_paths();
		self::set_scss_variables_data();
		self::init_hooks();
	}

	/**
	 * Set paths.
	 *
	 * @return void
	 */
	private static function set_paths(): void {
		self::$directory_path           = trailingslashit( get_template_directory() ) . trailingslashit( self::$directory_name );
		self::$sections_path            = trailingslashit( self::$directory_path ) . trailingslashit( self::$sections_directory_name );
		self::$test_data_directory_path = trailingslashit( self::$directory_path ) . trailingslashit( self::$test_data_directory_name );
	}

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	private static function init_hooks(): void {
		add_action( 'init', [ self::class, 'add_rewrite_endpoint' ] );
		add_action( 'template_redirect', [ self::class, 'set_sections' ] );
		add_filter( 'pg_wrapper_base', [ self::class, 'load_base_file' ] );
		add_action( 'wp_enqueue_scripts', [ self::class, 'enqueue_assets' ], PHP_INT_MAX );
		add_action( 'show_admin_bar', [ self::class, 'hide_admin_bar' ] );
	}

	/**
	 * Get directory data.
	 *
	 * @param string $dir_path
	 * @return array|null
	 */
	private static function get_dir_data( string $dir_path ): ?array {
		if ( ! file_exists( $dir_path ) ) {
			return null;
		}

		return array_diff( scandir( $dir_path ), [ '..', '.' ] );
	}

	/**
	 * Check if we're on the style guide.
	 *
	 * @return bool
	 */
	private static function is_style_guide(): bool {
		return false !== get_query_var( self::$slug, false );
	}

	/**
	 * Get section template part.
	 *
	 * @param array $section_data
	 * @return void|false
	 */
	public static function get_section_template_part( array $section_data ) {
		get_template_part( trailingslashit( self::$directory_name ) . trailingslashit( self::$sections_directory_name ) . trailingslashit( $section_data['group_id'] ) . $section_data ['file_name'], '', $section_data );
	}

	/**
	 * Set sections.
	 *
	 * @throws Exception
	 * @return void
	 */
	public static function set_sections(): void {
		if ( self::is_style_guide() && ! self::$sections ) {
			$groups = self::get_dir_data( self::$sections_path );
			$data   = [];

			if ( $groups ) {
				foreach ( $groups as $group ) :
					$group_dir_path = trailingslashit( self::$sections_path . $group );

					if ( is_dir( $group_dir_path ) ) {
						$sections = self::get_dir_data( $group_dir_path );

						if ( $sections ) {
							$headers = [];

							foreach ( $sections as $section ) :
								$section_headers             = self::get_section_header( $group_dir_path . $section );
								$section_headers['group_id'] = $group;
								if ( isset( $headers[ $section_headers['id'] ] ) ) {
									/* translators: %s is the section ID. */
									throw new Exception( sprintf( esc_html__( 'A section with ID "%s" already exists', 'playground' ), esc_html( $section_headers['id'] ) ) );
								} else {
									$headers[ $section_headers['id'] ] = $section_headers;
								}
							endforeach;

							// Get headers with order so we can sort them by order.
							$headers_width_order = array_filter(
								$headers,
								function( $value ) {
									return $value['order'] > 0;
								}
							);

							// Sort $headers_width_order.
							uasort(
								$headers_width_order,
								function( $a, $b ) {
									return $a['order'] <=> $b['order'];
								}
							);

							// Merge header arrays and add them $data.
							$data[ $group ] = array_merge( $headers_width_order, $headers );
						}
					}
				endforeach;

				self::$sections = $data;
			}
		}
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public static function get_sections(): array {
		return self::$sections;
	}

	/**
	 * Get section header.
	 *
	 * @param string $file_path
	 * @return array|null
	 */
	public static function get_section_header( string $file_path ): ?array {
		if ( ! file_exists( $file_path ) ) {
			return null;
		}

		// Pull only the first 8 KB of the file in.
		$file = file_get_contents( $file_path, false, null, 0, 8 * KB_IN_BYTES ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		// Make sure we catch CR-only line endings.
		$file = str_replace( "\r", "\n", $file );

		// Get file name.
		$file_name = basename( $file_path );

		// Set file header names.
		$file_headers = [
			'title' => 'Title',
			'id'    => 'ID',
			'order' => 'Order',
		];

		// Loop over $file_headers and try to find them in the file in the PHP comment.
		foreach ( $file_headers as $field => $regex ) :
			if ( preg_match( '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file, $match ) && $match[1] ) {
				$file_headers[ $field ] = _cleanup_header_comment( $match[1] );
			} else {
				$file_headers[ $field ] = '';
			}
		endforeach;

		// Add file name to $file_headers.
		$file_headers['file_name'] = str_replace( '.php', '', $file_name );

		// If we don't have the title make one out of the file name.
		if ( ! $file_headers['title'] ) {
			$file_headers['title'] = ucfirst( str_replace( [ '.php', '-' ], [ '', ' ' ], $file_name ) );
		}

		// Clean up the $file_headers.
		array_walk(
			$file_headers,
			function( &$value, $key ) use ( $file_headers ) {
				switch ( $key ) {
					case 'order':
						$value = absint( $value );
						break;
					case 'id':
						$value = str_replace( '_', '-', sanitize_title( empty( $value ) ? $file_headers['title'] : $value ) );
						break;
				}
			}
		);

		return $file_headers;
	}

	/**
	 * Add endpoint.
	 *
	 * @return void
	 */
	public static function add_rewrite_endpoint(): void {
		add_rewrite_endpoint( self::$slug, EP_ROOT );
	}

	/**
	 * Load base file
	 *
	 * @param array $templates
	 * @return array
	 */
	public static function load_base_file( $templates ) {
		if ( false !== get_query_var( self::$slug, false ) ) {
			array_unshift( $templates, 'base-style-guide.php' );
		}

		return $templates;
	}

	/**
	 * Set SCSS variables data.
	 *
	 * @return void
	 */
	private static function set_scss_variables_data(): void {
		self::$scss_variables = Pg_Assets::get_json( Pg_Assets::get_asset_path( 'style-guide/scss-variables.json' ) );
	}

	/**
	 * Get SCSS variables.
	 *
	 * @return array
	 */
	public static function get_scss_variables(): array {
		return self::$scss_variables;
	}

	/**
	 * Get SCSS variable.
	 *
	 * @param string $name
	 * @param mixed $default_value
	 * @return mixed
	 */
	public static function get_scss_variable( string $name, mixed $default_value = null ): mixed {
		return isset( self::$scss_variables[ $name ] ) ? self::$scss_variables[ $name ] : $default_value;
	}

	/**
	 * Format SCSS number.
	 *
	 * @param string $number
	 * @param string $unit
	 * @return string
	 */
	public static function format_scss_number( string $number, string $unit = '' ): string {
		if ( $unit ) {
			$number = str_replace( $unit, '', $number );
		}

		return floatval( $number ) . $unit;
	}

	/**
	 * Get placeholder URL from Lorem Picsum.
	 *
	 * @param int $width
	 * @param int $height
	 * @param int $photo_id
	 * @return string
	 */
	public static function get_placeholder_url( int $width, int $height, int $photo_id = 0 ): string {
		return sprintf(
			'https://picsum.photos/%s/%s.jpg?image=%s',
			intval( $width ),
			intval( $height ),
			intval( $photo_id > 0 ? $photo_id : wp_rand( 0, 100 ) ),
		);
	}

	/**
	 * Get placeholder image.
	 *
	 * @param int $width
	 * @param int $height
	 * @param array $attributes
	 * @param int $photo_id
	 * @return string
	 */
	public static function get_placeholder_img( int $width, int $height, array $attributes = [], int $photo_id = 0 ): string {
		$default_attributes = [
			'src'    => self::get_placeholder_url( $width, $height, $photo_id ),
			'width'  => $width,
			'height' => $height,
		];

		return Pg_Assets::get_img_html( wp_parse_args( $attributes, $default_attributes ) );
	}

	/**
	 * Get test data.
	 *
	 * @param string $json_name
	 * @return array
	 */
	public static function get_test_data( string $json_name ): array {
		return Pg_Assets::get_json( self::$test_data_directory_path . $json_name . '.json' );
	}

	/**
	 * Add front end assets.
	 *
	 * @return void
	 */
	public static function enqueue_assets(): void {
		// Style guide JavaScript.
		if ( Pg_Assets::asset_exists( 'style-guide/style-guide.js' ) && self::is_style_guide() ) {
			wp_enqueue_script( 'pg-style-guide', Pg_Assets::get_asset_uri( 'style-guide/style-guide.js' ), [], Pg_Assets::get_asset_version( 'style-guide/style-guide.js' ), true );
		}

		// Style guide CSS.
		if ( Pg_Assets::asset_exists( 'style-guide/style-guide.css' ) && self::is_style_guide() ) {
			wp_enqueue_style( 'pg-style-guide', Pg_Assets::get_asset_uri( 'style-guide/style-guide.css' ), [], Pg_Assets::get_asset_version( 'style-guide/style-guide.css' ) );
		}
	}

	/**
	 * Hide admin bar on style guide.
	 *
	 * @param bool $show_admin_bar
	 * @return bool
	 */
	public static function hide_admin_bar( $show_admin_bar ) {
		if ( self::is_style_guide() ) {
			$show_admin_bar = false;
		}

		return $show_admin_bar;
	}
}

Pg_Style_Guide::init();
