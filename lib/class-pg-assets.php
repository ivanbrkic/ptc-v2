<?php
/**
 * Pg_Assets helper class.
 */

/**
 * Pg_Assets class.
 */
final class Pg_Assets {
	/**
	 * Theme template directory absolute server path.
	 *
	 * @var string
	 */
	private static $assets_directory;

	/**
	 * Theme template directory URI.
	 *
	 * @var string
	 */
	private static $assets_directory_uri;

	/**
	 * Path of the dist directory relative to theme root directory.
	 *
	 * @var string
	 */
	private static $assets_dist_dir_path = 'assets/dist';

	/**
	 * Path to the SVG sprite relative to self::$assets_dist_dir_path.
	 *
	 * @var string
	 */
	private static $sprite_path = 'theme/sprite-icons/sprite-icons.svg';

	/**
	 * Path to the images directory relative to self::$assets_dist_dir_path.
	 *
	 * @var string
	 */
	private static $theme_img_dir_path = 'theme/img';

	/**
	 * Laravel Mix manifest data.
	 *
	 * @var array
	 */
	private static $mix_manifest_data = [];

	/**
	 * List of all icons.
	 *
	 * @var array
	 */
	private static $icon_list = [];

	/**
	 * Init the class.
	 *
	 * @return void
	 */
	public static function init(): void {
		self::set_assets_paths();
		self::set_mix_manifest_data();
		self::set_icon_list();
	}

	/**
	 * Set assets paths.
	 *
	 * @return void
	 */
	private static function set_assets_paths(): void {
		self::$assets_directory     = trailingslashit( get_template_directory() ) . trailingslashit( self::$assets_dist_dir_path );
		self::$assets_directory_uri = trailingslashit( get_template_directory_uri() ) . trailingslashit( self::$assets_dist_dir_path );
	}

	/**
	 * Get assets directory.
	 *
	 * @return string
	 */
	public static function get_assets_directory(): string {
		return self::$assets_directory;
	}

	/**
	 * Get assets directory URI.
	 *
	 * @return string
	 */
	public static function get_assets_directory_uri(): string {
		return self::$assets_directory_uri;
	}

	/**
	 * Get asset version.
	 *
	 * @param string $asset_key Asset key in $mix_manifest_data array.
	 * @return string|null
	 */
	public static function get_asset_version( string $asset_key ): ?string {
		$mix_manifest_data = self::get_mix_manifest_data();

		// Add slash to $asset_key since Mix add's it to the mix-manifest.json.
		$asset_key = '/' . $asset_key;

		$asset_data = ! empty( $mix_manifest_data[ $asset_key ] ) ? $mix_manifest_data[ $asset_key ] : '';

		if ( strpos( $asset_data, $asset_key . '?id=' ) === false ) {
			return null;
		}

		return str_replace( $asset_key . '?id=', '', $asset_data );
	}

	/**
	 * Get asset URI.
	 *
	 * @param string $asset_path Path relative to self::$assets_directory_uri.
	 * @return string
	 */
	public static function get_asset_uri( string $asset_path ): string {
		return self::get_assets_directory_uri() . $asset_path;
	}

	/**
	 * Get asset server path.
	 *
	 * @param string $asset_path Path relative to self::$assets_directory.
	 * @return string
	 */
	public static function get_asset_path( string $asset_path ): string {
		return trailingslashit( self::get_assets_directory() ) . $asset_path;
	}

	/**
	 * Check if asset exists.
	 *
	 * @param string $asset_path Asset path relative to self::$assets_directory.
	 * @return bool
	 */
	public static function asset_exists( string $asset_path ): bool {
		return file_exists( self::get_asset_path( $asset_path ) );
	}

	/**
	 * Read JSON.
	 *
	 * @param string $json_path Path to the JSON file.
	 * @return array|null
	 */
	private static function read_json( string $json_path ): ?array {
		// We have to use file_get_contents() in order to read the JSON so we're disabling PHP_CodeSniffer rule.
		return json_decode( file_get_contents( $json_path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	/**
	 * Get JSON.
	 *
	 * @param string $json_path Path to the JSON file.
	 * @return array
	 */
	public static function get_json( string $json_path ): array {
		if ( ! file_exists( $json_path ) ) {
			return [];
		}

		$json_data = self::read_json( $json_path );

		return $json_data ? $json_data : [];
	}

	/**
	 * Set Laravel Mix manifest data.
	 *
	 * @return void
	 */
	private static function set_mix_manifest_data(): void {
		self::$mix_manifest_data = self::get_json( self::get_assets_directory() . 'mix-manifest.json' );
	}

	/**
	 * Get Laravel Mix manifest data.
	 *
	 * @return array
	 */
	private static function get_mix_manifest_data(): array {
		return self::$mix_manifest_data;
	}

	/**
	 * Get SVG.
	 *
	 * @param string $svg_path SVG path relative to self::$assets_directory.
	 * @param bool $append_extension Add ".svg" extension to the provided file path.
	 * @return string SVG HTML as a string or empty string if the file doesn't exist.
	 */
	public static function get_svg( string $svg_path, bool $append_extension = true ): string {
		if ( $append_extension ) {
			$svg_path = $svg_path . '.svg';
		}
		return self::asset_exists( $svg_path ) ? file_get_contents( self::get_asset_path( $svg_path ) ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	/**
	 * Get SVG as XML.
	 *
	 * @param string $svg_path SVG path relative to self::$assets_directory.
	 * @return SimpleXMLElement|null
	 */
	public static function get_svg_as_xml( string $svg_path ): ?SimpleXMLElement {
		$xml = simplexml_load_file( $svg_path );

		return $xml ? $xml : null;
	}

	/**
	 * Get image size attributes.
	 *
	 * @param string $img_path Image path relative to self::$assets_directory.
	 * @return array Array with width and height or empty array on failure.
	 */
	public static function get_img_size_attr( string $img_path ): array {
		// Set the defaults.
		$size = [
			'width'  => null,
			'height' => null,
		];

		// Check the file extension.
		$file_info = pathinfo( $img_path );

		// Exit if we don't have an extension.
		if ( empty( $file_info['extension'] ) ) {
			return [];
		}

		if ( 'svg' === $file_info['extension'] ) {
			// Get SVG as XML.
			$svg_as_xml     = self::get_svg_as_xml( $img_path );
			$svg_attributes = $svg_as_xml ? $svg_as_xml->attributes() : [];
			// Try to get the with from with and height on the SVG.
			$size['width']  = ! empty( $svg_attributes['width'] ) ? $svg_attributes['width'] : false;
			$size['height'] = ! empty( $svg_attributes['height'] ) ? $svg_attributes['height'] : false;

			// If we don't have any of the values try to get them from the viewBox.
			if ( ( empty( $size['width'] ) || empty( $size['height'] ) ) && ! empty( $svg_attributes['viewBox'] ) ) {
				// Reset the size back to start value so we don't get some values from the viewBox and some from width/height.
				$size['width']  = null;
				$size['height'] = null;
				// Convert the viewBox to an array.
				$svg_viewbox = explode( ' ', $svg_attributes['viewBox'] );

				// If the viewBox is valid get the last two values (width and height).
				if ( 4 === count( $svg_viewbox ) ) {
					$size['width']  = $svg_viewbox[2];
					$size['height'] = $svg_viewbox[3];
				}
			}
		} else {
			// Return only if we have both values.
			$img_size       = getimagesize( $img_path );
			$size['width']  = $img_size[0];
			$size['height'] = $img_size[1];
		}

		// Sanitize the values.
		$size = array_filter( array_map( 'absint', $size ) );

		// Return only if we have both values.
		return 2 === count( $size ) ? $size : [];
	}

	/**
	 * Render the img HTML tag.
	 *
	 * @param array $args Array of arguments.
	 * @return string HTML ofr img tag.
	 */
	public static function get_img_html( array $args = [] ): string {
		$defaults = [
			'src'      => null,
			'class'    => null,
			'width'    => null,
			'height'   => null,
			'alt'      => null,
			'loading'  => 'lazy',
			'decoding' => 'async',
		];

		// Merge attr and default settings.
		$attr = wp_parse_args( $args, $defaults );

		// Escape the attributes.
		array_walk(
			$attr,
			function( &$value, $key ) {
				// Sanitize attr values.
				switch ( $key ) {
					case 'src':
						$value = esc_url( $value );
						break;
					case 'width':
					case 'height':
						$value = absint( $value );
						break;
					default:
						$value = esc_attr( $value );
						break;
				}
			}
		);

		// Remove empty values from the array.
		$attr = array_filter( $attr );

		// If we didn't have an alt attribute or if it was removed with escaping add an empty alt for SEO.
		if ( empty( $attr['alt'] ) ) {
			$attr['alt'] = null;
		}

		// Unset "loading" attribute if it's set to "eager" since that's the default.
		if ( isset( $attr['loading'] ) && 'eager' === $attr['loading'] ) {
			unset( $attr['loading'] );
		}

		// Unset "decoding" attribute if it's set to "auto" since that's the default.
		if ( isset( $attr['decoding'] ) && 'auto' === $attr['decoding'] ) {
			unset( $attr['decoding'] );
		}

		array_walk(
			$attr,
			function( &$value, $key ) {
				// Add the key as attribute name and sanitize it.
				$value = sprintf( '%s="%s"', esc_attr( $key ), $value );
			}
		);

		// Build the img tag.
		return sprintf( '<img %s>', join( ' ', $attr ) );
	}

	/**
	 * Get image.
	 *
	 * @param string $img_path Image path relative to self::$assets_directory.
	 * @param array $img_attr Additional img attributes.
	 * @return string Image HTML as a string or empty string if the file doesn't exist.
	 */
	public static function get_img( string $img_path, array $img_attr = [] ): string {
		// If the file doesn't exist exit.
		if ( ! self::asset_exists( $img_path ) ) {
			return '';
		}

		// Get size attributes.
		$attr = self::get_img_size_attr( self::get_asset_path( $img_path ) );

		// Get the src attribute.
		$attr['src'] = self::get_asset_uri( $img_path );

		// Combine $img_attr with $attr from the method args.
		$attr = wp_parse_args( $img_attr, $attr );

		// Return the img.
		return self::get_img_html( $attr );
	}

	/**
	 * Get sprite.
	 *
	 * @return string
	 */
	public static function get_sprite(): string {
		return self::get_svg( self::$sprite_path, false );
	}

	/**
	 * Set icon list.
	 *
	 * @return void
	 */
	private static function set_icon_list(): void {
		if ( self::asset_exists( self::$sprite_path ) ) {
			// Get SVG sprite as XML and populate the self::$icon_list.
			$sprite_xml = self::get_svg_as_xml( self::get_asset_path( self::$sprite_path ) );
			// Get all symbol elements and add their names to self::$icon_list.
			if ( $sprite_xml && isset( $sprite_xml->defs->symbol ) ) {
				foreach ( $sprite_xml->defs->symbol as $val ) :
					if ( ! empty( $val->attributes()['id'] ) ) {
						self::$icon_list[] = str_replace( 'sprite-icon-', '', $val->attributes()['id'] );
					}
				endforeach;
			}
		}
	}

	/**
	 * Get icon list.
	 *
	 * @return array
	 */
	public static function get_icon_list(): array {
		return self::$icon_list;
	}

	/**
	 * Check if icon exists.
	 *
	 * @param string $name Icon name.
	 * @return bool
	 */
	private static function icon_exist( string $name ): bool {
		return in_array( $name, self::get_icon_list(), true );
	}

	/**
	 * Get icon from SVG sprite.
	 *
	 * @param string $name Icon name.
	 * @param string $size Icon size (generates a modifier class so if you add "lg" the icon will have "icon--lg" class).
	 * @param array $attr Additional HTML attributes.
	 * @return string
	 */
	public static function get_icon( string $name, string $size = '', array $attr = [] ): string {
		// Default attributes.
		$icon_attr = [
			'aria-hidden' => 'true',
		];
		// Merge incoming attributes with defaults.
		$attr = wp_parse_args( $attr, $icon_attr );

		// If the icon doesn't exist exit.
		if ( ! self::icon_exist( $name ) ) {
			return '';
		}

		// Create classes array.
		$icon_classes = [ 'icon', 'icon--' . $name ];
		if ( $size ) {
			$icon_classes[] = 'icon--' . $size;
		}

		// Add incoming classes if they are set in $attr.
		if ( ! empty( $attr['class'] ) ) {
			$icon_classes[] = $attr['class'];
			unset( $attr['class'] );
		}

		// Sanitize attributes and create attribute name and value.
		array_walk(
			$attr,
			function( &$value, $key ) {
				$value = sprintf( '%s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}
		);

		// Combine all the attributes in a string.
		$attr_string = join( ' ', $attr );

		// Return SVG HTML.
		return sprintf(
			'<svg %1$s class="%2$s">
				<use href="#%3$s"></use>
			</svg>',
			$attr_string, // HTML attributes.
			esc_attr( join( ' ', $icon_classes ) ), // Icon class.
			'sprite-icon-' . esc_attr( $name ) // Icon ID from the sprite.
		);
	}

	/**
	 * Get theme image. Get's the image from self::$theme_img_dir_path.
	 *
	 * @param string $img_path Image path relative to self::$theme_img_dir_path.
	 * @param array $img_attr Additional img attributes.
	 * @return string Image HTML as a string or empty string if the file doesn't exist.
	 */
	public static function get_theme_img( string $img_path, array $img_attr = [] ): string {
		// Return the img.
		return self::get_img( trailingslashit( self::$theme_img_dir_path ) . $img_path, $img_attr );
	}

	/**
	 * Get theme SVG. Get's the SVG from self::$theme_img_dir_path.
	 *
	 * @param string $svg_path SVG path relative to self::$theme_img_dir_path.
	 * @param bool $append_extension Add ".svg" extension to the provided file path.
	 * @return string SVG HTML as a string or empty string if the file doesn't exist.
	 */
	public static function get_theme_svg( string $svg_path, bool $append_extension = true ): string {
		return self::get_svg( trailingslashit( self::$theme_img_dir_path ) . $svg_path, $append_extension );
	}
}

/**
 * Init Pg_Assets helper class.
 */
Pg_Assets::init();
