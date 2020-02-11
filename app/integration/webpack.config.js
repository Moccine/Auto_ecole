var path = require('path');
var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');
var appWeb = path.resolve(__dirname, '../symfony/public/build');

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath('../symfony/public/build/')

  // the public path used by the web server to access the previous directory
  .setPublicPath('/build')



    // will create public/build/app.js and public/build/app.css
    .addEntry('main', './js/main.js')


      // will output as web/build/theme.min.css
      .addStyleEntry('main.min', './sass/main.scss')


      // allow less files to be processed
      .enableSassLoader()

      // post css
      .enablePostCssLoader((options) => {
        options.config = {
          path: ' postcss.config.js'
        };
      })

      // will require an extra script tag for runtime.js
      // but, you probably want this, unless you're building a single-page app
      .enableSingleRuntimeChunk()

      // allow legacy applications to use $/jQuery as a global variable
      .autoProvidejQuery()

      // empty the outputPath dir before each build
      .cleanupOutputBeforeBuild()

      // enables hashed filenames (e.g. app.abc123.css)
      .enableSourceMaps(!Encore.isProduction())
      .enableVersioning(Encore.isProduction())

      // show OS notifications when builds finish/fail
      .enableBuildNotifications()

      // Copy vendor
      .addPlugin(
        new CopyWebpackPlugin([
          // Material Design
           { context: 'node_modules', from: 'material-design-iconic-font/dist/css/*.*', to: appWeb+'/vendors/' },
           { context: 'node_modules', from: 'material-design-iconic-font/dist/fonts/*.*', to: appWeb+'/vendors/' },

          // Paymentfont
          {context: 'node_modules', from: 'paymentfont/fonts/*.*', to: appWeb + '/vendors/'},
        ])
      )
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
