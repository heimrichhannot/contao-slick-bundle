<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\ContaoManager;

use Contao\CalendarBundle\ContaoCalendarBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\NewsBundle\ContaoNewsBundle;
use HeimrichHannot\SlickBundle\HeimrichHannotContaoSlickBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        $loadAfter = [ContaoCoreBundle::class];
        if (class_exists('Contao\CalendarBundle\ContaoCalendarBundle')) {
            $loadAfter[] = ContaoCalendarBundle::class;
        }
        if (class_exists('Contao\NewsBundle\ContaoNewsBundle')) {
            $loadAfter[] = ContaoNewsBundle::class;
        }

        return [
            BundleConfig::create(HeimrichHannotContaoSlickBundle::class)->setLoadAfter($loadAfter),
        ];
    }

    /**
     * Allows a plugin to load container configuration.
     *
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load('@HeimrichHannotContaoSlickBundle/Resources/config/services.yml');
        $loader->load('@HeimrichHannotContaoSlickBundle/Resources/config/listeners.yml');
        $loader->load('@HeimrichHannotContaoSlickBundle/Resources/config/datacontainers.yml');
    }
}
