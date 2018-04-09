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

namespace HeimrichHannot\SlickBundle\Element;


use Contao\System;

class ContentSlickNavStart extends \ContentElement
{

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'ce_slick_nav_start';


    public function generate()
    {
        if (System::getContainer()->get('huh.utils.container')->isBackend()) {
            $this->strTemplate     = 'be_wildcard';
            $this->Template        = new \BackendTemplate($this->strTemplate);
            $this->Template->title = $this->headline;
        }

        parent::generate();

        if (!$this->slickConfig) {
            return '';
        }
        $container = System::getContainer();

        $objConfig = $container->get('huh.slick.model.config')->findByPk($this->slickConfig);

        if ($objConfig === null) {
            return '';
        }


        if (!$this->slickContentSlider) {
            return '';
        }

        if (($objSlider = \ContentModel::findByPk($this->slickContentSlider)) === null) {
            return '';
        }

        $this->Template->class      .= ' ' . System::getContainer()->get('huh.slick.config')->getCssClassFromModel($objConfig);
        $this->Template->attributes .= System::getContainer()->get('huh.slick.config')->getAttributesFromModel($objConfig);
        $this->Template->syncTo     = $container->get('huh.slick.config')->getCssClassForContent($this->slickContentSlider);


        return $this->Template->parse();
    }

    /**
     * Generate the content element
     */
    protected function compile()
    {
    }
}