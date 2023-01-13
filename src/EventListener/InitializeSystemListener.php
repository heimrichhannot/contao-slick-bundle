<?php

/*
 * Copyright (c) 2023 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\EventListener;

use HeimrichHannot\SlickBundle\Module\ModuleSlickEventList;
use HeimrichHannot\SlickBundle\Module\ModuleSlickNewsList;

class InitializeSystemListener
{
    /**
     * @Hook("initializeSystem")
     */
    public function onInitializeSystem(): void
    {
        if (class_exists('Contao\ModuleEventlist')) {
            $GLOBALS['FE_MOD']['events'][ModuleSlickEventList::TYPE] = ModuleSlickEventList::class;
        }
        if (class_exists('Contao\ModuleNewsList')) {
            $GLOBALS['FE_MOD']['news'][ModuleSlickNewsList::TYPE] = ModuleSlickNewsList::class;
        }
    }
}
