<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<article <?php post_class(); ?>>
		<header>
			<h1><?php the_title(); ?></h1>
			<?php get_template_part( 'templates/entry-meta' ); ?>
		</header>

		<div class="editor">
			<?php the_content(); ?>
		</div>
	</article>
<?php endwhile; ?>
