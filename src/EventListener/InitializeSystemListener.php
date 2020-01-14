<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\SlickBundle\EventListener;

class InitializeSystemListener
{
    /**
     * @Hook("initializeSystem")
     */
    public function onInitializeSystem(): void
    {
        if (class_exists('Contao\ModuleEventlist')) {
            $GLOBALS['FE_MOD']['events'][\HeimrichHannot\SlickBundle\ModuleSlickEventList::TYPE] =
                \HeimrichHannot\SlickBundle\ModuleSlickEventList::class;
        }
        if (class_exists('Contao\ModuleNewsList')) {
            $GLOBALS['FE_MOD']['news'][\HeimrichHannot\SlickBundle\ModuleSlickNewsList::TYPE]    =
                \HeimrichHannot\SlickBundle\ModuleSlickNewsList::class;
        }
    }
}