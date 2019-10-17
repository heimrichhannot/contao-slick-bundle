# Slick

A content slider/carousel for contao based on [kenwheelers slick carousel](http://kenwheeler.github.io/slick/).

## Features

- global carousel configurations
- frontend modules for news and events
- content elements for images galleries and other content elements
- responsive
- [Encore Bundle](https://github.com/heimrichhannot/contao-encore-bundle) support
- [List Bundle](https://github.com/heimrichhannot/contao-list-bundle) support

## Usage

### Install

1. Install via composeer or contao manager: 
    ```
    composer require heimrichhannot/contao-slick-bundle
    ```
2. Call contao install tool and update the database

The bundle has no default styling for slick slider. If your want to use the slick default theme, please see the slick theme section.

### Setup

1. Create an slick configuration under System -> Slick configuration. Please consider the slick-carousel documentation for information about the different config options.
2. Select the created config in a slick frontend module, a slick content element or in your list config (Misc -> Add slick).

### Slick theme

If you want to use the default theme comes with the slick carousel, you need to add the slick theme css. If you use slick together with **encore bundle**, you just need to select the `contao-slick-bundle-theme` entry within layout or page setting. If you use the default contao asset managment, you need to add the `slick-theme.css` to the global asset array:

```php
$GLOBALS['TL_USER_CSS']['slick-theme'] = 'assets/slick/slick/slick-theme.css';
```

### Hooks

Hook                  | Description
--------------------- | -----------
compileSlickNewsList  | Modify slick news frontend module output.
compileSlickEventList | Modify slick event frontend module output.