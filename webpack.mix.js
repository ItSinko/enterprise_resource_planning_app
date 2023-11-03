const mix = require('laravel-mix');

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

// mix.js('resources/js/app.js', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');

// plugin library
mix.js('resources/js/bootstrap.js', 'public/native/js/plugin.js')
mix.sass('resources/css/bootstrap.scss', 'public/native/css/plugin.css')

// ppic
// mix.js('resources/js/ppic/jadwal/app.js', 'public/native/js/ppic/jadwal.js').vue()
// mix.js('resources/js/ppic/data/app.js', 'public/native/js/ppic/data.js').vue()
// mix.js('resources/js/ppic/dashboard/app.js', 'public/native/js/ppic/dashboard.js').vue()
// mix.js('resources/js/manager/app.js', 'public/native/js/ppic/manager.js').vue()
mix.js('resources/js/login/index.js', 'public/native/js/login.js').vue()
mix.js('resources/js/ppic_spa/index.js', 'public/native/js/ppic_spa.js').vue()
mix.js('resources/js/manager_spa/index.js', 'public/native/js/manager_spa.js').vue()
mix.js('resources/js/direksi/index.js', 'public/native/js/direksi.js').vue();
mix.js('resources/js/produksi/app.js', 'public/native/js/produksi.js').vue();
mix.js('resources/js/emiindo/index.js', 'public/native/js/emiindo.js').vue();
mix.js('resources/js/it/app.js', 'public/native/js/it.js').vue();
mix.js('resources/js/meeting/app.js', 'public/native/js/meeting.js').vue();
mix.js('resources/js/hr/app.js', 'public/native/js/hr.js').vue();
mix.js('resources/js/gbj/app.js', 'public/native/js/gbj.js').vue();
mix.js('resources/js/produksiNew/app.js', 'public/native/js/produksinew.js').vue();

// mix.browserSync("http://localhost:8000")
