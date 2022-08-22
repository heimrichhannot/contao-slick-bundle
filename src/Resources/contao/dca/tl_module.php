<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

$dca = &$GLOBALS['TL_DCA']['tl_module'];

/*
 * Callbacks
 */
$dca['config']['onload_callback']['slick'] = ['HeimrichHannot\SlickBundle\Backend\Module', 'modifyDC'];

/*
 * Palettes
 */
$dca['palettes']['slick_newslist'] = $dca['palettes']['newslist'] ?? '';
$dca['palettes']['slick_eventlist'] = $dca['palettes']['eventlist'] ?? '';
$dca['palettes']['newsreader'] = str_replace(
    'news_archives',
    'news_archives,useSlickGallery',
    ($dca['palettes']['newsreader'] ?? '')
);

/*
 * Fields
 */
$dca['fields']['useSlickGallery'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['useSlickGallery'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default '1'",
];
