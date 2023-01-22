const mix = require("laravel-mix");
const path = require("path");
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

mix.alias({
    "~": path.join(__dirname, "resources/js"),
    vue$: path.join(__dirname, "node_modules/vue/dist/vue.esm-bundler.js"),
});

mix.js("resources/js/app.js", "public/js")
    .vue()
    .sass("resources/sass/app.scss", "public/css");

mix.webpackConfig({
    devServer: {
        proxy: {
            "*": "http://localhost:8000",
        },
    },
});
