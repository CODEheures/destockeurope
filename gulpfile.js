const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.copy(
            'node_modules/semantic-ui-css/semantic.css', 'resources/assets/sass/_semantic.scss'
    )
        .copy(
            'node_modules/semantic-ui-css/semantic.js',  'resources/assets/js/semantic.js'
        )
       .sass('app.scss')
       .webpack('app.js');
});
