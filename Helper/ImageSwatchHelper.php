<?php

/**
 * GoMage.com
 *
 * GoMage Navigation M2
 *
 * @category  Extension
 * @copyright Copyright (c) 2018-2018 GoMage.com (https://www.gomage.com)
 * @author    GoMage.com
 * @license   https://www.gomage.com/licensing  Single domain license
 * @terms     of use https://www.gomage.com/terms-of-use
 * @version   Release: 1.1.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Helper;


use Magento\Framework\App\Helper\Context;
use Magento\Swatches\Helper\Media;

/**
 * Class ImageSwatchHelper
 *
 * @package GoMage\Navigation\Helper
 */
class ImageSwatchHelper extends Media
{
    /**
     * @var integer
     */
    protected $width;

    /**
     * @var integer
     */
    protected $height;

    /**
     * @param string $type
     * @param string $filename
     *
     * @return string
     */
    public function getImagePath($type, $filename)
    {
        $imagePath = $this->getSwatchAttributeImage($type, $filename);

        return $imagePath;
    }

    /**
     * @param string $swatchType
     * @param array  $imageConfig
     *
     * @return string
     */
    public function getFolderNameSize($swatchType, $imageConfig = null)
    {
        if ($imageConfig === null) {
            $imageConfig = $this->getImageConfig();
        }
        if ($this->width) {
            $imageConfig[$swatchType]['width'] = $this->width;
        }
        if ($this->width) {
            $imageConfig[$swatchType]['height'] = $this->height;
        }

        return $imageConfig[$swatchType]['width'] . 'x' . $imageConfig[$swatchType]['height'];
    }
    
    /**
     * @param integer $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param integer $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Generate swatch thumb and small swatch image
     *
     * @param  string $imageUrl
     * @return $this
     */
    public function generateSwatchVariations($imageUrl)
    {
        $absoluteImagePath = $this->mediaDirectory->getAbsolutePath($this->getAttributeSwatchPath($imageUrl));
        foreach ($this->swatchImageTypes as $swatchType) {
            $imageConfig = $this->getImageConfig();
            if ($this->width) {
                $imageConfig[$swatchType]['width'] = $this->width;
            }
            if ($this->width) {
                $imageConfig[$swatchType]['height'] = $this->height;
            }
            $swatchNamePath = $this->generateNamePath($imageConfig, $imageUrl, $swatchType);
            $image = $this->imageFactory->create($absoluteImagePath);
            $this->setupImageProperties($image);
            $image->resize($imageConfig[$swatchType]['width'], $imageConfig[$swatchType]['height']);
            $this->setupImageProperties($image, true);
            $image->save($swatchNamePath['path_for_save'], $swatchNamePath['name']);
        }
        return $this;
    }
}