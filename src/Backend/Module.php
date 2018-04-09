<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\SlickBundle\Backend;


use Contao\ModuleModel;
use Contao\System;

class Module extends \Contao\Backend
{
    /**
     * Modify data container config
     *
     * @param \DataContainer $dc
     */
    public function modifyDC(\DataContainer $dc)
    {
        $objModule = System::getContainer()->get('contao.framework')->getAdapter(ModuleModel::class)->findByPk($dc->id);

        if ($objModule === null) {
            return;
        }

        $dca = &$GLOBALS['TL_DCA']['tl_module'];

        if ($objModule->type == 'slick_newslist') {
            $dca['fields']['customTpl']['options'] = $this->getTemplateGroup('mod_newslist');
            unset($dca['fields']['customTpl']['options_callback']);
        }

        if ($objModule->type == 'slick_eventlist') {
            $dca['fields']['customTpl']['options'] = $this->getTemplateGroup('mod_eventlist');
            unset($dca['fields']['customTpl']['options_callback']);
        }
    }

}