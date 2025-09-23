<?php
/**
 * Title: Typography
 */
?>

<h2 id="base-typography-body-text">Body text</h2>

<div class="style-guide-example">
	<p>Lorem ipsum dolor <a href="#">and a link</a>, consectetur <del>adipisicing elit</del>. Recusandae, rerum perferendis quos aspernatur ullam eos <em><strong>voluptate quo cupiditate</strong></em> assumenda facilis, <strong>iste animi doloremque</strong>, quasi <strong><em>eveniet laboriosam</em></strong>. Dolor perferendis debitis dolore. Repellendus laudantium, quas excepturi ducimus reiciendis, eligendi unde quia perspiciatis voluptatibus pariatur repellat quibusdam, molestiae cum quaerat ex a modi neque? Id officiis doloremque nisi, veritatis fugit fugiat eveniet placeat?</p>

	<p>Officia, veniam. Molestias harum quasi, explicabo nesciunt fugit dicta provident natus veniam eveniet eos doloremque dolor, similique voluptates qui adipisci repudiandae iste sit nobis soluta error dolore accusamus fugiat, porro.</p>
</div>

<h2 id="base-typography-headings">Headings</h2>

<table class="style-guide-table">
	<thead>
		<tr>
			<th>Example</th>
			<th>Heading</th>
		</tr>
	</thead>
	<tbody>
		<?php for ( $heading = 1; $heading <= 6; $heading++ ) : ?>
			<tr>
				<td><h<?php echo esc_attr( $heading ); ?> class="m-0" >Heading <?php echo esc_html( $heading ); ?></h <?php echo esc_attr( $heading ); ?> ></td>
				<td><code><?php echo esc_html( '<h' . $heading . '>' ); ?></code></td>
			</tr>
		<?php endfor; ?>
	</tbody>
</table>

<h2 id="base-typography-lists">Lists</h2>

<div class="style-guide-example">
	<p>Unordered list:</p>
	<ul>
		<li>Lorem ipsum dolor sit amet</li>
		<li>Consectetur adipiscing elit</li>
		<li>Integer molestie lorem at massa</li>
		<li>Facilisis in pretium nisl aliquet</li>
		<li>Nulla volutpat aliquam velit
			<ul>
				<li>Phasellus iaculis neque</li>
				<li>Purus sodales ultricies</li>
				<li>Vestibulum laoreet porttitor sem</li>
				<li>Ac tristique libero volutpat at</li>
			</ul>
		</li>
		<li>Faucibus porta lacus fringilla vel</li>
		<li>Aenean sit amet erat nunc</li>
		<li>Eget porttitor lorem</li>
	</ul>
	<p>Ordered list:</p>
	<ol>
		<li>Lorem ipsum dolor sit amet</li>
		<li>Consectetur adipiscing elit</li>
		<li>Integer molestie lorem at massa</li>
		<li>Facilisis in pretium nisl aliquet</li>
		<li>Nulla volutpat aliquam velit
			<ol>
				<li>Phasellus iaculis neque</li>
				<li>Purus sodales ultricies</li>
				<li>Vestibulum laoreet porttitor sem</li>
				<li>Ac tristique libero volutpat at</li>
			</ol>
		</li>
		<li>Faucibus porta lacus fringilla vel</li>
		<li>Aenean sit amet erat nunc</li>
		<li>Eget porttitor lorem</li>
	</ol>
	<p>Definition list:</p>
	<dl>
		<dt>Time</dt>
		<dd>The indefinite continued progress of existence and events in the past, present, and future regarded as a whole.</dd>
		<dt>Space</dt>
		<dd>A continuous area or expanse that is free, available, or unoccupied.</dd>
		<dd>The dimensions of height, depth, and width within which all things exist and move.</dd>
	</dl>
</div>

<p>Use <code>.list-unstyled</code> class or <code>%list-unstyled</code> <a href="https://sass-lang.com/documentation/style-rules/placeholder-selectors/">placeholder selector</a> or <code>list-unstyled()</code> mixin to reset list styling of <code><?php echo esc_html( '<ul>' ); ?></code> and <code><?php echo esc_html( '<ol>' ); ?></code> lists.</p>

<div class="style-guide-example">
	<p>Unordered list:</p>
	<ul class="list-unstyled">
		<li>Lorem ipsum dolor sit amet</li>
		<li>Consectetur adipiscing elit</li>
		<li>Integer molestie lorem at massa</li>
		<li>Facilisis in pretium nisl aliquet</li>
	</ul>
	<p>Ordered list:</p>
	<ol class="list-unstyled">
		<li>Lorem ipsum dolor sit amet</li>
		<li>Consectetur adipiscing elit</li>
		<li>Integer molestie lorem at massa</li>
		<li>Facilisis in pretium nisl aliquet</li>
	</ol>
</div>

<p>Use <code>.list-styled</code> class or <code>%list-styled</code> <a href="https://sass-lang.com/documentation/style-rules/placeholder-selectors/">placeholder selector</a> or <code>list-styled()</code> mixin to apply additional styling to <code><?php echo esc_html( '<ul>' ); ?></code> and <code><?php echo esc_html( '<ol>' ); ?></code> lists.</p>

<div class="style-guide-example">
	<p>Unordered list:</p>
	<ul class="list-styled">
		<li>Lorem ipsum dolor sit amet</li>
		<li>Consectetur adipiscing elit</li>
		<li>Integer molestie lorem at massa</li>
		<li>Facilisis in pretium nisl aliquet</li>
	</ul>
	<p>Ordered list:</p>
	<ol class="list-styled">
		<li>Lorem ipsum dolor sit amet</li>
		<li>Consectetur adipiscing elit</li>
		<li>Integer molestie lorem at massa</li>
		<li>Facilisis in pretium nisl aliquet</li>
	</ol>
</div>

<h2 id="base-typography-blockquote">Blockquote</h2>

<div class="style-guide-example">
	<blockquote>
		<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante."</p>
	</blockquote>
</div>

<p>To add a source of the quote add a <code><?php echo esc_html( '<cite>' ); ?></code> in the <code><?php echo esc_html( '<blockquote>' ); ?></code>.</p>

<div class="style-guide-example">
	<blockquote>
		<p>"I am game for his crooked jaw, and for the jaws of Death too, Captain Ahab, if it fairly comes in the way of the business we follow; but I came here to hunt whales, not my commander's vengeance. How many barrels will thy vengeance yield thee even if thou gettest it, Captain Ahab? it will not fetch thee much in our Nantucket market."</p>
		<cite title="Starbuck">Starbuck</cite>
	</blockquote>
</div>

<h2 id="base-typography-horizontal-rule">Horizontal rule</h2>

<p>Use to separate content sections.</p>

<p><strong>NOTE:</strong> Don't use it to separate <code><?php echo esc_html( '<div>' ); ?></code> elements or some similar layout actions.

<div class="style-guide-example">
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	<hr>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
</div>

<h2 id="base-typography-abbreviations">Abbreviations</h2>

<div class="style-guide-example">
	<p class="m-0"><abbr title="attribute">attr</abbr></p>
	<p class="m-0"><abbr title="HyperText Markup Language">HTML</abbr></p>
</div>

<h2 id="base-typography-other">Other text inline elements</h2>

<div class="style-guide-example">
	<p class="m-0"><code><?php echo esc_html( '<mark>' ); ?></code> - You can use the mark tag to <mark>highlight</mark> text.</p>
	<p class="m-0"><code><?php echo esc_html( '<del>' ); ?></code> - <del>This line of text is meant to be treated as deleted text.</del></p>
	<p class="m-0"><code><?php echo esc_html( '<s>' ); ?></code> - <s>This line of text is meant to be treated as no longer accurate.</s></p>
	<p class="m-0"><code><?php echo esc_html( '<ins>' ); ?></code> - <ins>This line of text is meant to be treated as an addition to the document.</ins></p>
	<p class="m-0"><code><?php echo esc_html( '<u>' ); ?></code> - <u>This line of text will render as underlined</u></p>
	<p class="m-0"><code><?php echo esc_html( '<small>' ); ?></code> - <small>This line of text is meant to be treated as fine print.</small></p>
	<p class="m-0"><code><?php echo esc_html( '<strong>' ); ?></code> - <strong>This line rendered as bold text.</strong></p>
	<p class="m-0"><code><?php echo esc_html( '<em>' ); ?></code> - <em>This line rendered as italicized text.</em></p>
	<p class="m-0"><code><?php echo esc_html( '<b>' ); ?></code> - <b>This line rendered as bold text.</b></p>
	<p class="m-0"><code><?php echo esc_html( '<i>' ); ?></code> - <i>This line rendered as italicized text.</i></p>
</div>
