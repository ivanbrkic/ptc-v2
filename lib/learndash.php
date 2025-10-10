<?php

add_filter(
	'learndash_focus_header_element',
	function () {
		return get_template_part( 'templates/branding' );
	}
);

add_action('wp_enqueue_scripts', function () {
  wp_dequeue_style('learndash-front');
}, 1000);

add_action('learndash-focus-content-title-before', function () {
	echo '<div class="the-content">';
}, 1000);

add_action('learndash-focus-content-end', function () {
	echo '</div>';
}, 1000);
