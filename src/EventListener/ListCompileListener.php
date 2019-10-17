<?php

namespace HeimrichHannot\SlickBundle\EventListener;

use Contao\System;
use HeimrichHannot\ListBundle\Event\ListCompileEvent;
use HeimrichHannot\SlickBundle\Asset\FrontendAsset;
use HeimrichHannot\SlickBundle\Config\SlickConfig;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;

class ListCompileListener
{
    /**
     * @var SlickConfig
     */
    private $slickConfig;
    /**
     * @var FrontendAsset
     */
    private $frontendAsset;


    /**
     * ListCompileListener constructor.
     */
    public function __construct(SlickConfig $slickConfig, FrontendAsset $frontendAsset)
    {
        $this->slickConfig = $slickConfig;
        $this->frontendAsset = $frontendAsset;
    }

    public function onListCompileRender(ListCompileEvent $event)
    {
        $module = $event->getModule();
        $listConfig = $event->getListConfig();

        if ($listConfig->addSlick && null !== ($objConfig = SlickConfigModel::findByPk($listConfig->slickConfig))) {
            $cssID = $module->cssID;
            $cssID[1] = $cssID[1].($cssID[1] ? ' ' : '').$this->slickConfig->getCssClassFromModel($objConfig);
            $module->cssID = $cssID;
            $this->frontendAsset->addFrontendAssets();
        }
    }
}