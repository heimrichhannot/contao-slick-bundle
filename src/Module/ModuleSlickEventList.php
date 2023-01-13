<?php

/*
 * Copyright (c) 2023 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Module;

use Contao\BackendTemplate;
use Contao\ModuleEventlist;
use Contao\System;
use HeimrichHannot\SlickBundle\Asset\FrontendAsset;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;
use Patchwork\Utf8;

class ModuleSlickEventList extends ModuleEventlist
{
    const TYPE = 'slick_eventlist';

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'mod_eventlist';

    public function __construct($objModule, $strColumn = 'main')
    {
        parent::__construct($objModule, $strColumn);
    }

    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['eventlist'][0]).' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        parent::generate();

        if ($this->slickConfig > 0 && null !== ($objConfig = SlickConfigModel::findByPk($this->slickConfig))) {
            $this->Template->class .= ' '.System::getContainer()->get('huh.slick.config')->getCssClassFromModel($objConfig);
            $this->Template->attributes .= System::getContainer()->get('huh.slick.config')->getAttributesFromModel($objConfig);
            System::getContainer()->get(FrontendAsset::class)->addFrontendAssets();
        }

        return $this->Template->parse();
    }

    /**
     * Generate the module.
     */
    protected function compile()
    {
        parent::compile();

        // HOOK: add custom logic
        if (isset($GLOBALS['TL_HOOKS']['compileSlickEventList']) && \is_array($GLOBALS['TL_HOOKS']['compileSlickEventList'])) {
            foreach ($GLOBALS['TL_HOOKS']['compileSlickEventList'] as $callback) {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($this->Template, $this, $this->objModel);
            }
        }
    }
}
