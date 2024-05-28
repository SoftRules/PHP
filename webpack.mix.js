const mix = require('laravel-mix');

mix
    .js('resources/js/softRules.js', 'public/js/softRules.js')
    .sass('resources/css/SoftRules.scss', 'resources/css/SoftRules.css')
    .styles([
        'resources/css/rSlider.min.css',
        'resources/css/SoftRules.css',
    ], 'public/css/SoftRules.css');

mix.sourceMaps();
mix.disableNotifications();
