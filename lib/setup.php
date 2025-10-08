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
		load_theme_textdomain( 'ptc', get_template_directory() . '/languages' );

		// Theme supports.
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ] );

		// Register menus.
		register_nav_menus(
			[
				'header_navigation' => __( 'Header Navigation', 'ptc' ),
				'footer_menu' => __( 'Footer Navigation', 'ptc' ),
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


/**
 * Server Side Tracking by Taggrs.io
 */

if ( ! defined( 'WP_ENV' ) || ( defined( 'WP_ENV' && WP_ENV !== 'development' ) ) ) {
	// GTM
	add_action(
		'wp_head',
		function() {
			?>
			<!-- Server Side Tracking by Taggrs.io -->
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://sst.mennohenselmans.com/UXbXLdRo9t.js?tg='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','WJ7TLXV');</script>
			<!-- End Server Side Tracking by Taggrs.io -->
			<?php
		}
	);

	add_action(
		'wp_body_open',
		function() {
			?>
			<!-- Server Side Tracking by Taggrs.io (noscript) -->
			<noscript><iframe src="https://sst.mennohenselmans.com/UXbXLdRo9t.html?tg=WJ7TLXV" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Server Side Tracking by Taggrs.io (noscript) -->
			<?php
		}
	);
}

// Wrap HTML blocks with a wrapper
function wrap_blocks( $block_content, $block ) {
	if ( $block['blockName'] === 'core/html' ) {
		if ( str_contains( $block_content, '<iframe' ) ) {
			$content  = '<div class="wp-iframe-wrap">';
			$content .= $block_content;
			$content .= '</div>';
			return $content;
		}
	}

	return $block_content;
}

add_filter( 'render_block', 'wrap_blocks', 10, 2 );

add_action(
	'learndash-focus-header-usermenu-after',
	function() {
		if ( shortcode_exists( 'wpdiscuz_bell' ) ) {
			echo do_shortcode( '[wpdiscuz_bell]' );
		}
	}
);

/**
* Redirect (homepage or LD page) to login if not logged in
* if only one course, redirect there.
* Also set a cookie for after logging in.
*/


add_action(
	'template_redirect',
	function () {
		if ( ! is_user_logged_in() ) {
			if ( is_front_page() ) {
				wp_safe_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
				exit;
			}
			if ( function_exists( 'learndash_get_post_type_slug' ) ) {
				$learndash_post_types = [
					learndash_get_post_type_slug( 'course' ),
					learndash_get_post_type_slug( 'lesson' ),
					learndash_get_post_type_slug( 'topic' ),
					learndash_get_post_type_slug( 'quiz' ),
					learndash_get_post_type_slug( 'group' ),
					learndash_get_post_type_slug( 'essay' ),
					learndash_get_post_type_slug( 'certificate' ),
				];

				$post_type = get_post_type();

				if ( in_array( $post_type, $learndash_post_types ) ) {
					$current_url = esc_url_raw(
						( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
					);
					$login_url   = add_query_arg( 'ld_redirect', urlencode( $current_url ), get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );

					wp_safe_redirect( $login_url );
					exit;
				} else {
					// error_log( 'DEBUG: Post type not matched as LearnDash type.' );
				}
			}
		}
		if ( is_user_logged_in() && is_front_page() ) {
			$courses = learndash_user_get_enrolled_courses( get_current_user_id() );
			if ( count( $courses ) === 1 ) {
				wp_safe_redirect( learndash_get_course_url( $courses[0] ) );
				exit;
			}
		}
	}
);

// Handle the redirect after login using WooCommerce filter
add_filter(
	'woocommerce_login_redirect',
	function ( $redirect, $user ) {
		if ( ! empty( $_GET['ld_redirect'] ) ) {
			$original_url = esc_url_raw( $_GET['ld_redirect'] ); // phpcs:ignore
			// Store as a transient for 5 minutes
			set_transient( 'ld_login_redirect_' . $user->ID, $original_url, 5 * MINUTE_IN_SECONDS );
		}
		// Still go to My Account for now — actual redirect will happen after login
		return get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
	},
	10,
	2
);

// Step 3: Redirect after login using init (or wherever appropriate)
add_action(
	'template_redirect',
	function () {
		if ( is_user_logged_in() && is_page( get_option( 'woocommerce_myaccount_page_id' ) ) ) {
			$user_id  = get_current_user_id();
			$redirect = get_transient( 'ld_login_redirect_' . $user_id );

			if ( ! empty( $redirect ) ) {
				delete_transient( 'ld_login_redirect_' . $user_id );
				wp_safe_redirect( $redirect );
				exit;
			}
		}
	}
);

// whitelist my cookie in Complianz
add_filter(
	'cmplz_known_cookie_names',
	function( $cookie_names ) {
		$cookie_names[] = 'ld_redirect_after_login';
		return $cookie_names;
	}
);


add_action(
	'init',
	function() {

		add_shortcode(
			'ptc_wrap',
			function( $atts, $content ) {
				extract(
					shortcode_atts(
						[
							'href' => 'http://',
						],
						$atts
					)
				);

				return '<div class="ptc-wrap">' . do_shortcode( $content ) . '</div>';
			}
		);
	}
);

// center login form
add_action(
	'woocommerce_before_customer_login_form',
	function () {
		echo '<div class="login-form u-mw-646 text-center mx-auto">';
	}
);

add_action(
	'woocommerce_after_customer_login_form',
	function () {
		echo '</div>';
	}
);

// add page slug to body class
add_filter(
	'body_class',
	function( $classes ) {
		global $post;
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}
		return $classes;
	}
);


add_filter( 'locale', 'set_frontend_locale_based_on_profile' );
function set_frontend_locale_based_on_profile( $locale ) {
	if ( is_user_logged_in() && ! is_wc_endpoint_url( 'order-received' ) ) { // only for logged in users and not for order-received Woocommerce page
		$user_locale = get_user_meta( get_current_user_id(), 'locale', true );
		if ( ! empty( $user_locale ) ) {
			return $user_locale;
		}
	}
	return $locale; // Default locale
}


// add admin columns for release date of lessons
add_filter( 'manage_sfwd-lessons_posts_columns', 'ld_add_release_date_column' );
function ld_add_release_date_column( $columns ) {
	$columns['release_date'] = 'Release Date';
	return $columns;
}

add_action( 'manage_sfwd-lessons_posts_custom_column', 'ld_show_release_date_column', 10, 2 );
function ld_show_release_date_column( $column, $post_id ) {
	if ( $column === 'release_date' ) {
		$lesson_meta = get_post_meta( $post_id, '_sfwd-lessons', true );

		if ( isset( $lesson_meta['sfwd-lessons_visible_after_specific_date'] ) && ! empty( $lesson_meta['sfwd-lessons_visible_after_specific_date'] ) ) {
			$timestamp = intval( $lesson_meta['sfwd-lessons_visible_after_specific_date'] );
			echo date_i18n( get_option( 'date_format' ), $timestamp );
		} else {
			echo '—';
		}
	}
}

add_filter( 'gform_pre_render_1', 'populate_post_id' );
add_filter( 'gform_pre_validation_1', 'populate_post_id' );
add_filter( 'gform_pre_submission_filter_1', 'populate_post_id' );

function populate_post_id( $form ) {
	if ( ! is_admin() && is_singular( 'sfwd-lessons' ) ) {
		foreach ( $form['fields'] as &$field ) {
			if ( $field->id == 15 ) {
					$field->defaultValue = get_the_ID(); // phpcs:ignore
			}
			if ( $field->id == 16 ) {
				$field->defaultValue = get_the_title(); // phpcs:ignore
			}
			if ( $field->id == 17 ) {
				$field->defaultValue = get_the_permalink(); // phpcs:ignore
			}
			if ( $field->id == 18 ) {
				// get current user email
				$field->defaultValue = wp_get_current_user()->user_email; // phpcs:ignore
			}
		}
	}
	return $form;
}

add_action( 'learndash-lesson-after', 'add_feedback_form', 10, 3 );
function add_feedback_form( $lesson_id, $course_id, $user_id ) {
	if ( get_locale() === 'en_US' ) {
		?>
	<div class="modal micromodal-slide" id="modal-form" aria-hidden="true">
		<div class="modal__overlay" tabindex="-1" data-micromodal-close>
			<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
				<header class="modal__header">
					<button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
				</header>
				<main class="modal__content" id="modal-1-content">
					<h3 class="mb-2"><?php esc_html_e( 'Leave Lesson Feedback', 'ptc' ); ?></h3>
					<?php echo do_shortcode( '[gravityform id="1" title="false" description="false" ajax="true"]' ); ?>
				</main>
				<footer class="modal__footer">
					<button class="button button--secondary" data-micromodal-close aria-label="Close this dialog window"><?php _e( 'Close', 'ptc' ); ?></button>
				</footer>
			</div>
		</div>
	</div>
	<div class="text-right">
		<hr>
		<button class="modal__button" data-micromodal-trigger="modal-form"><?php _e( 'Leave Lesson Feedback', 'ptc' ); ?></button>
	</div>
		<?php
	}
}
