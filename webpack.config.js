var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    .addEntry('main',     './assets/js/main.js')
    .addEntry('create',     './assets/js/create.js')
    .addEntry('persona',     './assets/js/persona.js')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    //.enableSingleRuntimeChunk()
    .disableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())

    .enableVersioning(Encore.isProduction())

    .copyFiles({
          from: './assets/images',
          // optional target path, relative to the output dir
          to: './images/[path][name].[ext]',
          // only copy files matching this pattern
          pattern: /\.(png|jpg|jpeg|svg)$/,
    })

    .autoProvidejQuery()

    //enables hashed filenames (e.g. app.abc123.css)
    .configureFilenames({
        images: '[path][name].[hash:8].[ext]'
    })

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // uncomment if you're having problems with a jQuery plugin
    .enableBuildNotifications();

// fetch the config
var config = Encore.getWebpackConfig();

config.watchOptions = {
    poll: true,
    ignored: /node_modules/
};

config.resolve = {
    symlinks: false
};

config.module.rules.push({
    test: /\.js$/,
    exclude: /node_module/,
    use: ['babel-loader']
});

// export the final config
module.exports = config;