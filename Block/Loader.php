<?php

namespace GoMage\Navigation\Block;

use Magento\Framework\View\Element\Template;

class Loader extends \Magento\Framework\View\Element\Template
{
    const LOADER_IMAGE_PATH = 'gomage/navigation/loader/';

    protected $helper;
    protected $alignmentConfig;

    public function __construct(
        Template\Context $context,
        \GoMage\Navigation\Helper\Data $helper,
        \GoMage\Navigation\Model\Config\Source\Alignment $alignmentConfig,
        array $data = []
    ) {
    
        $this->helper = $helper;
        $this->alignmentConfig = $alignmentConfig;
        $this->setTemplate('GoMage_Navigation::layer/loader.phtml');
        parent::__construct($context, $data);
    }

    public function getHelper()
    {
        return $this->helper;
    }

    public function getLoaderImage()
    {
        if (!empty($this->helper->getLoaderImage())) {
            return $this->getUrl('pub/media') . static::LOADER_IMAGE_PATH . $this->helper->getLoaderImage();
        }

        return false;
    }

    public function getImageAlignment()
    {
        return $this->helper->getLoaderImageAlignment();
    }

    public function getBackgroundColor()
    {
        return $this->helper->getLoaderBackgroundColor();
    }

    public function getBorderColor()
    {
        return $this->helper->getLoaderBorderColor();
    }
}
