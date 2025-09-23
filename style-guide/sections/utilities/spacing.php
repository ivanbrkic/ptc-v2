<?php
/**
 * Title: Spacing
 */
?>

<p>Responsive utility classes to assign <code>margin</code> and <code>padding</code> to elements.</p>

<p>The classes use the format <code>.{property}-{side}-{breakpoint}-{size}</code>.</p>

<p>The <code>{property}</code> in the class can be:</p>

<ul>
	<li><code>m</code> - for classes that set <code>margin</code></li>
	<li><code>p</code> - for classes that set <code>padding</code></li>
</ul>

<p>The <code>{side}</code> in the class can be:</p>

<ul>
	<li><code>t</code> - for classes that set <code>margin-top</code>/<code>padding-top</code></li>
	<li><code>b</code> - for classes that set <code>margin-bottom</code>/<code>padding-bottom</code></li>
	<li><code>r</code> - for classes that set <code>margin-right</code>/<code>padding-right</code></li>
	<li><code>l</code> - for classes that set <code>margin-left</code>/<code>padding-left</code></li>
	<li><code>x</code> - for classes that set <code>margin-right</code> and <code>margin-left</code>/<code>padding-right</code> and <code>padding-left</code></li>
	<li><code>y</code> - for classes that set <code>margin-top</code> and <code>margin-bottom</code>/<code>padding-top</code> and <code>padding-bottom</code></li>
	<li>blank - classes that set <code>margin</code>/<code>padding</code> on all 4 sides</li>
</ul>

<p>The <code>{size}</code> in the class can be:</p>

<ul>
	<?php foreach ( Pg_Style_Guide::get_scss_variable( 'spacers', [] ) as $key => $val ) : ?>
		<li><code><?php echo esc_html( $key ); ?></code> - for classes that set <code>margin</code>/<code>padding</code> to <code><?php echo esc_html( $key . ' * $spacer' ); ?></code></li>
	<?php endforeach; ?>
	<li><code>auto</code> - for classes the set <code>margin</code> to auto</li>
</ul>

<p>The <code>{size}</code> depends on the setting in <code>$spacers</code> variable where you can add or remove spacer values.</p>

<h2 id="utilities-spacing-negative-margin">Negative margins</h2>

<p>There is an optional utility which you can enable to generate margin utility classes for negative margins. Set <code>$negative-margins: true</code> and those classes will be generated.</p>

<p>The syntax is the same as for regular margin classes just with an <code>n</code> (as in negative) before the size value. IE: if you want a top negative margin of one spacer the class is <code>.mt-n1</code>.</p>
