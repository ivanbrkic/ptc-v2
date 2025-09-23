<?php
/**
 * Title: Icons
 */
?>

<table class="style-guide-table">
	<tbody>
		<tr>
			<th>Icon</th>
			<th>Icon name</th>
		</tr>
		<?php foreach ( Pg_Assets::get_icon_list() as $icon ) : ?>
			<tr>
				<td>
					<?php echo Pg_Assets::get_icon( $icon ); ?>
				</td>
				<td><code><?php echo esc_html( $icon ); ?></code></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
