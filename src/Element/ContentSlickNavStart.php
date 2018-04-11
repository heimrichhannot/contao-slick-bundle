<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Element;

use Contao\System;

class ContentSlickNavStart extends \ContentElement
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
            $this->Template = new \BackendTemplate($this->strTemplate);
            $this->Template->title = $this->headline;
        }

        parent::generate();

        if (!$this->slickConfig) {
            return '';
        }
        $container = System::getContainer();

        $objConfig = $container->get('huh.slick.model.config')->findByPk($this->slickConfig);

        if (null === $objConfig) {
            return '';
        }

        if (!$this->slickContentSlider) {
            return '';
        }

        if (null === ($objSlider = \ContentModel::findByPk($this->slickContentSlider))) {
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
