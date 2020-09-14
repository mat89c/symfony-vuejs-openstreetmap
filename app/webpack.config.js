var Encore = require('@symfony/webpack-encore');

const eslintrc = require('./.eslintrc.js')
const path = require('path');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/login')
    .setPublicPath('/build/login')

    .addEntry('login', './assets/js/login.js')

    .splitEntryChunks()

    .disableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .enableVueLoader(() => {}, { runtimeCompilerBuild: false })
    .enableEslintLoader(options => {
      return eslintrc
    })

    // enables Sass/SCSS support
    .enableSassLoader(options => {
      options.implementation = require('sass')
      options.sassOptions.fiber = require('fibers')
    })
;

const login = Encore.getWebpackConfig();
login.name = 'login';

Encore
    .setOutputPath('public/build/dashboard')
    .setPublicPath('/build/dashboard')

    .addEntry('dashboard', './assets/js/app.js')

    .splitEntryChunks()

    .disableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .copyFiles({
        from: './assets/images',
        to: '../uploads/[path][name].[ext]'
    })

    .enableVueLoader(() => {}, { runtimeCompilerBuild: false })
    .enableEslintLoader(options => {
      return eslintrc
    })

    // enables Sass/SCSS support
    .enableSassLoader(options => {
      options.implementation = require('sass')
      options.sassOptions.fiber = require('fibers')
    })
;

const dashboard = Encore.getWebpackConfig();
dashboard.name = 'dashboard';

module.exports = [login, dashboard];
