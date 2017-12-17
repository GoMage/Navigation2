<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 16.12.2017
 * Time: 20:34
 */

namespace GoMage\Navigation\Helper;


use Magento\Framework\App\Helper\Context;
use Magento\Swatches\Helper\Media;

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
     * @param array $imageConfig
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
}