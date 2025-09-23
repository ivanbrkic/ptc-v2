<?php
/**
 * Title: Breakpoints
 * Order: 3
 */
?>

<?php $breakpoints = Pg_Style_Guide::get_scss_variable( 'grid-breakpoints', [] ); ?>

<ul>
	<li>Write code to use mobile first approach whenever possible.</li>
	<li>All of the breakpoints we use are defined in <code>$grid-breakpoints</code> variable in <code>abstracts/variables.scss</code>.</li>
	<li>The breakpoints defined in <code>$grid-breakpoints</code> are the basis for our grid, utility classes and media query mixins.</li>
	<li>The breakpoints defined in <code>$grid-breakpoints</code> are set to get you started but modify them according to your project specification and requirements.</li>
</ul>

<?php if ( $breakpoints ) { ?>
	<table class="style-guide-table">
		<thead>
			<tr>
				<th>Breakpoint name</th>
				<th>Class infix</th>
				<th>Dimensions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $breakpoints as $breakpoint_name => $breakpoint_width ) : ?>
				<tr>
					<?php
					$start_width  = $breakpoint_width;
					$compare_sign = '≥';
					if ( 0 === $breakpoint_width ) {
						$start_width  = next( $breakpoints );
						$compare_sign = '<';
					}
					?>
					<td><?php echo esc_html( strtoupper( $breakpoint_name ) ); ?></td>
					<td><?php echo 0 === $breakpoint_width ? 'None' : '<code>' . esc_html( $breakpoint_name ) . '</code>'; ?></td>
					<td><?php echo esc_html( $compare_sign . ' ' . $start_width ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php } ?>

<h2 id="base-breakpoints-mixins">Mixins</h2>

<p><strong>Always use Sass mixins and variables</strong> for media queries.</p>

<table class="style-guide-table">
	<tbody>
		<tr>
			<th>Bad</th>
			<td><code>@media (min-width: 576px) { ... }</code></td>
		</tr>
		<tr>
			<th>Good</th>
			<td><code>@include media-breakpoint-up(xs) { ... }</code></td>
		</tr>
	</tbody>
</table>

<h3 id="base-breakpoints-mixins-available-mixins">Available mixins</h3>

<table class="style-guide-table style-guide-table--fixed-layout">
	<thead>
		<tr>
			<th></th>
			<th>Note</th>
			<th>Mixin</th>
			<th>Compiled CSS</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Min width</td>
			<td>This is the mixin you should use in most cases since it follows the "mobile first" approach.</td>
			<td><code>@include media-breakpoint-up(sm) { ... }</code></td>
			<td><code>@media (min-width: 576px) { ... }</code></td>
		</tr>
		<tr>
			<td>Max width</td>
			<td>Media query that goes in the other direction.</td>
			<td><code>@include media-breakpoint-down(sm) { ... }</code></td>
			<td><code>@media (max-width: 575.98px) { ... }</code></td>
		</tr>
		<tr>
			<td>Single breakpoint</td>
			<td>Media query that is applied just to a single breakpoint.</td>
			<td><code>@include media-breakpoint-only(md) { ... }</code></td>
			<td><code>@media (min-width: 768px) and (max-width: 991.98px) { ... }</code></td>
		</tr>
		<tr>
			<td>Between breakpoints</td>
			<td>Media query that spans for multiple breakpoints.</td>
			<td><code>@include media-breakpoint-between(md, xl) { ... }</code></td>
			<td><code>@media (min-width: 768px) and (max-width: 1199.98px) { ... }</code></td>
		</tr>
	</tbody>
</table>

<p>When we're using <code>max-width</code> the breakpoint size is smaller for 0.02px. The reason for that is to work around browsers not supporting <a href="https://www.w3.org/TR/mediaqueries-4/#range-context">range context</a> queries so in some cases the <code>min-width</code> would be the same as <code>max-width</code> which would cause media queries to overlap.</p>
