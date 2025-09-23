<h1><?php echo pg_get_page_header(); ?></h1>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'templates/content' );
	}
	echo pg_get_pagination();
} else {
	esc_html_e( 'Sorry, no results were found.', 'playground' );
	get_search_form();
}
?>
