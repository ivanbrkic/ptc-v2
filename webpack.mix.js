/**
 * Laravel Mix config.
*/

/* eslint-env node */
/* eslint-disable unicorn/prefer-module */
/* eslint-disable unicorn/prefer-node-protocol */

/**
 * Require dependencies.
 */
// Laravel mix
const mix = require('laravel-mix');
// Path
const path = require('path');
// File system
const fs = require('fs');
// Imagemin webpack plugin
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');
// Copy webpack plugin
const copyWebpackPlugin = require('copy-webpack-plugin');
// Laravel Mix Clean
require('laravel-mix-clean');
// Laravel-mix-svg-sprite
require('laravel-mix-svg-sprite');
// Export SCSS variables
require('./export-scss-variables');

/**
 * Define the variables we need.
 */
// Source path
// This is the path where your raw assets are.
const sourcePath = 'assets/src';

// Distribution path
// This is the path where your compiled assets are.
const distributionPath = 'assets/dist';

// Set browserSyncDomain
// This is the domain of your local development version of the site.
// The domain gets generated automatically based on the name of your root site folder.
let browserSyncDomain = `https://${path.basename(path.join(__dirname, '../../..'))}.test`;

// If mix-domain.json file is present use the URL from that file instead of the root site folder name.
if (fs.existsSync('./mix-domain.json')) {
  const customDomain = JSON.parse(fs.readFileSync('./mix-domain.json')).url;
  browserSyncDomain = customDomain || browserSyncDomain;
}

/**
 * Helper function to check if directory is empty.
 * @param {string} path Path to check.
 * @param {bool} useSourcePath Use sourcePath.
 * @return {bool}
 */
function isDirectoryEmpty(path, useSourcePath = true) {
  if (useSourcePath) {
    path = sourcePath + '/' + path;
  }

  if (!fs.existsSync(path)) {
    return true;
  }

  return fs.readdirSync(path).length === 0;
}

/**
 * Laravel Mix settings.
 */
// Disable success messages.
mix.disableSuccessNotifications();

// Set public path
// This is a folder where all your assets will get compiled to.
mix.setPublicPath(distributionPath);

// Turn on file versioning
// This will create mix-manifest.json with hashes used for each of the compiled files which we use for cache busting.
// https://laravel-mix.com/docs/6.0/versioning
mix.version();

// Turn on source maps
// Source maps enable you to debug your code in the browser by providing a reference to your assets before the build.
// For example if you're debugging your CSS source maps will provide references to that part of the code but in your SCSS files.
// We have to define the type of source maps because of this issue: https://github.com/JeffreyWay/laravel-mix/issues/1793#issuecomment-556084373.
mix.sourceMaps(false, 'inline-source-map');

// Turn off URL processing in CSS.
// If processCssUrls is on Mix goes through your CSS and adds query strings to your URL's for cache busting.
// Mix also copies the referenced images in your CSS to images folder in distributionPath but doesn't do any image optimization.
// Since we have a separate task for image optimization we have to turn this off in Mix.
mix.options({ processCssUrls: false });

// PostCSS
// Define default PostCSS plugins.
const postCss = [
  require('postcss-preset-env')({
    features: {
      'custom-properties': false,
      'logical-properties-and-values': false,
      'text-decoration-shorthand': false,
    },
  }),
  require('postcss-variables-prefixer')({
    prefix: 'pg-',
  }),
];
// Add postcss-discard-comments plugin if we're in production.
if (mix.inProduction()) {
  postCss.push(require('postcss-discard-comments')({ removeAll: true }));
}
// Set PostCSS options.
mix.options({ postCss }); // eslint-disable-line padding-line-between-statements

// Clean distributionPath
// This removes the folder where we build all of our assets when we start Mix.
// Prevents having stale assets like images in distributionPath that remain after we remove them in sourcePath.
mix.clean({
  // Ignore some files.
  cleanOnceBeforeBuildPatterns: [
    '**/*',
    '!style-guide',
    '!style-guide/scss-variables.json',
  ],
});

// Browsersync
// Browsersync monitors your files for changes and does a "live reload" when something changes in those files.
mix.browserSync({
  proxy: browserSyncDomain,
  files: [
    `${distributionPath}/theme/css/main.css`,
    `${distributionPath}/theme/js/main.js`,
    `${distributionPath}/theme/img/*`,
    `${distributionPath}/theme/sprite-icons/*`,
    `${distributionPath}/theme/font/*`,
    `${distributionPath}/style-guide/style-guide.css`,
    `${distributionPath}/style-guide/style-guide.js`,
    `${distributionPath}/style-guide/scss-variables.json`,
    `${distributionPath}/responsive-console/responsive-console.css`,
    `${distributionPath}/responsive-console/responsive-console.js`,
    '*.php',
    '{lib,templates,woocommerce,style-guide}/**/*.php',
  ],
});

// Exclude dependencies
// Exclude dependencies that shouldn't be compiled in output bundle.
mix.webpackConfig({
  externals: {
    jquery: 'jQuery',
  },
});

/**
 * Build theme assets.
 */
// SCSS
// Main theme CSS.
mix.sass(`${sourcePath}/theme/scss/main.scss`, 'theme/css');

// JavaScript
// Main theme JavaScript.
mix.js(`${sourcePath}/theme/js/main.js`, 'theme/js');
// Optional vendors JavaScript.
// All of the files in this folder will be combined in to one file and transpiled with Babel.
// https://laravel-mix.com/docs/6.0/concatenation-and-minification#concatenate-scripts-and-apply-babel-compilation
if (!isDirectoryEmpty('theme/js/vendor')) {
  mix.combine(`${sourcePath}/theme/js/vendor`, `${distributionPath}/theme/js/vendor.js`, true);
}

// Images
// Mix doesn't have support for image minification out of the box so we have to modify webpack config.
mix.webpackConfig({
  plugins: [
    new copyWebpackPlugin({ // eslint-disable-line new-cap
      patterns: [
        {
          context: `${sourcePath}/theme/img/`,
          from: '**/*.{jpg,jpeg,png,gif,svg}',
          to: 'theme/img',
        },
      ],
    }),
    new ImageMinimizerPlugin({
      test: [
        /\.(jpe?g|png|gif)$/i, // Image file extensions.
        /(?<!sprite-icons)\.svg$/i, // Separate RegEx for SVG but exclude sprite-icons.svg.
      ],
      minimizer: {
        implementation: ImageMinimizerPlugin.imageminMinify,
        options: {
          plugins: [
            [
              'gifsicle',
              {
                interlaced: true,
              },
            ],
            [
              'optipng',
              {
                optimizationLevel: 5,
              },
            ],
            'mozjpeg',
            'svgo',
          ],
        },
      },
    }),
  ],
});

// SVG sprite
mix.svgSprite(
  `${sourcePath}/theme/sprite-icons/icons`, // The directory containing your SVG icons that will be combined in the sprite.
  'theme/sprite-icons/sprite-icons.svg', // The output path for the sprite.
  {
    extract: true,
    symbolId: 'sprite-icon-[name]',
  },
  {
    plainSprite: true,
    spriteAttrs: {
      'aria-hidden': 'true',
      id: 'sprite-icons',
      style: 'display:none!important;width:0!important;height:0!important',
    },
  },
);
// Optimize the sprite
mix.webpackConfig({
  plugins: [
    new ImageMinimizerPlugin({
      test: /sprite-icons\.svg$/i,
      minimizer: {
        implementation: ImageMinimizerPlugin.imageminMinify,
        options: {
          plugins: [
            [
              'svgo',
              {
                plugins: [
                  {
                    name: 'preset-default',
                    params: {
                      overrides: {
                        removeHiddenElems: {
                          displayNone: false,
                        },
                        removeUnusedNS: false,
                      },
                    },
                  },
                  {
                    name: 'removeAttrs',
                    params: {
                      attrs: '(fill|stroke|class)',
                    },
                  },
                ],
              },
            ],
          ],
        },
      },
    }),
  ],
});
// Laravel-mix-svg-sprite only adds a loader to the Webpack configuration, it doesn't include referenced files in mix.svgSprite settings. That's why we need to require the icons in the assets Webpack compiles so we have to add this JavaScript that requires our icons.
mix.js(`${sourcePath}/theme/sprite-icons/get-icons.js`, 'theme/sprite-icons');

// Fonts
// If we have a font directory copy it to distributionPath.
if (!isDirectoryEmpty('theme/font')) {
  mix.copy(`${sourcePath}/theme/font`, `${distributionPath}/theme/font`);
}

/**
 * Build responsive console assets.
 */
// SCSS
// Responsive console CSS.
mix.sass(`${sourcePath}/responsive-console/responsive-console.scss`, 'responsive-console');

// JavaScript
// Responsive console JavaScript.
mix.js(`${sourcePath}/responsive-console/responsive-console.js`, 'responsive-console');

/**
 * Build admin assets.
 */
// Gutenberg JavaScript.
mix.js(`${sourcePath}/admin/gutenberg.js`, 'admin');

/**
 * Build style guide assets.
 */
// SCSS
// Style guide CSS.
mix.sass(`${sourcePath}/style-guide/style-guide.scss`, 'style-guide');

// JavaScript
// Style guide JavaScript.
mix.js(`${sourcePath}/style-guide/style-guide.js`, 'style-guide');

// Export SCSS variables.
mix.exportScssVariables(
  // SCSS files to include.
  [
    `${sourcePath}/theme/scss/abstracts/_functions.scss`,
    `${sourcePath}/theme/scss/abstracts/_variables.scss`,
  ],
  // Where to save the JSON file with the variables.
  `${distributionPath}/style-guide/scss-variables.json`,
  // What variables to export.
  ['colors', 'grid-breakpoints', 'grid-columns', 'grid-gutter-width', 'grid-row-columns', 'container-max-widths', 'displays', 'spacers', 'flex-order'],
  // Sass options.
  {
    loadPaths: ['assets/src/theme/scss/abstracts'],
    silenceDeprecations: ['global-builtin', 'import'],
  },
);
