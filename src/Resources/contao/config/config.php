<?php

/**
 * Constants
 */
define('SLICK_PALETTE_DEFAULT', 'default');
define('SLICK_PALETTE_FLAT', 'flat');
define('SLICK_PALETTE_PRESETCONFIG', 'presetConfig');
define('SLICK_PALETTE_GALLERY', 'gallery');
define('SLICK_PALETTE_CONTENT', 'slick');
define('SLICK_PALETTE_CONTENT_SLIDER_START', 'slick-content-start');
define('SLICK_PALETTE_CONTENT_SLIDER_END', 'slick-content-end');

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = ['huh.slick.event_listener.hook_listener', 'onLoadDataContainer'];
$GLOBALS['TL_HOOKS']['parseArticles'][]     = ['huh.slick.event_listener.hook_listener', 'onParseArticles'];

/**
 * Supported TL_DCA Entities, spreading efa palette to
 */
// News support
$GLOBALS['TL_SLICK']['SUPPORTED']['tl_module']['slick_newslist'] = 'type;[[SLICK_PALETTE_PRESETCONFIG]]';
$GLOBALS['TL_SLICK']['SUPPORTED']['tl_news_archive']['default']  = '[[SLICK_PALETTE_PRESETCONFIG]]{comments_legend';
$GLOBALS['TL_SLICK']['SUPPORTED']['tl_news']['default']          = '[[SLICK_PALETTE_GALLERY]]{enclosure_legend';

// Event support
$GLOBALS['TL_SLICK']['SUPPORTED']['tl_module']['slick_eventlist'] = 'type;[[SLICK_PALETTE_PRESETCONFIG]]';

// Content support
$GLOBALS['TL_SLICK']['SUPPORTED']['tl_content']['slick-slider']        = '[[SLICK_PALETTE_CONTENT]]';
$GLOBALS['TL_SLICK']['SUPPORTED']['tl_content']['slick-content-start'] = '[[SLICK_PALETTE_CONTENT_SLIDER_START]]';

// Owl carousel config support
$GLOBALS['TL_SLICK']['SUPPORTED']['tl_slick_config']['default']      = 'title;[[SLICK_PALETTE_FLAT]]';
$GLOBALS['TL_SLICK']['SUPPORTED']['tl_module']['slick_combinedlist'] = 'type;[[SLICK_PALETTE_PRESETCONFIG]]';

/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['system'], 1, [

    'slick_config' => [
        'tables' => ['tl_slick_config'],
    ],
]);


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['news'][\HeimrichHannot\SlickBundle\ModuleSlickNewsList::TYPE]    =
    \HeimrichHannot\SlickBundle\ModuleSlickNewsList::class;
$GLOBALS['FE_MOD']['events'][\HeimrichHannot\SlickBundle\ModuleSlickEventList::TYPE] =
    \HeimrichHannot\SlickBundle\ModuleSlickEventList::class;

/**
 * Content elements
 */
array_insert($GLOBALS['TL_CTE'], 3, [
    'slick' => [
        \HeimrichHannot\SlickBundle\Element\SlickImageSliderElement::TYPE =>
            \HeimrichHannot\SlickBundle\Element\SlickImageSliderElement::class,
        'slick-content-start'   => 'HeimrichHannot\SlickBundle\Element\ContentSlickContentStart',
        'slick-slide-separator' => 'HeimrichHannot\SlickBundle\Element\ContentSlickSlideSeparator',
//        'slick-slide-start'     => 'HeimrichHannot\SlickBundle\Element\ContentSlickSlideStart',
//        'slick-slide-stop'      => 'HeimrichHannot\SlickBundle\Element\ContentSlickSlideStop',
        'slick-content-stop'    => 'HeimrichHannot\SlickBundle\Element\ContentSlickContentStop',
        'slick-nav-start'       => 'HeimrichHannot\SlickBundle\Element\ContentSlickNavStart',
        'slick-nav-separator'   => 'HeimrichHannot\SlickBundle\Element\ContentSlickNavSlideSeparator',
        'slick-nav-stop'        => 'HeimrichHannot\SlickBundle\Element\ContentSlickNavStop',
    ],
]);

/**
 * Intend elements
 */
$GLOBALS['TL_WRAPPERS']['start'][]     = 'slick-content-start';
$GLOBALS['TL_WRAPPERS']['start'][]     = 'slick-slide-start';
$GLOBALS['TL_WRAPPERS']['start'][]     = 'slick-nav-start';
$GLOBALS['TL_WRAPPERS']['stop'][]      = 'slick-content-stop';
$GLOBALS['TL_WRAPPERS']['stop'][]      = 'slick-slide-stop';
$GLOBALS['TL_WRAPPERS']['stop'][]      = 'slick-nav-stop';
$GLOBALS['TL_WRAPPERS']['separator'][] = 'slick-slide-separator';
$GLOBALS['TL_WRAPPERS']['separator'][] = 'slick-nav-separator';

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_slick_config'] = 'HeimrichHannot\SlickBundle\Model\SlickConfigModel';

/**
 * Modal module configuration
 */
$GLOBALS['MODAL_MODULES']['slick_newslist'] = [
    'invokePalette' => 'customTpl;',
];

