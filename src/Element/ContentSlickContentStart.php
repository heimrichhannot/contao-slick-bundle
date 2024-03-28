<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Element;

use Contao\BackendTemplate;
use Contao\ContentElement;
use Contao\System;
use HeimrichHannot\SlickBundle\Asset\FrontendAsset;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;
use HeimrichHannot\UtilsBundle\Util\Utils;

class ContentSlickContentStart extends ContentElement
{
    const TYPE = 'slick-content-start';
    const TEMPLATE = 'ce_slick_content_start';

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = ContentSlickContentStart::TEMPLATE;

    public function generate()
    {
        $utils = System::getContainer()->get(Utils::class);
        if ($utils->container()->isBackend()) {
            $this->strTemplate = 'be_wildcard';
            $this->Template = new BackendTemplate($this->strTemplate);
            $this->Template->title = $this->headline;
        }

        parent::generate();

        if (!$this->slickConfig) {
            return '';
        }

        $objConfig = SlickConfigModel::findByPk($this->slickConfig);

        if (null === $objConfig) {
            return '';
        }

        $this->Template->class .= ' '.System::getContainer()->get('huh.slick.config')->getCssClassFromModel($objConfig);
        $this->Template->attributes .= System::getContainer()->get('huh.slick.config')->getAttributesFromModel($objConfig);
        
        System::getContainer()->get(FrontendAsset::class)->addFrontendAssets();

        return $this->Template->parse();
    }

    /**
     * Generate the content element.
     */
    protected function compile()
    {
    }
}
