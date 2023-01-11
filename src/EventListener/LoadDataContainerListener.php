<?php

/*
 * Copyright (c) 2023 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\EventListener;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use HeimrichHannot\ListBundle\HeimrichHannotContaoListBundle;

class LoadDataContainerListener
{
    public function onLoadDataContainer(string $table): void
    {
        switch ($table) {
            case 'tl_list_config':
                $this->addListConfigDcaFields();

                return;
        }
    }

    /**
     * Add fields to tl_list_config if list bundle is installed.
     */
    protected function addListConfigDcaFields()
    {
        if (!class_exists(HeimrichHannotContaoListBundle::class)) {
            return;
        }

        $dca = &$GLOBALS['TL_DCA']['tl_list_config'];

        PaletteManipulator::create()
            ->addField('addSlick', 'misc_legend')
            ->applyToPalette('default', 'tl_list_config')
            ;

        $dca['palettes']['__selector__'][] = 'addSlick';
        $dca['subpalettes']['addSlick'] = 'slickConfig';

        $fields = [
            'addSlick' => [
                'label' => &$GLOBALS['TL_LANG']['tl_list_config']['addSlick'],
                'exclude' => true,
                'inputType' => 'checkbox',
                'eval' => ['tl_class' => 'w50', 'submitOnChange' => true],
                'sql' => "char(1) NOT NULL default ''",
            ],
            'slickConfig' => [
                'label' => &$GLOBALS['TL_LANG']['tl_list_config']['slickConfig'],
                'exclude' => true,
                'filter' => true,
                'inputType' => 'select',
                'foreignKey' => 'tl_slick_config.title',
                'eval' => ['tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true, 'submitOnChange' => true],
                'sql' => "int(10) unsigned NOT NULL default '0'",
            ],
        ];
        $dca['fields'] = array_merge($fields, \is_array($dca['fields']) ? $dca['fields'] : []);
    }
}
