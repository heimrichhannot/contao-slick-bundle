<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2015 Heimrich & Hannot GmbH
 * @package slick
 * @author Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\SlickBundle\Element;


use Contao\System;

class ContentSlickContentStop extends \ContentElement
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_slick_content_stop';


    /**
     * Generate the content element
     */
    protected function compile()
    {
        if (System::getContainer()->get('huh.utils.container')->isBackend()) {
            $this->strTemplate = 'be_wildcard';
            $this->Template    = new \BackendTemplate($this->strTemplate);
        }
    }
}