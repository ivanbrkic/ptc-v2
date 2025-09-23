<?php
/**
 * Title: Flex
 */
?>

<?php
	$direction       = [ 'row', 'row-reverse', 'column', 'column-reverse' ];
	$justify_content = [ 'start', 'end', 'center', 'between', 'around', 'evenly' ];
	$align_items     = [ 'start', 'end', 'center', 'baseline', 'stretch' ];
	$align_self      = [ 'start', 'end', 'center', 'baseline', 'stretch' ];
	$align_content   = [ 'end', 'center', 'between', 'around', 'stretch' ];
	$flex_order      = Pg_Style_Guide::get_scss_variable( 'flex-order', 0 );
?>

<p>If you need some help with flexbox check out this <a href="https://css-tricks.com/snippets/css/a-guide-to-flexbox/">tutorial</a> that explains flexbox layout model in detail.</p>

<p>Use <a href="#utilities-display">display utility</a> <code>.d-{breakpoint}-flex</code> class to enable the behavior on the parent element and use the other responsive flex utility classes to control direct children elements.</p>

<div class="style-guide-example style-guide-example--flex">
	<div class="d-flex p-1">
		This is a flexbox container.
	</div>
</div>

<h2 id="utilities-flex-direction">Direction</h2>

<?php foreach ( $direction as $val ) : ?>
	<p><code><?php echo esc_html( '.flex-{breakpoint}-' . $val ); ?></code></p>

	<div class="style-guide-example style-guide-example--flex">
		<div class="d-flex <?php echo esc_attr( 'flex-' . $val ); ?>">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="p-1">Flex item <?php echo esc_html( $i ); ?></div>
			<?php endfor; ?>
		</div>
	</div>
<?php endforeach; ?>

<h2 id="utilities-flex-justify-content">Justify content</h2>

<?php foreach ( $justify_content as $val ) : ?>
	<p><code><?php echo esc_html( '.justify-content-{breakpoint}-' . $val ); ?></code></p>

	<div class="style-guide-example style-guide-example--flex">
		<div class="d-flex <?php echo esc_attr( 'justify-content-' . $val ); ?>">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="p-1">Flex item <?php echo esc_html( $i ); ?></div>
			<?php endfor; ?>
		</div>
	</div>
<?php endforeach; ?>

<h2 id="utilities-flex-align-items">Align items</h2>

<?php foreach ( $align_items as $val ) : ?>
	<p><code><?php echo esc_html( '.align-items-{breakpoint}-' . $val ); ?></code></p>

	<div class="style-guide-example style-guide-example--flex">
		<div class="d-flex <?php echo esc_attr( 'align-items-' . $val ); ?>" style="height: 100px">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="p-1">Flex item <?php echo esc_html( $i ); ?></div>
			<?php endfor; ?>
		</div>
	</div>
<?php endforeach; ?>

<h2 id="utilities-flex-align-content">Align content</h2>

<p>These utilities don't have any effect on single rows of flex items.</p>

<?php foreach ( $align_content as $val ) : ?>
	<p><code><?php echo esc_html( '.align-content-{breakpoint}-' . $val ); ?></code></p>

	<div class="style-guide-example style-guide-example--flex">
		<div class="d-flex flex-wrap <?php echo esc_attr( 'align-content-' . $val ); ?>" style="height: 200px">
			<?php for ( $i = 1; $i <= 20; $i++ ) : ?>
				<div class="p-1">Flex item <?php echo esc_html( $i ); ?></div>
			<?php endfor; ?>
		</div>
	</div>
<?php endforeach; ?>

<h2 id="utilities-flex-wrap">Wrap</h2>

<p>Control how flex items wrap in a flex container.</p>

<p><code>.flex-{breakpoint}-wrap</code></p>

<div class="style-guide-example style-guide-example--flex">
	<div class="d-flex flex-wrap">
		<?php for ( $i = 1; $i <= 20; $i++ ) : ?>
			<div class="p-1">Flex item <?php echo esc_html( $i ); ?></div>
		<?php endfor; ?>
	</div>
</div>

<p><code>.flex-{breakpoint}-wrap-reverse</code></p>

<div class="style-guide-example style-guide-example--flex">
	<div class="d-flex flex-wrap-reverse">
		<?php for ( $i = 1; $i <= 20; $i++ ) : ?>
			<div class="p-1">Flex item <?php echo esc_html( $i ); ?></div>
		<?php endfor; ?>
	</div>
</div>

<p><code>.flex-{breakpoint}-nowrap</code></p>

<div class="style-guide-example style-guide-example--flex">
	<div class="d-flex flex-nowrap" style="width: 180px;">
		<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
			<div class="p-1">Flex item <?php echo esc_html( $i ); ?></div>
		<?php endfor; ?>
	</div>
</div>

<h2 id="utilities-flex-align-self">Align self</h2>

<p>Use <code>.align-self-{breakpoint}-{property}</code> on flex items to control the alignment.</p>

<?php foreach ( $align_self as $val ) : ?>
	<p><code><?php echo esc_html( '.align-self-{breakpoint}-' . $val ); ?></code></p>

	<div class="style-guide-example style-guide-example--flex">
		<div class="d-flex" style="height: 100px">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<?php $item_classes = $i === 2 ? 'p-1 align-self-' . $val : 'p-1'; ?>
				<div class="<?php echo esc_attr( $item_classes ); ?>">
					Flex item <?php echo esc_html( $i ); ?>
					<?php echo $i === 2 ? 'with <code>' . esc_html( '.align-self-' . $val ) . '</code> class' : ''; ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
<?php endforeach; ?>

<h2 id="utilities-flex-order">Order</h2>

<p>Use <code>.order-{breakpoint}-{value}</code> to control the <strong>visual</strong> order of flex items. Includes support from <code>0</code> to <code><?php echo esc_html( $flex_order ); ?></code>. If you need a bigger range edit <code>$flex-order</code> variable.</p>

<div class="style-guide-example style-guide-example--flex">
	<div class="d-flex">
		<div class="p-1 order-2">First flex item</div>
		<div class="p-1 order-3">Second flex item</div>
		<div class="p-1 order-1">Third flex item</div>
	</div>
</div>

<p>Also comes with <code>.order-{breakpoint}-first</code> and <code>.order-{breakpoint}-last</code> classes.</p>

<h2 id="utilities-flex-fill">Fill</h2>

<p>Use <code>.flex-{breakpoint}-fill</code> on flex items to fill all the available space.</p>

<div class="style-guide-example style-guide-example--flex">
	<div class="d-flex">
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<?php $item_classes = $i === 2 ? 'p-1 flex-fill' : 'p-1'; ?>
			<div class="<?php echo esc_attr( $item_classes ); ?>">
				Flex item <?php echo esc_html( $i ); ?>
				<?php echo $i === 2 ? 'with <code>.flex-fill</code> class' : ''; ?>
			</div>
		<?php endfor; ?>
	</div>
</div>

<h2 id="utilities-flex-grow-shrink">Grow and shrink</h2>

<p>Use <code>.flex-{breakpoint}-grow-{1|0}</code> and <code>.flex-{breakpoint}-shrink-{1|0}</code> on flex items to control will the items grow/shrink.</p>

<p><code><.flex-grow-{breakpoint}-{1|0}</code></p>

<div class="style-guide-example style-guide-example--flex">
	<div class="d-flex">
		<div class="p-1">Flex item 1</div>
		<div class="p-1 flex-grow-1">
			Flex item 2 with <code>.flex-grow-1</code> class
		</div>
	</div>
</div>

<p><code>.flex-shrink-{breakpoint}-{1|0}</code></p>

<div class="style-guide-example style-guide-example--flex">
	<div class="d-flex">
		<div class="p-1" style="width: 100%;">Flex item 1</div>
		<div class="p-1 flex-shrink-1">
			Flex item 2 with <br> <code>.flex-shrink-1</code> class
		</div>
	</div>
</div>

<h2 id="utilities-flex-auto-margins">Combining with auto margins</h2>

<p>Use <code>.m-{r|l}-{breakpoint}-auto</code> <a href="#utilities-spacing">spacing utility classes</a> to push content away from a flex item on the X axis.</p>

<?php foreach ( [ 'mr-auto', 'ml-auto' ] as $margin ) : ?>
	<div class="style-guide-example style-guide-example--flex">
		<div class="d-flex">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<?php $item_classes = $i === 2 ? 'p-1 ' . $margin : 'p-1'; ?>
				<div class="<?php echo esc_attr( $item_classes ); ?>">
					Flex item <?php echo esc_html( $i ); ?>
					<?php echo $i === 2 ? 'with <code>.' . esc_html( $margin ) . '</code> class' : ''; ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
<?php endforeach; ?>

<p>Use <code>.m-{t|b}-{breakpoint}-auto</code> <a href="#utilities-spacing">spacing utility classes</a> to push content away from a flex item on the Y axis.</p>

<?php foreach ( [ 'mt-auto', 'mb-auto' ] as $margin ) : ?>
	<div class="style-guide-example style-guide-example--flex">
		<div class="d-flex flex-column" style="height: 200px;">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<?php $item_classes = $i === 2 ? 'p-1 ' . $margin : 'p-1'; ?>
				<div class="<?php echo esc_attr( $item_classes ); ?>">
					Flex item <?php echo esc_html( $i ); ?>
					<?php echo $i === 2 ? 'with <code>.' . esc_html( $margin ) . '</code> class' : ''; ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
<?php endforeach; ?>
