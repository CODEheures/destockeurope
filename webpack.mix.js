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

mix// .js('resources/assets/js/sw.js', 'public')
  .webpackConfig({
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          loader: 'babel-loader'
        }
      ]
    }
  })
  .js('resources/assets/js/app.js', 'public/js')
  // .extract(['vue', 'vuex', 'vue-focus', 'lodash', 'moment', 'url', 'axios', 'swiper', '@vimeo/player'])
  .sass('resources/assets/sass/pdf.scss', 'public/css')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .combine([
    'resources/assets/css/motionControlFont.css',
    'resources/assets/css/swiper.css',
    'resources/assets/ripple/ripple.css'
  ], 'public/css/vendor.css')
  .copy('resources/assets/pace/pace.min.js', 'public/js/pace.min.js')
  .copy('resources/assets/pace/pace-theme-flash.css', 'public/css/pace-theme.css')
  .copy('resources/assets/semantic/dist/semantic.min.js', 'public/js/semantic.min.js')
  .copy('resources/assets/semantic/dist/semantic.min.css', 'public/css/semantic.min.css')
  .copy('resources/assets/semantic/dist/themes/', 'public/css/themes', false)

if (mix.inProduction()) {
  mix.version()
}
