const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/sw.js', 'public/js')
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/pdf.scss', 'public/css')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .version(['css/app.css','js/app.js']);
