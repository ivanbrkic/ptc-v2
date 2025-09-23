<?php
/**
 * Title: Root and custom properties
 * Order: 1
 */
?>

<p>We're using <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties">CSS custom properties</a> for some parts of the project.</p>

<p>All of the custom properties are prefixed with postcss-variables-prefixer PostCSS plugin. The default prefix is <code>pg-</code> but you can set your prefix in <code>webpack.mix.js</code> in the <code>postcss-variables-prefixer</code> section.</p>

<p>When using SCSS variables in custom properties be sure to use <a href="https://sass-lang.com/documentation/interpolation/">interpolation</a> in order to get the result of SCSS expression. For example you'd like to create a custom property <code>--grid-gutter-width</code> what has the value of an SCSS variable called <code>$width</code> you have to use <code>#{}</code> around the variable like so: <code>--grid-gutter-width: #{$grid-gutter-width}</code>.</p>

<p>All of our custom properties are defined in <code>root.scss</code> and are globally scoped but for some cases you'll have to define custom properties inside a selector to scope them to that selector. Keep in mind that custom properties follow the rules of CSS cascade and specificity the same as other CSS selectors.</p>
