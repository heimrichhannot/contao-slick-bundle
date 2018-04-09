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

namespace HeimrichHannot\SlickBundle\Backend;


use Contao\Database;
use Contao\System;

class SlickUpdater
{
    public function run()
    {
        $database = System::getContainer()->get('contao.framework')->createInstance(Database::class);

        return;
    }
} 