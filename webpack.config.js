var Encore = require('@symfony/webpack-encore');

Encore
.setOutputPath('src/Resources/public/assets/')
.addEntry('contao-slick-bundle', './src/Resources/assets/js/contao-slick-bundle.js')
.setPublicPath('/bundles/heimrichhannotcontaoslick/assets')
.setManifestKeyPrefix('bundles/heimrichhannotcontaoslick/assets')
.disableSingleRuntimeChunk()
.addExternals({
    'slick-carousel': {
        root: '_'
    },
    'jquery': 'jQuery'
})
.enableSassLoader()
.enablePostCssLoader()
.enableSourceMaps(!Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();