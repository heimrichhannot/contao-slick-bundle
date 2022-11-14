<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\EventListener;

use Contao\Module;
use HeimrichHannot\ListBundle\Event\ListBeforeRenderEvent;
use HeimrichHannot\ListBundle\Event\ListCompileEvent;
use HeimrichHannot\SlickBundle\Asset\FrontendAsset;
use HeimrichHannot\SlickBundle\Config\SlickConfig;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ListBundleListener implements EventSubscriberInterface
{
    private SlickConfig   $slickConfig;
    private FrontendAsset $frontendAsset;

    public function __construct(SlickConfig $slickConfig, FrontendAsset $frontendAsset)
    {
        $this->slickConfig = $slickConfig;
        $this->frontendAsset = $frontendAsset;
    }

    public static function getSubscribedEvents()
    {
        if (class_exists(ListBeforeRenderEvent::class)) {
            return [
                ListBeforeRenderEvent::NAME => 'onListBeforeRender',
                ListCompileEvent::NAME => 'onListCompile',
            ];
        }

        return [];
    }

    public function onListBeforeRender(ListBeforeRenderEvent $event)
    {
        $templateData = $event->getTemplateData();
        $listConfig = $event->getListConfig();

        if ($listConfig->addSlick && null !== ($objConfig = SlickConfigModel::findByPk($listConfig->slickConfig))) {
            $dataAttributes = $templateData['dataAttributes'] ?: '';
            $dataAttributes .= $this->slickConfig->getAttributesFromModel($objConfig);
            $templateData['dataAttributes'] = $dataAttributes;
            $templateData['itemsClass'] = 'slick-container';

            $event->setTemplateData($templateData);
        }
    }

    public function onListCompile(ListCompileEvent $event)
    {
        $listConfig = $event->getListConfig();

        if ($listConfig->addSlick && null !== ($objConfig = SlickConfigModel::findByPk($listConfig->slickConfig))) {
            if ($event->getModule() instanceof Module) {
                $cssID = $event->getModule()->cssID;
                $cssID[1] = $cssID[1].($cssID[1] ? ' ' : '').$this->slickConfig->getCssClassFromModel($objConfig);
                $event->getModule()->cssID = $cssID;
            } else {
                $event->getTemplate()->class = trim($event->getTemplate()->class.' '.$this->slickConfig->getCssClassFromModel($objConfig));
            }
            $this->frontendAsset->addFrontendAssets();
        }
    }
}
