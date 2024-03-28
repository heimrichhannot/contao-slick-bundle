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


use Contao\Backend;
use Contao\CoreBundle\Image\ImageSizes;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;
use HeimrichHannot\UtilsBundle\Util\Utils;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SlickSpreadContainer implements ServiceSubscriberInterface
{
    private Utils $utils;
    private TranslatorInterface $translator;

    public function __construct(Utils $utils, TranslatorInterface $translator)
    {
        $this->utils = $utils;
        $this->translator = $translator;
    }

    public function onFieldsSlickSizeOptionsCallback(): array
    {
        $container = System::getContainer();
        $imageSizes = $container->has('contao.image.image_sizes')
            ? $container->get('contao.image.image_sizes')
            : $container->get(ImageSizes::class);
        return $imageSizes->getAllOptions();
    }

    /**
     * @noinspection HtmlUnknownTarget
     */
    public function onFieldsSlickConfigWizardCallback(DataContainer $dc): string
    {
        if ($dc->value < 1) {
            return '';
        }

        $generateTitle = function (DataContainer $dc) {
            $title = sprintf($this->translator->trans('tl_slick_spread.slickConfig.0', [], 'contao_tl_slick_spread'), $dc->value);
            $title = str_replace("'", "\\'", $title);
            $title = StringUtil::specialchars($title);
            $title = str_replace("'", "\\'", $title);
            return $title;
        };

        return ($dc->value < 1)
            ? ''
            : sprintf(
                ' <a href="%s" title="%s" style="padding-left:3px" onclick="%s">%s</a>',
                $this->utils->routing()->generateBackendRoute([
                    'do' => 'slick_config',
                    'act' => 'edit',
                    'id' => $dc->value,
                    'popup' => 1,
                    'nb' => 1,
                ]),
                sprintf(StringUtil::specialchars($this->translator->trans('tl_slick_spread.slickConfig.1', [], 'contao_tl_slick_spread')), $dc->value),
                sprintf("Backend.openModalIframe({'width':768,'title':'%s','url':this.href});return false;", $generateTitle($dc)),
                Image::getHtml('alias.gif', $this->translator->trans('tl_slick_spread.slickConfig.0', [], 'contao_tl_slick_spread'), 'style="vertical-align:top"')
            );
    }

    /**
     * Return all gallery templates as array.
     *
     * @return array
     */
    public function getGalleryTemplates()
    {
        return Backend::getTemplateGroup('slick_');
    }

    public function setFileTreeFlags($varValue, DataContainer $dc)
    {
        if ($dc->activeRecord && 'slick' === $dc->activeRecord->type) {
            $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['isGallery'] = true;
        }

        return $varValue;
    }

    public function getConfigurations(DataContainer $dc)
    {
        $arrOptions = [];

        $objConfig = SlickConfigModel::findBy(['id != ?'], $dc->activeRecord->id);

        if (null === $objConfig) {
            return $arrOptions;
        }

        while ($objConfig->next()) {
            $arrOptions[$objConfig->id] = $objConfig->title;
        }

        return $arrOptions;
    }

    public static function getSubscribedServices(): array
    {
        return [
            '?'.ImageSizes::class,
            '?contao.image.image_sizes'
        ];
    }
}