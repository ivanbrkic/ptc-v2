<?php
/**
 * Autologin.
 */

/**
 * Programmatic login.
 *
 * @param string $username
 * @return bool
 */
function pg_programmatic_login( $username ) {
	if ( is_user_logged_in() ) {
		wp_logout();
	}

	add_filter( 'authenticate', 'pg_allow_programmatic_login', 10, 3 );

	$user = wp_signon(
		[
			'user_login'    => $username,
			'user_password' => '',
		]
	);

	remove_filter( 'authenticate', 'pg_allow_programmatic_login', 10, 3 );

	if ( is_a( $user, 'WP_User' ) ) {
		wp_set_current_user( $user->ID, $user->user_login );
		if ( is_user_logged_in() ) {
			return true;
		}
	}
	return false;
}

/**
 * Allow programatic login.
 *
 * @param null|WP_User|WP_Error $user
 * @param string $username
 * @return WP_User|false
 */
function pg_allow_programmatic_login( $user, $username ) {
	return get_user_by( 'login', $username );
}

/**
 * Check if the autologin action is legal.
 *
 * @return void
 */
add_action(
	'init',
	function() {
		if ( defined( 'WP_ENV' ) && WP_ENV === 'development' ) {
			// We don't need a nonce for this since this is a dev tool only used in development so we're disabling that PHP_CodeSniffer rule.
			if ( isset( $_REQUEST['logme'] ) && ! is_user_logged_in() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$first_created_admin = get_users(
					[
						'role'    => 'Administrator',
						'orderby' => 'ID',
						'number'  => 1,
					]
				);
				if ( $first_created_admin && is_array( $first_created_admin ) ) {
					pg_programmatic_login( $first_created_admin[0]->data->user_login );
				}
			}
			if ( ! is_user_logged_in() ) {
				add_action(
					'wp_footer',
					function() {
						?>
						<a href="?logme" style="position: fixed; top: 10px; left: 10px; z-index: 10000000; display: block;">
							<img style="display: block;" src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAf/AABEIABQAFAMBEQACEQEDEQH/xAGiAAABBQEBAQEBAQAAAAAAAAAAAQIDBAUGBwgJCgsQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+gEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoLEQACAQIEBAMEBwUEBAABAncAAQIDEQQFITEGEkFRB2FxEyIygQgUQpGhscEJIzNS8BVictEKFiQ04SXxFxgZGiYnKCkqNTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqCg4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2dri4+Tl5ufo6ery8/T19vf4+fr/2gAMAwEAAhEDEQA/AP4dP2fP2Zvj1+1Z4z1f4d/s6/DDxL8XPHWheCvEnxE1Lwp4SitbrXE8HeEIrefxFrFrYXF1bTal/Z0d3bE6fpq3eqXTTJHZWNzISgAMn4bfAP4wfF//AIWevw38C6t4pn+DHgDxD8U/idZWkun22peEfh/4SvLGw8UeJ77TNRvbLULiw8PXOpWY1lNOtry602GVrq7t4rWGeaMA8goA/bP/AIIMftYfC79jX9rz4vfF34pfFC3+EEF1+xZ+014K8A+M5o9UeSD4reIfDGmS/D6wsH0iy1C7t9Uvtb02NNNuTbi3hvUhe5mgi3SqAfeXwP8A+Cm37C3x/wDgx+218aP2kvDOnfs4f8FLPHP7BX7QP7P+veO/h5oX9nfA39uLUvH+m6QdD8YeI/BGgaXJafDD9pAahoem2/iTUtHSx8FfENrrWfEd0NMvriy0XQAD+VqgAoAKACgD/9k=">
						</a>
						<?php
					}
				);
			}
		}
	}
);
