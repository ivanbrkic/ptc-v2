/**
 * Get SVG icons to compile the SVG sprite sheet.
*/
/* eslint-env node */
/* eslint-disable unicorn/no-array-callback-reference */
/* eslint-disable unicorn/prefer-module */

const svgIcons = require.context('./icons/', true, /\.svg$/);
svgIcons.keys().forEach(svgIcons);
