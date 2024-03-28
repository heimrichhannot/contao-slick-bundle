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


use Contao\Controller;
use Contao\DataContainer;
use Contao\DC_Table;
use HeimrichHannot\SlickBundle\Element\ContentSlickContentStart;
use HeimrichHannot\UtilsBundle\Util\Utils;

class ContentContainer
{
    private Utils $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @param DataContainer $dc
     * @return array
     */
    public function getContentSliderCarousels(DataContainer $dc)
    {
        $arrOptions = [];

        $objSlider = $this->utils->model()->findModelInstancesBy('tl_content', 'type', 'slick-content-start');

        if ($objSlider === null) {
            return $arrOptions;
        }

        while ($objSlider->next()) {
            $objArticle = $this->utils->model()->findModelInstanceByPk('tl_article', $objSlider->pid);

            if ($objArticle === null) {
                continue;
            }

            $arrOptions[$objSlider->id] = sprintf($GLOBALS['TL_LANG']['tl_content']['contentSliderCarouselSelectOption'], $objArticle->title, $objArticle->id, $objSlider->id);
        }

        return $arrOptions;
    }

    /**
     * @param $value
     * @param DC_Table $context
     * @param null $module
     * @return mixed
     */
    public function onCustomTplLoad($value, $context, $module = null)
    {
        if (!$this->utils->container()->isBackend()) {
            return $value;
        }
        if ($context->activeRecord->type === ContentSlickContentStart::TYPE) {
            $GLOBALS['TL_DCA']['tl_content']['fields']['customTpl']['options_callback'] = [static::class, 'getCorrectedTemplates'];
        }

        return $value;
    }

    /**
     * @param DC_Table $dc
     */
    public function getCorrectedTemplates($dc): array
    {
        $templates = [];
        $templates = array_merge($templates, Controller::getTemplateGroup('ce_' . $dc->activeRecord->type . '_', array(), 'ce_' . $dc->activeRecord->type));
        $templates = array_merge($templates, Controller::getTemplateGroup(ContentSlickContentStart::TEMPLATE . '_', array(), 'ce_' . $dc->activeRecord->type));
        return $templates;
    }
}