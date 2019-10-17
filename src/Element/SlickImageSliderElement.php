<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Element;

use Contao\ContentGallery;
use Contao\System;
use HeimrichHannot\SlickBundle\Asset\FrontendAsset;
use HeimrichHannot\SlickBundle\Frontend\Slick;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;

class SlickImageSliderElement extends ContentGallery
{
    const TYPE = 'slick-slider';

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'ce_slick';

    /**
     * Return if there are no files.
     *
     * @return string
     */
    public function generate()
    {
        // show gallery instead of slickcarousel in backend mode
        if (System::getContainer()->get('huh.utils.container')->isBackend()) {
            return parent::generate();
        }

        parent::generate();

        if (!$this->slickConfig) {
            return '';
        }

        $objConfig = SlickConfigModel::findByPk($this->slickConfig);

        if (null === $objConfig) {
            return '';
        }

        System::getContainer()->get(FrontendAsset::class)->addFrontendAssets();

        // Map content fields to slick fields
        $this->arrData['slickMultiSRC'] = $this->arrData['multiSRC'];
        $this->arrData['slickOrderSRC'] = $this->arrData['orderSRC'];
        $this->arrData['slickSortBy'] = $this->arrData['sortBy'];
        $this->arrData['slickUseHomeDir'] = $this->arrData['useHomeDir'];
        $this->arrData['slickSize'] = $this->arrData['size'];
        $this->arrData['slickFullsize'] = $this->arrData['fullsize'];
        $this->arrData['slickNumberOfItems'] = $this->arrData['numberOfItems'];
        $this->arrData['slickCustomTpl'] = $this->arrData['customTpl'];

        $gallery = new Slick(Slick::createSettings($this->arrData, $objConfig));

        $this->Template->class .= ' '.System::getContainer()->get('huh.slick.config')->getCssClassFromModel($objConfig);
        $this->Template->attributes .= System::getContainer()->get('huh.slick.config')->getAttributesFromModel($objConfig);
        $this->Template->images = $gallery->getImages();

        return $this->Template->parse();
    }
}