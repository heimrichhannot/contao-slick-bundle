<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\SlickBundle\Frontend;

use Contao\FilesModel;
use Contao\Frontend;
use Contao\StringUtil;
use Contao\System;
use HeimrichHannot\SlickBundle\Model\SlickConfigModel;

class Slick extends Frontend
{
    /**
     * Current record.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Current record.
     *
     * @var \Model
     */
    protected $settings;

    /**
     * Files object.
     *
     * @var \FilesModel
     */
    protected $files;

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'ce_slick';

    public function __construct($objSettings)
    {
        $this->data = $objSettings->row();
        $this->settings = $objSettings;
        $this->Template = new \FrontendTemplate($this->strTemplate);
        $this->getFiles();
    }

    /**
     * Return an object property.
     *
     * @param string
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return parent::__get($key);
    }

    /**
     * Set an object property.
     *
     * @param string
     * @param mixed
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Check whether a property is set.
     *
     * @param string
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public static function createSettings(array $arrData = [], SlickConfigModel $objConfig)
    {
        \Controller::loadDataContainer('tl_slick_spread');
        $objSettings = $objConfig;
        foreach ($arrData as $key => $value) {
            if (substr($key, 0, 5) != 'slick') {
                continue;
            }
            $arrData = &$GLOBALS['TL_DCA']['tl_slick_spread']['fields'][$key];
            if ($arrData['eval']['multiple'] || $key == 'slickOrderSRC') {
                $value = StringUtil::deserialize($value, true);
            }
            $objSettings->{$key} = $value;
        }
        return $objSettings;
    }


    public function parse()
    {
        $this->Template->images = $this->getImages();

        return $this->Template->parse();
    }

    public function getImages()
    {
        global $objPage;

        $images = [];
        $auxDate = [];
        $files = $this->files;

        if (null === $files) {
            return '';
        }

        // Get all images
        while ($files->next()) {
            // Continue if the files has been processed or does not exist
            if (isset($images[$files->path]) || !file_exists(TL_ROOT.'/'.$files->path)) {
                continue;
            }

            // Single files
            if ('file' == $files->type) {
                if (false === ($image = $this->prepareImage($files->current()))) {
                    continue;
                }

                $images[$files->path] = $image;

                $auxDate[] = $image['file']->mtime;
            } // Folders
            else {
                $subFiles = System::getContainer()->get('contao.framework')->getAdapter(FilesModel::class)->findByPid($files->uuid);

                if (null === $subFiles) {
                    continue;
                }

                while ($subFiles->next()) {
                    // Continue if the files has been processed or does not exist
                    if (isset($images[$subFiles->path]) || !file_exists(TL_ROOT.'/'.$subFiles->path)) {
                        continue;
                    }

                    // Skip subfolders
                    if ('folder' == $subFiles->type) {
                        continue;
                    }

                    if (false === ($image = $this->prepareImage($subFiles->current()))) {
                        continue;
                    }

                    $images[$subFiles->path] = $image;

                    $auxDate[] = $image['file']->mtime;
                }
            }
        }

        // all files do not exist (maybe moved or deleted by FTP or else)
        if (empty($images)) {
            return '';
        }

        // Sort array
        switch ($this->slickSortBy) {
            default:
            case 'name_asc':
                uksort($images, 'basename_natcasecmp');
                break;

            case 'name_desc':
                uksort($images, 'basename_natcasercmp');
                break;

            case 'date_asc':
                array_multisort($images, SORT_NUMERIC, $auxDate, SORT_ASC);
                break;

            case 'date_desc':
                array_multisort($images, SORT_NUMERIC, $auxDate, SORT_DESC);
                break;

            case 'meta': // Backwards compatibility
            case 'custom':
                if ('' != $this->slickOrderSRC) {
                    $tmp = deserialize($this->slickOrderSRC);

                    if (!empty($tmp) && is_array($tmp)) {
                        // Remove all values
                        $arrOrder = array_map(function () {
                        }, array_flip($tmp));

                        // Move the matching elements to their position in $arrOrder
                        foreach ($images as $k => $v) {
                            if (array_key_exists($v['uuid'], $arrOrder)) {
                                $arrOrder[$v['uuid']] = $v;
                                unset($images[$k]);
                            }
                        }

                        // Append the left-over images at the end
                        if (!empty($images)) {
                            $arrOrder = array_merge($arrOrder, array_values($images));
                        }

                        // Remove empty (unreplaced) entries
                        $images = array_values(array_filter($arrOrder));
                        unset($arrOrder);
                    }
                }
                break;

            case 'random':
                shuffle($images);
                break;
        }

        $images = array_values($images);

        // Limit the total number of items (see #2652)
        if ($this->slickNumberOfItems > 0) {
            $images = array_slice($images, 0, $this->slickNumberOfItems);
        }

        $offset = 0;
        $total = count($images);
        $limit = $total;

        $intMaxWidth = (TL_MODE == 'BE') ? floor((640 / $total)) : (\Config::get('maxImageWidth') > 0 ? floor((\Config::get('maxImageWidth') / $total)) : null);
        $strLightboxId = 'lightbox[lb'.$this->id.']';
        $body = [];

        $strTemplate = 'slick_default';

        // Use a custom template
        if (TL_MODE == 'FE' && '' != $this->slickgalleryTpl) {
            $strTemplate = $this->slickgalleryTpl;
        }

        $objTemplate = new \FrontendTemplate($strTemplate);
        $objTemplate->setData($this->data);

        $this->Template->setData($this->data);
        $this->Template->class .= ' '.System::getContainer()->get('huh.slick.config')->getCssClassFromModel($this->settings).' slick';

        for ($i = $offset; $i < $limit; ++$i) {
            $objImage = new \stdClass();
            $images[$i]['size'] = $this->slickSize;
            $images[$i]['fullsize'] = $this->slickFullsize;
            \Controller::addImageToTemplate($objImage, $images[$i], $intMaxWidth, $strLightboxId, $images[$i]['model']);
            $body[$i] = $objImage;
        }

        $objTemplate->body = $body;
        $objTemplate->headline = $this->headline; // see #1603

        return $objTemplate->parse();
    }

    protected function getFiles()
    {
        // Use the home directory of the current user as file source
        if ($this->slickUseHomeDir && FE_USER_LOGGED_IN) {
            $this->import('FrontendUser', 'User');

            if ($this->User->assignDir && $this->User->homeDir) {
                $this->slickMultiSRC = [$this->User->homeDir];
            }
        } else {
            $this->slickMultiSRC = StringUtil::deserialize($this->slickMultiSRC);
        }

        // Return if there are no files
        if (!is_array($this->slickMultiSRC) || empty($this->slickMultiSRC)) {
            return '';
        }

        // Get the file entries from the database
        $this->files = System::getContainer()->get('contao.framework')->getAdapter(FilesModel::class)->findMultipleByUuids($this->slickMultiSRC);

        if (null === $this->files) {
            if (!\Validator::isUuid($this->slickMultiSRC[0])) {
                return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
            }

            return '';
        }
    }

    /**
     * Prepare data for image template.
     *
     * @param FilesModel $model
     *
     * @return array|bool The image data as array for the image template, or false if invalid image
     */
    protected function prepareImage(FilesModel $model)
    {
        global $objPage;

        $file = new \File($model->path);

        if (!$file->isGdImage) {
            return false;
        }

        $arrMeta = $this->getMetaData($model->meta, $objPage->language);

        // Use the file name as title if none is given
        if ('' == $arrMeta['title']) {
            $arrMeta['title'] = StringUtil::specialchars($file->basename);
        }

        $image = [
            'id' => $model->id,
            'uuid' => $model->uuid,
            'name' => $file->basename,
            'file' => $file,
            'model' => $model,
            'singleSRC' => $model->path,
            'alt' => version_compare(VERSION, '4.0', '<') ? $arrMeta['title'] : $arrMeta['alt'],
            'imageUrl' => $arrMeta['link'],
            'caption' => $arrMeta['caption'],
            'title' => $arrMeta['title'],
        ];

        return $image;
    }
}
