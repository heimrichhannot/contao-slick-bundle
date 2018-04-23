<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Backend;

use Contao\Database;
use Contao\System;

class SlickUpdater
{
    public function run()
    {
        $database = System::getContainer()->get('contao.framework')->createInstance(Database::class);
    }
}
