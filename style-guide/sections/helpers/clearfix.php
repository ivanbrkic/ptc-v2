<?php
/**
 * Title: Clearfix
 */
?>

<p>Clear floats by adding <code>.clearfix</code> to the parent element. Can also be used as a silent class <code>%clearfix</code>.</p>

<div class="style-guide-example">
	<p>Example of the applied <code>.clearfix</code>:</p>
	<div class="bg-primary clearfix">
		<div class="p-1 text-white bg-black" style="float:left;">
			This text is on the left
		</div>
		<div class="p-1 text-white bg-black" style="float:right;">
			This text is on the right
		</div>
	</div>
	<hr>
	<p>Example without <code>.clearfix</code>:</p>
	<div class="bg-primary">
		<div class="p-1 text-white bg-black" style="float:left;">
			This text is on the left
		</div>
		<div class="p-1 text-white bg-black" style="float:right;">
			This text is on the right
		</div>
	</div>
</div>
