<?php

namespace HeimrichHannot\SlickBundle\EventListener;

use Contao\System;
use HeimrichHannot\ListBundle\Event\ListBeforeRenderEvent;
use HeimrichHannot\SlickBundle\Config\SlickConfig;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;

class ListBeforeRenderListener
{
    /**
     * @var SlickConfig
     */
    private $slickConfig;


    /**
     * ListBeforeRenderListener constructor.
     */
    public function __construct(SlickConfig $slickConfig)
    {
        $this->slickConfig = $slickConfig;
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
}