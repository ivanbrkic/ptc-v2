<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'playground' ); ?></span>
		<input type="search" placeholder="<?php echo esc_attr_x( 'Search', 'search input placeholder', 'playground' ); ?> &hellip;" value="<?php echo get_search_query(); ?>" name="s">
	</label>
	<button type="submit"><?php echo esc_html_x( 'Search', 'submit button', 'playground' ); ?></button>
</form>
