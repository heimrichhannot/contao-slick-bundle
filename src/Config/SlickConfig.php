<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Config;

use Contao\Config;
use Contao\StringUtil;
use Contao\System;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;

class SlickConfig
{
    public function getAttributesFromModel($objConfig)
    {
        $arrData = $this->createConfig($objConfig);

        $attributes = ' data-slick-config="'.htmlspecialchars(json_encode($arrData['config']), ENT_QUOTES, Config::get('characterSet')).'"';

        if('' !== $objConfig->initCallback){
            $attributes .= ' data-slick-init-callback="' . htmlspecialchars($objConfig->initCallback, ENT_QUOTES, Config::get('characterSet')) . '"';
        }

        if('' !== $objConfig->afterInitCallback){
            $attributes .= ' data-slick-after-init-callback="' . htmlspecialchars($objConfig->afterInitCallback, ENT_QUOTES, Config::get('characterSet')) . '"';
        }

        return $attributes;
    }

    public function createConfig($objConfig)
    {
        \Controller::loadDataContainer('tl_slick_spread');

        $arrConfig = [];
        $arrObjects = [];

        foreach ($objConfig->row() as $key => $value) {
            if (false === strstr($key, 'slick_')) {
                continue;
            }

            if (!isset($GLOBALS['TL_DCA']['tl_slick_spread']['fields'][$key])) {
                continue;
            }

            $arrData = $GLOBALS['TL_DCA']['tl_slick_spread']['fields'][$key];

            $slickKey = substr($key, 6); // trim slick_ prefix

            if ($arrData['eval']['rgxp'] == 'digit') {
                $value = (int) $value;
            }

            if ('checkbox' == $arrData['inputType'] && !$arrData['eval']['multiple']) {
                $value = (bool) filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            if ($arrData['eval']['multiple'] || 'multiColumnEditor' == $arrData['inputType']) {
                $value = deserialize($value, true);
            }

            if ($arrData['eval']['isJsObject']) {
                $arrObjects[] = $slickKey;
            }

            // check type as well, otherwise
            if ('' === $value) {
                continue;
            }

            if ('slick_responsive' == $key) {
                $arrResponsive = [];

                foreach ($value as $config) {
                    if (empty($config['slick_settings'])) {
                        continue;
                    }

                    $objResponsiveConfig = System::getContainer()->get('huh.slick.model.config')->findByPk($config['slick_settings']);

                    if (null === $objResponsiveConfig) {
                        continue;
                    }

                    $config['breakpoint'] = $config['slick_breakpoint'];
                    unset($config['slick_breakpoint']);

                    $settings = $this->createConfig($objResponsiveConfig);

                    if ($settings['config']['unslick']) {
                        $config['settings'] = 'unslick';
                    } else {
                        $config['settings'] = $settings['config'];
                    }

                    unset($config['slick_settings']);

                    $arrResponsive[] = $config;
                }

                if (empty($arrResponsive)) {
                    $value = false;
                } else {
                    $value = $arrResponsive;
                }
            }

            if ('slick_asNavFor' == $key) {
                if ($value > 0) {
                    $objTargetConfig = System::getContainer()->get('huh.slick.model.config')->findByPk($value);

                    if (null !== $objTargetConfig) {
                        $value = $this->getSlickContainerSelectorFromModel($objTargetConfig);
                    } else {
                        $value = null; // should be null by default
                    }
                }

                if (!$value) {
                    continue;
                }
            }

            if ($key) {
                $arrConfig[$slickKey] = $value;
            }
        }

        // remove responsive settings, otherwise center wont work
        if (empty($arrResponsive)) {
            unset($arrConfig['responsive']);
        }

        $arrReturn = [
            'config' => $arrConfig,
            'objects' => $arrObjects,
        ];

        return $arrReturn;
    }

    public function getSlickContainerSelectorFromModel($objConfig)
    {
        return '.'.$this->getSlickCssClassFromModel($objConfig).' .slick-container';
    }

    public function getSlickCssClassFromModel($objConfig)
    {
        $strClass = $this->stripNamespaceFromClassName($objConfig);

        return 'slick_'.substr(md5($strClass.'_'.$objConfig->id), 0, 6);
    }

    public function stripNamespaceFromClassName($obj)
    {
        $classname = get_class($obj);

        if (preg_match('@\\\\([\w]+)$@', $classname, $matches)) {
            $classname = $matches[1];
        }

        return $classname;
    }

    public function getCssClassFromModel($objConfig)
    {
        return $this->getSlickCssClassFromModel($objConfig).(strlen($objConfig->cssClass) > 0 ? ' '.$objConfig->cssClass : '').' slick_uid_'.uniqid().' slick';
    }

    /**
     * @param array            $data
     * @param SlickConfigModel $config
     *
     * @return SlickConfigModel
     */
    public function createSettings(array $data, SlickConfigModel $config)
    {
        \Controller::loadDataContainer('tl_slick_spread');

        $settings = $config;

        foreach ($data as $key => $value) {
            if ('slick' != substr($key, 0, 5)) {
                continue;
            }

            $data = &$GLOBALS['TL_DCA']['tl_slick_spread']['fields'][$key];

            if ($data['eval']['multiple'] || 'slickOrderSRC' == $key) {
                $value = StringUtil::deserialize($value, true);
            }

            $settings->{$key} = $value;
        }

        return $settings;
    }
}
