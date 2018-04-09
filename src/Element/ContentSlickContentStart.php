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

class ContentSlickContentStart extends \ContentElement
{

    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'ce_slick_content_start';


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
        $slickConfig = $container->get('huh.slick.config');

        $this->Template->class  .= ' ' . $slickConfig->getCssClassFromModel($objConfig) . ' slick ' . $slickConfig->getCssClassForContent($this->id);
        $this->Template->syncid = $slickConfig->getCssClassForContent($this->id);

        return $this->Template->parse();
    }

    /**
     * Generate the content element
     */
    protected function compile()
    {
    }
}