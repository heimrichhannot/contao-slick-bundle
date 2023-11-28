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


use Contao\CoreBundle\Image\ImageSizes;
use Contao\DataContainer;
use Contao\Image;
use Contao\StringUtil;
use HeimrichHannot\UtilsBundle\Util\Utils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SlickSpreadContainer
{
    private Utils $utils;
    private TranslatorInterface $translator;
    private ImageSizes $imageSizes;

    public function __construct(Utils $utils, TranslatorInterface $translator, ImageSizes $imageSizes)
    {
        $this->utils = $utils;
        $this->translator = $translator;
        $this->imageSizes = $imageSizes;
    }

    public function onFieldsSlickSizeOptionsCallback(): array
    {
        return $this->imageSizes->getAllOptions();
    }

    /** @noinspection PhpTranslationDomainInspection
     * @noinspection PhpTranslationKeyInspection
     * @noinspection HtmlUnknownTarget
     */
    public function onFieldsSlickConfigWizardCallback(DataContainer $dc): string
    {
        if ($dc->value < 1) {
            return '';
        }
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
                'Backend.openModalIframe({\'width\':768,\'title\':\'' . str_replace(
                    "'", "\\'", StringUtil::specialchars(str_replace("'", "\\'", sprintf(
                        $this->translator->trans('tl_slick_spread.slickConfig.0', [], 'contao_tl_slick_spread'),
                        $dc->value
                    )
                ))
                ) . '\',\'url\':this.href});return false',
                Image::getHtml('alias.gif', $this->translator->trans('tl_slick_spread.slickConfig.0', [], 'contao_tl_slick_spread'), 'style="vertical-align:top"')
            );
    }
}