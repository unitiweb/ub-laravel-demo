const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')
const config = require('./webpack.config')

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

/**
 * Mix in the JavaScript
 */
mix.js('resources/js/app.js', 'public/js').sourceMaps()

/**
 * Mix in the StyleSheets
 */
mix.sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js') ],
    })
    .webpackConfig(config)
