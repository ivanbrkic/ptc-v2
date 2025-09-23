<?php
/**
 * Title: Colors
 */
?>

<?php
// Create color variables so we can loop through them and create examples.
$colors = array_keys( Pg_Style_Guide::get_scss_variable( 'colors', [] ) );
// Define light colors so we can add a black background to them in the example.
$light_colors = [ 'white' ];

if ( ! $colors ) {
	return;
}
?>

<table class="style-guide-table">
	<thead>
		<tr>
			<th>Utility class</th>
			<th>Example</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $colors as $value ) : ?>
			<?php $color_name = 'text-' . $value; ?>
			<tr>
				<td><code>.<?php echo esc_html( $color_name ); ?></code></td>
				<?php
				if ( in_array( $value, $light_colors, true ) ) {
					$color_name .= ' bg-black';
				}
				?>
				<td><p class="m-0 d-inline-block <?php echo esc_attr( $color_name ); ?>">Example</p></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h2 id="utilities-colors-background-color">Background color utilities</h2>

<div class="style-guide-example">
	<div class="row">
		<?php foreach ( $colors as $value ) : ?>
			<div class="col-4 col-md-2 my-2">
				<div class="style-guide-color-box bg-<?php echo esc_attr( $value ); ?>"></div>
				<code>.bg-<?php echo esc_html( $value ); ?></code>
			</div>
		<?php endforeach; ?>
	</div>
</div>
