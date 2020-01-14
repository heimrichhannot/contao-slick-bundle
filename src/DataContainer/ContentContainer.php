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


use Contao\DataContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContentContainer
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container
    )
    {
        $this->container = $container;
    }

    /**
     * @param DataContainer $dc
     * @return array
     */
    public function getContentSliderCarousels(DataContainer $dc)
    {
        $arrOptions = [];

        $objSlider = $this->container->get('huh.utils.model')->findModelInstancesBy('tl_content', 'type', 'slick-content-start');

        if ($objSlider === null) {
            return $arrOptions;
        }

        while ($objSlider->next()) {
            $objArticle = $this->container->get('huh.utils.model')->findModelInstanceByPk('tl_article', $objSlider->pid);

            if ($objArticle === null) {
                continue;
            }

            $arrOptions[$objSlider->id] = sprintf($GLOBALS['TL_LANG']['tl_content']['contentSliderCarouselSelectOption'], $objArticle->title, $objArticle->id, $objSlider->id);
        }

        return $arrOptions;
    }
}