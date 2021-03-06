# Changelog

## [1.5.0] - 2020-09-08
- updated js dependencies
- removed non functional randomActive config (#30)

## [1.4.4] - 2020-07-23
- fixed jquery dependency for building process (now supports same jquery versions as slick library)

## [1.4.3] - 2020-07-17
- fixed undefined method exception when adding a slick gallery to news in contao 4.9 (#28)

## [1.4.2] - 2020-06-12
- fixed customTpl field not showing templates named like default template for slick content start element

## [1.4.1] - 2020-04-18
- restored ie support through polyfills

## [1.4.0] - 2020-04-14
- refactored js code to es6
- added new events
- removed unnecessary jquery code (jquery still needed to call core slick functions)
- deprecated slickInitCallback and afterInitCallback
- deprecated bootstrapper support in js code
- updated encore bundle config
- raised minimum encore bundle version to 1.5

## [1.3.1] - 2020-03-27
- updated readme
- removed unused import
- fixed a namespace

## [1.3.0] - 2020-01-14
- made contao news and calendar bundle an optional dependency (#20, #21)
- fix possible install error (#22)
- added missing translations (#19)
- some refactoring 

## [1.2.2] - 2019-10-25
- fixed FrontentAsset service not public (#16)(#17) - thanks to fritzmg 

## [1.2.1] - 2019-10-17
- fixed ClassNotFoundException

## [1.2.0] - 2019-10-17
- added list bundle support (integrated contao-slick-list-bundle)
- replace contao-slick and contao-slick-list-bundle via composer.json
- replaces `huh.slick.model.config` service calls with direct model calls
- deprecated `huh.slick.model.config`

## [1.1.1] - 2019-10-16
- fixed svg image files not working with content elements (#15)

## [1.1.0] - 2019-10-16
- added support for dynamically adding entrypoints of contao encore bundle 1.3
- legacy contao assets are only added to global array when needed
- refactored legacy asset generation to encore (internal process)
- fixed assets not loading when not using encore
- fixed some deprecations

## [1.0.1] - 2019-08-08
- removed symfony framework bundle dependency

## [1.0.0] - 2019-08-08
- fixed frontend assets not included in newer contao versions (#9, #13)