<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<div <?php post_class(); ?>>
		<h1><?php the_title(); ?></h1>

		<div class="editor">
			<?php the_content(); ?>
		</div>
	</div>
<?php endwhile; ?>
