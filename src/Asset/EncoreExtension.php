<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Asset;

use HeimrichHannot\EncoreContracts\EncoreEntry;
use HeimrichHannot\EncoreContracts\EncoreExtensionInterface;
use HeimrichHannot\SlickBundle\HeimrichHannotContaoSlickBundle;

class EncoreExtension implements EncoreExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function getBundle(): string
    {
        return HeimrichHannotContaoSlickBundle::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntries(): array
    {
        return [
            EncoreEntry::create('contao-slick-bundle', 'src/Resources/assets/js/contao-slick-bundle.js')
                ->setRequiresCss(true)
                ->addCssEntryToRemoveFromGlobals('slick')
                ->addJsEntryToRemoveFromGlobals('slick')
                ->addJsEntryToRemoveFromGlobals('contao-slick-bundle'),
            EncoreEntry::create('contao-slick-bundle-theme', 'src/Resources/assets/js/contao-slick-bundle-theme.es6.js')
                ->setRequiresCss(true),
        ];
    }
}
