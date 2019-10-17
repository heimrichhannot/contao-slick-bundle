<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\SlickBundle\EventListener;


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
     * Add fields to tl_list_config if list bundle is installed
     */
    protected function addListConfigDcaFields()
    {
        $dca = &$GLOBALS['TL_DCA']['tl_list_config'];

        /**
         * Palettes
         */
        $dca['palettes']['default'] = str_replace('addMasonry', 'addMasonry,addSlick', $dca['palettes']['default']);

        /**
         * Subpalettes
         */
        $dca['palettes']['__selector__'][] = 'addSlick';
        $dca['subpalettes']['addSlick'] = 'slickConfig';

        /**
         * Fields
         */
        $fields = [
            'addSlick' => [
                'label'                   => &$GLOBALS['TL_LANG']['tl_list_config']['addSlick'],
                'exclude'                 => true,
                'inputType'               => 'checkbox',
                'eval'                    => ['tl_class' => 'w50', 'submitOnChange' => true],
                'sql'                     => "char(1) NOT NULL default ''"
            ],
            'slickConfig' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_list_config']['slickConfig'],
                'exclude'    => true,
                'filter'     => true,
                'inputType'  => 'select',
                'foreignKey' => 'tl_slick_config.title',
                'eval'       => ['tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true, 'submitOnChange' => true],
                'sql'        => "int(10) unsigned NOT NULL default '0'"
            ],
        ];
        $dca['fields'] = array_merge($fields, is_array($dca['fields']) ? $dca['fields'] : []);
    }
}