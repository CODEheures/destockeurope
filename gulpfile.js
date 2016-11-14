const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

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

// elixir(mix => {
//     mix.copy('resources/assets/semantic/dist/semantic.css', 'resources/assets/sass/_semantic.scss')
//        .copy('resources/assets/semantic/dist/semantic.js',  'resources/assets/js/semantic.js')
//        .sass('app.scss')
//        .webpack('app.js');
// });


////////////////////////////////////////////////////////////////////////////////////////
//                     A RAJOUTER DANS LE WATCHER DE SEMANTIC UI
//copy css et js dans le dossier watché par webpack
// gulp
//     .watch([
//         output.packaged   + '/semantic.css'
//     ], function(file) {
//         gulp.src(file.path)
//             .pipe(rename({
//                 basename: "_semantic",
//                 extname: '.scss'
//             }))
//             .pipe(gulp.dest(output.packaged));
//     })
// ;

elixir(mix => {
    mix.sass('app.scss')
        .webpack('app.js');
});
