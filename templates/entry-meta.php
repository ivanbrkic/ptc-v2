<time datetime="<?php echo esc_attr( get_post_time( 'c', true ) ); ?>">
	<?php echo get_the_date(); ?>
</time>
<p>
	<?php esc_html_e( 'Author', 'playground' ); ?>:
	<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
		<?php echo get_the_author(); ?>
	</a>
</p>
