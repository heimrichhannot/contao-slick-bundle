# Changelog


## [1.1.0] - 2019-10-15

- added support for dynamically adding entrypoints of contao encore bundle 1.3
- legacy contao assets are only added to global array when needed
- refactored legacy asset generation to encore (internal process)
- fixed assets not loading when not using encore
- fixed some deprecations


## [1.0.1] - 2019-08-08
- removed symfony framework bundle dependency


## [1.0.0] - 2019-08-08
- fixed frontend assets not included in newer contao versions (#9, #13)