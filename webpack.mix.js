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

mix//.js('resources/assets/js/sw.js', 'public')
    .js('resources/assets/js/app.js', 'public/js')
    .extract(['vue', 'vue-focus', 'jquery', 'lodash', 'moment', 'url', 'axios', 'swiper', 'ion-rangeslider', '@vimeo/player'])
    .sass('resources/assets/sass/pdf.scss', 'public/css')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .combine([
        'resources/assets/css/motionControlFont.css',
        'resources/assets/semantic/dist/semantic.min.css',
        'resources/assets/css/ion.rangeSlider.css',
        'resources/assets/css/ion.rangeSlider.skinFlat.css',
        'resources/assets/css/swiper.css',
    ], 'public/css/vendor.css');

if (mix.inProduction()) {
    mix.version();
}

