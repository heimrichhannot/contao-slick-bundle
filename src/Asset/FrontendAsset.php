<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Asset;

use HeimrichHannot\EncoreContracts\PageAssetsTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class FrontendAsset implements ServiceSubscriberInterface
{
    use PageAssetsTrait;

    public function __invoke()
    {
        $this->addPageEntrypoint('contao-slick-bundle', [
            'TL_CSS' => ['slick' => 'assets/slick/slick/slick.css|static'],
            'TL_JAVASCRIPT' => [
                'slick' => 'assets/slick/slick/slick.min.js|static',
                'contao-slick-bundle' => 'bundles/heimrichhannotcontaoslick/assets/contao-slick-bundle.js|static',
            ],
        ]);
    }
}
