<?php
/**
 * Title: Grid
 */
?>

<?php
$breakpoints       = Pg_Style_Guide::get_scss_variable( 'grid-breakpoints', [] );
$grid_columns      = Pg_Style_Guide::get_scss_variable( 'grid-columns', 0 );
$grid_gutter_width = Pg_Style_Guide::get_scss_variable( 'grid-gutter-width' );
$grid_row_columns  = Pg_Style_Guide::get_scss_variable( 'grid-row-columns' );
?>

<p>Our grid is based on <a href="https://getbootstrap.com/docs/5.3/layout/grid/">Bootstrap 5.3</a> with some minor differences.</p>

<p>Our grid is a mobile first <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout/Basic_Concepts_of_Flexbox">flexbox</a> based system which you can use for laying out the content.</p>

<p>Here are some basic rules and explanations on how the grid actually works:</p>

<ol>
	<li>The grid is based on the values in <code>$grid-breakpoints</code>. That variable controls the classes and breakpoints that the grid system uses.</li>
	<li>The grid is mobile first so all of the breakpoints are based on <code>min-width</code> media queries.</li>
	<li>The number of grid columns is set in <code>$grid-columns</code> and the gutter is set in <code>$grid-gutter-width</code>. Modify those values based on your project requirements.</li>
	<li>Rows (<code>.row</code>) are used as wrappers for columns. Each column has horizontal <code>padding</code> (gutter) for controlling the space between them. This padding is then counteracted on the rows with negative margins to ensure the content in your columns is visually aligned down the left side.</li>
	<li>Columns have to be first level children of <code>.row</code>.</li>
	<li>You can customize grid gutters by adding values to <code>$gutters</code> variable. The values there are used to generate utility classes for modifying grid gutters. By default the only gutter modifier classes that get generated are <code>.g-{breakpoint}-0</code>.</li>
	<li><strong>The grid has to be used inside of a container.</strong> If you don't use it inside of a container negative margins on <code>.row</code> will create a horizontal scrollbar. The only exception to this rule is if you are using any of the <code>.g-{breakpoint}-0</code> classes which remove the gutter between the columns and the negative margin on <code>.row</code>.</li>
</ol>

<p>If you need some help with flexbox check out this <a href="https://css-tricks.com/snippets/css/a-guide-to-flexbox/">tutorial</a> that explains flexbox layout model in detail.</p>

<p>The table below contains information about the settings of the grid system.</p>

<table class="style-guide-table">
	<thead>
		<tr>
			<th></th>
			<?php foreach ( $breakpoints as $breakpoint_name => $breakpoint_width ) : ?>
				<?php
				$start_width  = $breakpoint_width;
				$compare_sign = '≥';
				if ( 0 === $breakpoint_width ) {
					$start_width  = next( $breakpoints );
					$compare_sign = '<';
				}
				?>
				<th>
					<?php echo esc_html( strtoupper( $breakpoint_name ) ); ?><br>
					<?php echo esc_html( $compare_sign . ' ' . $start_width ); ?>
				</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Class prefix</th>
			<?php foreach ( $breakpoints as $breakpoint_name => $breakpoint_width ) : ?>
					<?php
					$class = '.col-';
					if ( 0 < $breakpoint_width ) {
						$class .= $breakpoint_name . '-';
					}
					?>
					<td><code><?php echo esc_html( $class ); ?></code></td>
				<?php endforeach; ?>
		</tr>
		<tr>
			<th>Number of columns</th>
			<td colspan="<?php echo count( $breakpoints ); ?>"><?php echo esc_html( $grid_columns ); ?></td>
		</tr>
		<tr>
			<th>Gutter width</th>
			<td colspan="<?php echo count( $breakpoints ); ?>"><?php echo esc_html( $grid_gutter_width ); ?></td>
		</tr>
	</tbody>
</table>

<h2 id="base-grid-columns-equal">Equal width columns</h2>

<p>The simplest columns to use are equal width columns apply <code>.col-{breakpoint}</code> class to columns and they will take up the same amount of space.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="col">Column</div>
			<?php endfor ?>
		</div>
	</div>
</div>

<h2 id="base-grid-columns-width">"Fixed" width columns</h2>

<p>Use <code>.col-{breakpoint}-{column-number}</code> to set a "fixed" width size for columns. Note that the "fixed" width refers to the number of columns your column will take up IE: <code>.col-6</code> will take up 6 out of <?php echo esc_html( $grid_columns ); ?> columns.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<?php for ( $i = 1; $i <= 2; $i++ ) : ?>
				<div class="col-6">6 of <?php echo esc_html( $grid_columns ); ?></div>
			<?php endfor ?>
		</div>
	</div>
</div>

<h2 id="base-grid-columns-auto">Auto width columns</h2>

<p>Use <code>.col-{breakpoint}-auto</code> classes to size columns based on the width of their content.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-auto">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit.
			</div>
			<div class="col-auto">
				Vero soluta quasi quam quibusdam perspiciatis rerum doloremque dicta?
			</div>
		</div>
	</div>
</div>

<h2 id="base-grid-columns-mix">Mix and match columns</h2>

<p>You can combine all of the column type classes.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-auto">
				This is an auto width column.
			</div>
			<div class="col-4">
				This is a fixed width column that takes 4 of <?php echo esc_html( $grid_columns ); ?>
			</div>
			<div class="col">
				This is a column with <code>.col</code> class that will take all of the remaining space.
			</div>
		</div>
	</div>
</div>

<p>All of the mentioned classes have support for each of our breakpoints and can be combined on one column.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-6 col-sm-8 col-md-auto col-lg-7">
				This column has the following classes added <code>.col-6 .col-sm-8 .col-md-auto .col-lg-7</code>. Change the size of your screen to see each of the changes apply to the column.
			</div>
			<div class="col-6 col-sm-5 col-md">
				This column has the following classes added <code>.col-6 .col-sm-5 .col-md</code>. Change the size of your screen to see each of the changes apply to the column.
			</div>
		</div>
	</div>
</div>

<h2 id="base-grid-row-columns">Row columns</h2>

<p>Use <code>.row-cols{breakpoint}-{size}</code> class on <code>.row</code> to define the column sizes.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row row-cols-2">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<div class="col">Column <?php echo esc_html( $i ); ?></div>
			<?php endfor ?>
		</div>
	</div>
</div>

<p>With the number value for size in <code>.row-cols{breakpoint}-{size}</code> there is also a version with auto as a value which makes all columns have a width based on their content.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row row-cols-auto">
			<div class="col">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit.
			</div>
			<div class="col">
				Vero soluta quasi quam quibusdam perspiciatis rerum doloremque dicta?
			</div>
			<div class="col">
				Perferendis assumenda error autem magni quae ipsum ut mollitia natus itaque tenetur rem odio, sed placeat, cupiditate ducimus? Corrupti, voluptates.
			</div>
		</div>
	</div>
</div>

<p>Includes support from <code>1</code> to <code><?php echo esc_html( $grid_row_columns ); ?></code> for <code>.row-cols{breakpoint}-{size}</code> classes. If you need a bigger range edit <code>$grid-row-columns</code> variable.</p>

<h2 id="base-grid-columns-nesting">Nesting</h2>

<p>To nest your content add a new <code>.row</code> inside a column and add new columns in the row.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3">
				Level 1: <code>.col-sm-3</code>
			</div>
			<div class="col-sm-9">
				<div class="row">
					<div class="col-8 col-sm-6">
						Level 2: <code>.col-8 .col-sm-6</code>
					</div>
					<div class="col-4 col-sm-6">
						Level 2: <code>.col-4 .col-sm-6</code>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<h2 id="base-grid-offset">Offset</h2>

<p>Use <code>.offset-{breakpoint}-{value}</code> to move columns to the right.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-3 offset-1">
				First column width <code>.col-3 .offset-1</code> classes
			</div>
			<div class="col-3 offset-2">
				Second column width <code>.col-3 .offset-2</code> classes
			</div>
		</div>
	</div>
</div>

<p>You can use <a href="#utilities-spacing">margin utilities</a> for auto margins also to move columns away from each other.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-3 mr-auto">
				First column width <code>.mr-auto</code> class
			</div>
			<div class="col-3">
				Second column
			</div>
		</div>
	</div>
</div>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<div class="col-3">
				First column
			</div>
			<div class="col-3 ml-auto">
				Second column width <code>.ml-auto</code> class
			</div>
		</div>
	</div>
</div>

<h2 id="base-grid-alignment-and-order">Alignment and order</h2>

<p>Use <a href="#utilities-flex">flexbox utility classes</a> to align columns vertically and horizontally.</p>

<p>The <code>.row</code> in the example below has <code>.align-items-center</code> applied to it.</p>

<div class="style-guide-example style-guide-example--grid style-guide-example--flex">
	<div class="container-fluid">
		<div class="row align-items-center" style="height: 200px">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="col">Column <?php echo esc_html( $i ); ?></div>
			<?php endfor ?>
		</div>
	</div>
</div>

<p>The <code>.row</code> in the example below has <code>.justify-content-center</code> applied to it.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row justify-content-center">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="col-2">Column <?php echo esc_html( $i ); ?></div>
			<?php endfor ?>
		</div>
	</div>
</div>

<p>The <code>.row</code> in the example below has <code>.flex-row-reverse</code> applied to it.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row flex-row-reverse">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="col">Column <?php echo esc_html( $i ); ?></div>
			<?php endfor ?>
		</div>
	</div>
</div>

<p>You can apply classes to columns to change their alignment individually.</p>

<div class="style-guide-example style-guide-example--grid style-guide-example--flex">
	<div class="container-fluid">
		<div class="row" style="height: 200px">
			<?php foreach ( [ 'start', 'center', 'end' ] as $val ) : ?>
				<?php $align_classes = 'align-self-' . $val; ?>
				<div class="col <?php echo esc_attr( $align_classes ); ?>">
					Column with <code>.<?php echo esc_html( $align_classes ); ?></code> class
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<p>You can apply classes to columns to change their order individually.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row">
			<div class="col order-2">
				First column width <code>.order-2</code> class
			</div>
			<div class="col order-3">
				Second column width <code>.order-3</code> class
			</div>
			<div class="col order-1">
				Third column width <code>.order-1</code> class
			</div>
		</div>
	</div>
</div>

<p>Check the <a href="#utilities-flex">docs</a> for flexbox utilities for more examples.</p>

<h2 id="base-grid-gutter">Gutter</h2>

<p>Change the gutter width with <code>.gutter-{breakpoint}-{size}</code> in the grid.</p>

<div class="style-guide-example style-guide-example--grid">
	<div class="container-fluid">
		<div class="row g-0">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<div class="col-3">Column <?php echo esc_html( $i ); ?> with some text to show that there are no gutters between them.</div>
			<?php endfor ?>
		</div>
	</div>
</div>

<p>By default the only gutter modifier classes that get generated are <code>.g-{breakpoint}-0</code>. You can add more grid gutter classes by adding values to <code>$gutters</code> variable.</p>
