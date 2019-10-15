<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\SlickBundle\Asset;


use HeimrichHannot\UtilsBundle\Container\ContainerUtil;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FrontendAsset
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ContainerUtil
     */
    private $containerUtil;

    public function __construct(ContainerInterface $container, ContainerUtil $containerUtil)
    {
        $this->container = $container;
        $this->containerUtil = $containerUtil;
    }

    /**
     * Setup the frontend assets needed for slick slider
     */
    public function addFrontendAssets()
    {
        if (!$this->containerUtil->isFrontend()) {
            return;
        }

        if ($this->container->has('huh.encore.asset.frontend')) {
            $this->container->get('huh.encore.asset.frontend')->addActiveEntrypoint('contao-slick-bundle');
        }

        $GLOBALS['TL_USER_CSS']['slick']                 = 'assets/slick/slick/slick.css|static';
        $GLOBALS['TL_JAVASCRIPT']['slick']               = 'assets/slick/slick/slick.min.js|static';
        $GLOBALS['TL_JAVASCRIPT']['contao-slick-bundle'] = 'bundles/heimrichhannotcontaoslick/assets/contao-slick-bundle.js|static';
    }
}