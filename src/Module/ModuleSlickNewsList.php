<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle;

use Contao\BackendTemplate;
use Contao\ModuleNewsList;
use Contao\System;
use HeimrichHannot\SlickBundle\Asset\FrontendAsset;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;
use Patchwork\Utf8;

class ModuleSlickNewsList extends ModuleNewsList
{
    const TYPE = 'slick_newslist';

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'mod_newslist';

    public function generate()
    {
        if (System::getContainer()->get('huh.utils.container')->isBackend()) {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['newslist'][0]).' ###';
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
        if (isset($GLOBALS['TL_HOOKS']['compileSlickNewsList']) && is_array($GLOBALS['TL_HOOKS']['compileSlickNewsList'])) {
            foreach ($GLOBALS['TL_HOOKS']['compileSlickNewsList'] as $callback) {
                $this->import($callback[0]);
                $this->{$callback[0]}->{$callback[1]}($this->Template, $this, $this->objModel);
            }
        }
    }
}
