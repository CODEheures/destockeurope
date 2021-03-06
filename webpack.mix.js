let mix = require('laravel-mix')

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

mix.options({
  uglify: {
    uglifyOptions: {
      ie8: false,
      ecma: 5,
      mangle: {
        safari10: true
      }
    }
  }
})

mix// .js('resources/assets/js/sw.js', 'public')
  .js('resources/assets/js/app.js', 'public/js')
  .extract(['vue', 'vue-focus', 'jquery', 'lodash', 'moment', 'url', 'axios', 'swiper', './resources/assets/semantic/dist/semantic', 'ion-rangeslider', '@vimeo/player', 'amcharts3'])
  .autoload({
    jquery: ['$', 'window.jQuery', 'jQuery']
  })
  .sass('resources/assets/sass/pdf.scss', 'public/css')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .combine([
    'resources/assets/css/motionControlFont.css',
    'resources/assets/semantic/dist/semantic.min.css',
    'resources/assets/css/ion.rangeSlider.css',
    'resources/assets/css/ion.rangeSlider.skinFlat.css',
    'resources/assets/css/swiper.css',
    'resources/assets/ripple/ripple.css'
  ], 'public/css/vendor.css')
  .copy('resources/assets/js/start.js', 'public/js')
  .copy('resources/assets/js/errors.js', 'public/js')
  .copy('resources/assets/pace/pace.min.js', 'public/js/pace.min.js')
  .copy('resources/assets/pace/pace-theme-flash.css', 'public/css/pace-theme.css')
  .copy('node_modules/amcharts3/amcharts/images', 'public/images/amcharts3', false)

if (mix.inProduction()) {
  mix.version()
}
