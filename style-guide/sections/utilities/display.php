<?php
/**
 * Title: Display
 */
?>

<p>Change the value of the display property with responsive display utility classes. The classes have support for all of our breakpoints and are mobile first.</p>

<p>Supported values are:</p>

<ul>
	<?php foreach ( Pg_Style_Guide::get_scss_variable( 'displays', [] ) as $value ) : ?>
		<li><code><?php echo esc_html( $value ); ?></code></li>
	<?php endforeach; ?>
</ul>

<p>If you need to add additional values modify the <code>$displays</code> variable.</p>

<p>The notation for the classes uses the format <code>.d-{breakpoint}-{value}</code> so for example adding a class of <code>.d-md-flex</code> means that that element will have <code>display: flex;</code> applied to it from breakpoint MD and above.</p>
