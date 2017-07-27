var Encore = require('@symfony/webpack-encore');

Encore
// directory where all compiled assets will be stored
    .setOutputPath('web/build/')
    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/build')
    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()
    // will output as web/build/app.js
    .addEntry('admin', './assets/js/admin.js')
    // will output as web/build/global.css
    .addStyleEntry('admin-style', './assets/css/admin.scss')
    // allow sass/scss files to be processed
    .enableSassLoader()
    .autoProvidejQuery()
    // allow legacy applications to use $/jQuery as a global variable
    .enableSourceMaps(!Encore.isProduction())
;

// export the final configuration
module.exports = Encore.getWebpackConfig();