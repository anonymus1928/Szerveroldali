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

/**
 * FontAwesome telepítése
 * npm i --save-dev @fortawesome/fontawesome-free   // A laravel-mix elvégzi a build építését, ezért elegendő a 'save-dev'
 * Az alábbi 3 'copy' sorral mondjuk meg a laravel-mix-nek, hogy tegye ki a public mappába őket.
 * Végezetül a base.blade.php file head-jében a fontawesome link-jét át kell írni a public/fontawesome/css/all.min.css-re.
 * Ezt követően futtassuk a laravel-mix-et: 'npm run prod' vagy 'npm run dev'
 */


mix
    .copy('node_modules/@fortawesome/fontawesome-free/css',      'public/fontawesome/css')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/fontawesome/webfonts')
    .copy('node_modules/@fortawesome/fontawesome-free/svgs',     'public/fontawesome/svgs')
    
    .js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
