<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Backend;

use Contao\ModuleModel;
use Contao\System;

class Module extends \Contao\Backend
{
    /**
     * Modify data container config.
     *
     * @param \DataContainer $dc
     */
    public function modifyDC(\DataContainer $dc)
    {
        $objModule = System::getContainer()->get('contao.framework')->getAdapter(ModuleModel::class)->findByPk($dc->id);

        if (null === $objModule) {
            return;
        }

        $dca = &$GLOBALS['TL_DCA']['tl_module'];

        if ('slick_newslist' == $objModule->type) {
            $dca['fields']['customTpl']['options'] = $this->getTemplateGroup('mod_newslist');
            unset($dca['fields']['customTpl']['options_callback']);
        }

        if ('slick_eventlist' == $objModule->type) {
            $dca['fields']['customTpl']['options'] = $this->getTemplateGroup('mod_eventlist');
            unset($dca['fields']['customTpl']['options_callback']);
        }
    }
}
