<?php
/**
 * Pg_Wrapper.
 *
 * Forked version of the theme wrapper used in Sage.
 *
 * @link https://roots.io/docs/sage/8.x/wrapper/#template-hierarchy
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */

/**
 * Pg_Wrapper class.
 */
class Pg_Wrapper {
	/**
	 * Stores the full path to the main template file.
	 *
	 * @var string
	 */
	public static $main_template;

	/**
	 * Basename of template file
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Array of templates.
	 *
	 * @var array
	 */
	public $templates;

	/**
	 * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	 *
	 * @var string
	 */
	public static $base;

	/**
	 * Constructor.
	 *
	 * @param string $template Template name.
	 */
	public function __construct( $template = 'base.php' ) {
		$this->slug      = basename( $template, '.php' );
		$this->templates = [ $template ];

		if ( self::$base ) {
			$str = substr( $template, 0, -4 );
			array_unshift( $this->templates, sprintf( $str . '-%s.php', self::$base ) );
		}
	}

	/**
	 * To string helper.
	 *
	 * @return string
	 */
	public function __toString() {
		$this->templates = apply_filters( 'pg_wrapper_' . $this->slug, $this->templates );

		return locate_template( $this->templates );
	}

	/**
	 * Wrap function.
	 *
	 * @param string $main Template name.
	 * @return Pg_Wrapper
	 */
	public static function wrap( $main ) {
		/** Check for other filters returning null */
		if ( ! is_string( $main ) ) {
			return $main;
		}

		self::$main_template = $main;
		self::$base          = basename( self::$main_template, '.php' );

		if ( self::$base === 'index' ) {
			self::$base = false;
		}

		return new Pg_Wrapper();
	}
}

/**
 * Init our wrapper to filter default WordPress template hierarchy.
 */
add_filter( 'template_include', [ 'Pg_Wrapper', 'wrap' ], 109 );
