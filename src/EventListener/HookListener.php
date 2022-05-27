<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\SlickBundle\EventListener;


use Contao\Controller;
use Contao\FrontendTemplate;
use Contao\ModuleNews;
use Contao\NewsArchiveModel;
use Contao\StringUtil;
use HeimrichHannot\SlickBundle\Frontend\Slick;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HookListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    private static $strSpreadDca = 'tl_slick_spread';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param FrontendTemplate $template
     * @param array $row
     * @param ModuleNews $module
     */
    public function onParseArticles(&$template, $row, $module)
    {
        if (!$row['addGallery'] || !$module->useSlickGallery) {
            return;
        }

        $objArchive = NewsArchiveModel::findByPk($row['pid']);

        if (null === $objArchive) {
            return;
        }

        if (!$objArchive->slickConfig) {
            return;
        }

        $objConfig = SlickConfigModel::findByPk($objArchive->slickConfig);

        if (null === $objConfig) {
            return;
        }

        // set size from module
        $row['slickSize'] = $module->imgSize;

        $objGallery = new Slick($this->container->get('huh.slick.config')->createSettings($row, $objConfig));

        $template->gallery = $objGallery->parse();
    }

    /**
     * Spread Fields to existing DataContainers.
     *
     * @param string $strName
     *
     * @return bool false if Datacontainer not supported
     */
    public function onLoadDataContainer($strName)
    {
        Controller::loadDataContainer(static::$strSpreadDca);

        if (!is_array($GLOBALS['TL_SLICK']['SUPPORTED']) || !in_array($strName, array_keys($GLOBALS['TL_SLICK']['SUPPORTED']), true)) {
            return false;
        }

        if (!is_array($GLOBALS['TL_DCA'][static::$strSpreadDca]['fields'])) {
            $GLOBALS['TL_DCA'][static::$strSpreadDca]['fields'] = [];
        }

        $dc = &$GLOBALS['TL_DCA'][$strName];

        if (null === $dc) {
            return;
        }

        if(isset($dc['config']['ctable']) && is_array($dc['config']['ctable']) && in_array('tl_content', $dc['config']['ctable']))
        {
            Controller::loadDataContainer('tl_content');
        }

        foreach ($GLOBALS['TL_SLICK']['SUPPORTED'][$strName] as $strPalette => $replace) {
            preg_match_all('#\[\[(?P<constant>.+)\]\]#', $replace, $matches);

            if (!isset($matches['constant'][0])) {
                continue;
            }

            $strConstant = $matches['constant'][0];
            $strReplacePalette = @constant($matches['constant'][0]);

            $pos = strpos($replace, '[['.$strConstant.']]');
            $search = str_replace('[['.$strConstant.']]', '', $replace);

            // prepend slick config palette
            if ($pos < 1) {
                $replace = ($GLOBALS['TL_DCA'][static::$strSpreadDca]['palettes'][$strReplacePalette] ?? '').$search;
            } // append slick config palette
            else {
                $replace = $search.$GLOBALS['TL_DCA'][static::$strSpreadDca]['palettes'][$strReplacePalette];
            }

            $arrFields = static::getPaletteFields($strReplacePalette, $dc);

            $arrFieldKeys = array_keys($arrFields);

            // inject palettes
            // create palette if not existing
            if (!isset($dc['palettes'][$strPalette])) {
                $dc['palettes'][$strPalette] = $replace;
            } else {
                // do not replace multiple times
                if (!$replace || false !== strpos($dc['palettes'][$strPalette], $replace)) {
                    continue;
                }

                $dc['palettes'][$strPalette] = str_replace($search, $replace, $dc['palettes'][$strPalette]);
            }

            if (!is_array($GLOBALS['TL_DCA'][static::$strSpreadDca]['palettes']['__selector__'])) {
                $GLOBALS['TL_DCA'][static::$strSpreadDca]['palettes']['__selector__'] = [];
            }

            // inject subplattes & selectors
            $arrSelectors = array_intersect($GLOBALS['TL_DCA'][static::$strSpreadDca]['palettes']['__selector__'], $arrFieldKeys);

            if (!empty($arrSelectors)) {
                $dc['palettes']['__selector__'] = array_merge(is_array($dc['palettes']['__selector__']) ? $dc['palettes']['__selector__'] : [], $arrSelectors);

                foreach ($arrSelectors as $key) {
                    $arrFields = array_merge($arrFields, $this->getPaletteFields($key, $dc, 'subpalettes'));
                }

                $dc['subpalettes'] = array_merge(is_array($dc['subpalettes']) ? $dc['subpalettes'] : [], $GLOBALS['TL_DCA'][static::$strSpreadDca]['subpalettes']);
            }

            if (!is_array($arrFields)) {
                return;
            }

            // inject fields
            $dc['fields'] = array_merge($arrFields, (is_array($dc['fields']) ? $dc['fields'] : []));
        }

        Controller::loadLanguageFile(static::$strSpreadDca);
        Controller::loadLanguageFile($strName);

        // add language to TL_LANG palette
        if (is_array($GLOBALS['TL_LANG'][static::$strSpreadDca])) {
            $GLOBALS['TL_LANG'][$strName] = array_merge(is_array($GLOBALS['TL_LANG'][$strName]) ? $GLOBALS['TL_LANG'][$strName] : [], $GLOBALS['TL_LANG'][static::$strSpreadDca]);
        }
    }

    protected function getPaletteFields($strPalette, $dc, $type = 'palettes')
    {
        $boxes = StringUtil::trimsplit(';', $GLOBALS['TL_DCA'][static::$strSpreadDca][$type][$strPalette] ?? '');

        $arrFields = [];

        if (!empty($boxes)) {
            foreach ($boxes as $k => $v) {
                $eCount = 1;
                $boxes[$k] = StringUtil::trimsplit(',', $v);

                foreach ($boxes[$k] as $kk => $vv) {
                    if (preg_match('/^\[.*\]$/', $vv)) {
                        ++$eCount;
                        continue;
                    }

                    if (preg_match('/^\{.*\}$/', $vv)) {
                        unset($boxes[$k][$kk]);
                    } else {
                        if (isset($GLOBALS['TL_DCA'][static::$strSpreadDca]['fields'][$vv])) {
                            $arrField = $GLOBALS['TL_DCA'][static::$strSpreadDca]['fields'][$vv];
                        } else {
                            if (isset($dc['fields'][$vv])) {
                                $arrField = $dc['fields'][$vv];
                            } else {
                                continue;
                            }
                        }

                        $arrFields[$vv] = $arrField;

                        // orderSRC support
                        if (isset($arrField['eval']['orderField'])) {
                            $arrFields[$arrField['eval']['orderField']] = $GLOBALS['TL_DCA'][static::$strSpreadDca]['fields']['slickOrderSRC'];
                        }
                    }
                }
            }

            // Unset a box if it does not contain any fields
            if (count($boxes[$k]) < $eCount) {
                unset($boxes[$k]);
            }
        }

        return $arrFields;
    }
}