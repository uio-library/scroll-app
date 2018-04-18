let mix = require('laravel-mix');
require('laravel-mix-auto-extract');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.sass', 'public/css');

mix.autoExtract();

   //.extract(['vue', 'bootstrap', 'bootstrap-vue', 'vue-resource', 'vue-observe-visibility'])
   // .extract(['vue', 'bootstrap', 'bootstrap-vue', 'vue-resource', 'vue-observe-visibility'])
   ;

if (mix.inProduction()) {
    mix.version();
} else {
   // mix.sourceMaps()  // While useful, this adds megabytes of overhead!
}

