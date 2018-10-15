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

class ContentSlickNavStop extends ContentElement
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'ce_slick_nav_stop';

    /**
     * Generate the content element.
     */
    protected function compile()
    {
        if (System::getContainer()->get('huh.utils.container')->isBackend()) {
            $this->strTemplate = 'be_wildcard';
            $this->Template = new BackendTemplate($this->strTemplate);
        }
    }
}
