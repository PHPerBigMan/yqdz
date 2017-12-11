let mix = require('laravel-mix');

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
    .sass('resources/assets/sass/app.scss', 'public/css')
    .styles([
        'node_modules/normalize.css/normalize.css',
        'node_modules/swiper/dist/css/swiper.min.css',
        'resources/assets/css/weui.css',
        'public/frontend/mobile.css',
    ], 'public/frontend/all.css')
        .browserSync({
            proxy: 'localhost:8000',
            notify: false,
            files: [
                'app/**/*.php',
                'resources/views/**/*.php',
                'public/frontend/*.js',
                'public/frontend/*.css'
            ]});

    if (mix.config.inProduction) {
        mix.version();
    }
