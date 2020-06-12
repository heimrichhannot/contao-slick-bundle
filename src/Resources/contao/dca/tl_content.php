<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 *
 * @package slick
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dc = &$GLOBALS['TL_DCA']['tl_content'];

/**
 * Palettes
 */
$dc['palettes']['slick-content-start'] = '{type_legend},type,headline;{slick_config},slickConfig;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$dc['palettes']['slick-content-stop']  = '{type_legend},type,headline;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$dc['palettes']['slick-slide-start'] = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$dc['palettes']['slick-slide-stop']  = '{type_legend},type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$dc['palettes']['slick-nav-start'] = '{type_legend},type,headline;{slick_config},slickConfig,slickContentSlider;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$dc['palettes']['slick-nav-stop']  = '{type_legend},type,headline;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

$dc['palettes']['slick-slide-separator'] = '{type_legend},type,headline;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$dc['palettes']['slick-nav-separator']   = '{type_legend},type,headline;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';


$dc['fields']['customTpl']['load_callback'][] = [\HeimrichHannot\SlickBundle\DataContainer\ContentContainer::class, 'onCustomTplLoad'];

$dc['fields']['slickContentSlider'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_content']['slickContentSlider'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => [\HeimrichHannot\SlickBundle\DataContainer\ContentContainer::class, 'getContentSliderCarousels'],
    'eval'             => ['tl_class' => 'w50', 'mandatory' => true],
    'wizard'           => [
        ['tl_content', 'editAlias'],
    ],
    'sql'              => "varchar(64) NOT NULL default ''",
];