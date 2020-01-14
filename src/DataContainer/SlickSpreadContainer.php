<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\SlickBundle\DataContainer;


use Symfony\Component\DependencyInjection\ContainerInterface;

class SlickSpreadContainer
{
    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * SlickSpreadContainer constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function onSlickSizeOptionsCallback ()
    {
        return $this->container->get('contao.image.image_sizes')->getAllOptions();
    }
}