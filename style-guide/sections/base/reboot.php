<?php
/**
 * Title: Reboot
 * Order: 2
 */
?>

<p>We're using a modified version of <a href="https://getbootstrap.com/docs/5.3/content/reboot/">Bootstrap 5.3.0 Reboot</a>.</p>

<p>Some more notable settings:</p>

<ul>
	<li>The <code>box-sizing</code> is globally set on every element—including <code>*::before</code> and <code>*::after</code>, to <code>border-box</code>.</li>
	<li>Set <code>1rem</code> to <code>10px</code>.</li>
	<li>The <code><?php echo esc_html( '<body>' ); ?></code> element also has these properties set:
		<ul>
			<li><code>font-family</code></li>
			<li><code>font-size</code></li>
			<li><code>font-weight</code></li>
			<li><code>line-height</code></li>
			<li><code>text-align</code></li>
			<li><code>color</code></li>
			<li><code>background-color</code></li>
		</ul>
	</li>
	<li>The <code><?php echo esc_html( '<img>' ); ?></code>, <code><?php echo esc_html( '<video>' ); ?></code> and <code><?php echo esc_html( '<audio>' ); ?></code> tags have <code>max-width: 100%;</code> and <code>height: auto;</code> applied ot them making them all responsive by default.</li>
</ul>

For more details check <code>base/reboot.scss</code> file.
