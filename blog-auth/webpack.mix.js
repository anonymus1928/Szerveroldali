const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .copy('node_modules/@fortawesome/fontawesome-free/css', 'public/fontawesome/css')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/fontawesome/webfonts')
    .copy('node_modules/@fortawesome/fontawesome-free/svgs', 'public/fontawesome/svgs')

    .js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);
