var elixir = require("laravel-elixir");

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir.config.sourcemaps = false;

elixir(function(mix) {

    mix
        // genereer de css obv de less:
        .less('app.less', 'resources/assets/css')
        .styles(
            // combineer deze bronbestanden
            [
                'app.css', // het app.less bestand gecompiled
            ],
            // in dit bestand
            'public/css/all.css',
            // hier staan de bronbestanden
            'resources/assets/css'
        )
        .scripts(
            // combineer deze bronbestanden
            [
                'bootstrap.min.js',
                'ie10-viewport-bug-workaround.js',
                
                'bootstrap-select/bootstrap-select.js',
                'framework.js',
                'app.js',
            ],
            // in dit bestand
            'public/js/all.js',
            // hier staan de bronbestanden
            'resources/assets/js'
        )
       .copy(
            'resources/assets/js/bootstrap-select/i18n',
            'public/js/bootstrap-select/i18n'
        )
       .copy(
            'resources/assets/fonts',
            'public/fonts'
        )
       ;
    /*
    mix
        .scripts(
            // combineer deze bronbestanden
            [
                'bootstrap.min.js',
            ],
            // in dit bestand
            'public/js/bare.js',
            // hier staan de bronbestanden
            'resources/assets/js'
        )
        ;
    */

    //mix.version(["css/all.css", "js/all.js", "js/bare.js"]);

});
