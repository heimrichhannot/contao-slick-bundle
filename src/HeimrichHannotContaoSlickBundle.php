<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle;

use HeimrichHannot\SlickBundle\DependencyInjection\SlickExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotContaoSlickBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new SlickExtension();
    }
}
