/**
 * Export SCSS variables Mix plugin.
*/

/* eslint-env node */
/* eslint-disable unicorn/prefer-module */
/* eslint-disable unicorn/prefer-node-protocol */

/**
 * Require dependencies.
 */
// Laravel mix
const mix = require('laravel-mix');
// Get SASS vars
const getSassVars = require('get-sass-vars');
// File system
const fs = require('fs');

// eslint-disable-next-line max-params
mix.extend('exportScssVariables', (webpackConfig, paths = [], jsonPath, variables = [], sassOptions = {}) => {
  if (variables) {
    mix.after(() => {
      let scss = '';

      // Combine all paths in one file.
      paths.forEach(path => {
        scss += fs.readFileSync(path, 'utf8');
      });

      // Get SCSS variables as JSON.
      const scssVariables = getSassVars.sync(scss, { sassOptions });

      // Store our variables in variablesFiltered.
      const variablesFiltered = {};

      // Go over variables and try to get a variable base don it's name from scssVariables.
      variables.forEach(variable => {
        // Add $ to variable name.
        const variableName = `$${variable}`;

        // If we have a variable with that name in scssVariables add it variablesFiltered.
        if (Object.prototype.hasOwnProperty.call(scssVariables, variableName)) {
          variablesFiltered[variable] = scssVariables[variableName];
        } else {
          // eslint-disable-next-line no-console
          console.warn(`Variable "${variable}" not found in the SCSS file.`);
        }
      });

      // Write the JSON file.
      fs.writeFileSync(jsonPath, JSON.stringify(variablesFiltered));
    });
  }
});
