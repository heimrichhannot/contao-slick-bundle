<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Element;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\ContentModel;
use Contao\System;
use HeimrichHannot\SlickBundle\Asset\FrontendAsset;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;

class ContentSlickNavStart extends ContentElement
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'ce_slick_nav_start';

    public function generate()
    {
        if (System::getContainer()->get('huh.utils.container')->isBackend()) {
            $this->strTemplate = 'be_wildcard';
            $this->Template = new BackendTemplate($this->strTemplate);
            $this->Template->title = $this->headline;
        }

        parent::generate();

        if (!$this->slickConfig) {
            return '';
        }
        System::getContainer()->get(FrontendAsset::class)->addFrontendAssets();
        $container = System::getContainer();

        $objConfig = SlickConfigModel::findByPk($this->slickConfig);

        if (null === $objConfig) {
            return '';
        }

        if (!$this->slickContentSlider) {
            return '';
        }

        if (null === ($objSlider = ContentModel::findByPk($this->slickContentSlider))) {
            return '';
        }

        $this->Template->class .= ' '.System::getContainer()->get('huh.slick.config')->getCssClassFromModel($objConfig);
        $this->Template->attributes .= System::getContainer()->get('huh.slick.config')->getAttributesFromModel($objConfig);

        return $this->Template->parse();
    }

    /**
     * Generate the content element.
     */
    protected function compile()
    {
    }
}
