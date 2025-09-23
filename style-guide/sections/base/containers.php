<?php
/**
 * Title: Containers
 */
?>

<?php
$breakpoints          = Pg_Style_Guide::get_scss_variable( 'grid-breakpoints', [] );
$container_max_widths = [ 'xs' => '100%' ] + Pg_Style_Guide::get_scss_variable( 'container-max-widths', [] );
?>

<p>Our containers are based on <a href="https://getbootstrap.com/docs/5.3/layout/containers/">Bootstrap 5.3</a> with some minor differences.</p>

<p>Containers are a basic layout element that aligns the content, adds right and left padding and based on the type of container and your settings adds max width.</p>

<p>By default containers have right and left padding set to half of <code>$grid-gutter-width</code> but some projects require containers to change that padding on some breakpoints. If you need to change container padding keep in mind that the value shouldn't be less then <code>$grid-gutter-width</code>.</p>

<p>There are 3 types of containers:</p>

<ul>
	<li><code>.container</code> - has a <code>max-width</code> at each of the breakpoints defined in <code>$container-max-widths</code>.</li>
	<li><code>.container-fluid</code> has a width of 100% on each breakpoint.</li>
	<li><code>.container-{breakpoint}</code> - behaves as <code>.container-fluid</code> up to the specific breakpoint when it get's a <code>max-width</code> as defined in <code>$container-max-widths</code>.</li>
</ul>

<p>The table below explains in detail <code>max-width</code> for each of the mentioned container types.</p>

<table class="style-guide-table">
	<thead>
		<tr>
			<th>Container class</th>
			<?php foreach ( $container_max_widths as $key => $value ) : ?>
				<?php
				$breakpoint_width = $breakpoints[ $key ] ?? 0;
				$start_width      = $breakpoint_width;
				$compare_sign     = '≥';

				if ( 0 === $breakpoint_width ) {
					$start_width  = next( $breakpoints );
					$compare_sign = '<';
				}
				?>
				<th>

					<?php echo esc_html( strtoupper( $key ) ); ?> (<?php echo esc_html( $compare_sign . ' ' . $start_width ); ?>)
				</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><code>.container</code></td>
			<?php foreach ( $container_max_widths as $container_max_width ) : ?>
				<td><?php echo esc_html( $container_max_width ); ?></td>
			<?php endforeach; ?>
		</tr>
		<?php foreach ( $container_max_widths as $container_breakpoint => $container_width ) : ?>
			<?php if ( 'xs' === $container_breakpoint ) continue; // phpcs:ignore Generic.ControlStructures.InlineControlStructure.NotAllowed ?>
			<tr>
				<td><code>.container-<?php echo esc_html( $container_breakpoint ); ?></code></td>
				<?php $has_width = false; ?>
				<?php foreach ( $container_max_widths as $breakpoint_name => $container_max_width ) : ?>
					<?php
					if ( $container_breakpoint === $breakpoint_name ) {
						$has_width = true;
					}
					?>
					<td><?php echo esc_html( $has_width ? $container_max_width : '100%' ); ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		<tr>
			<td><code>.container-fluid</code></td>
			<?php foreach ( $container_max_widths as $container_max_width ) : ?>
				<td>100%</td>
			<?php endforeach; ?>
		</tr>
	</tbody>
</table>

<p>Modify the <code>$container-max-widths</code> variable based on your project needs.</p>

<p>You can also use the <code>make-container()</code> mixin to apply container style to other elements.</p>
