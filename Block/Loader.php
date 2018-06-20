<?php

namespace GoMage\Navigation\Block;

use Magento\Framework\View\Element\Template;

class Loader extends \Magento\Framework\View\Element\Template
{
    const LOADER_IMAGE_PATH = 'gomage/navigation/loader/';

    /**
     * @var \GoMage\Navigation\Helper\LoaderData
     */
    protected $helper;

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Alignment
     */
    protected $alignmentConfig;

    /**
     * Loader constructor.
     * @param Template\Context $context
     * @param \GoMage\Navigation\Helper\LoaderData $helper
     * @param \GoMage\Navigation\Model\Config\Source\Alignment $alignmentConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \GoMage\Navigation\Helper\LoaderData $helper,
        \GoMage\Navigation\Model\Config\Source\Alignment $alignmentConfig,
        array $data = []
    ) {
    
        $this->helper = $helper;
        $this->alignmentConfig = $alignmentConfig;
        if($helper->isEnabled()) {
            $this->setTemplate('GoMage_Navigation::layer/loader.phtml');
        } else {
            $this->setTemplate('');
        }
        parent::__construct($context, $data);
    }

    /**
     * @return \GoMage\Navigation\Helper\LoaderData
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return bool|string
     */
    public function getLoaderImage()
    {
        if (!empty($this->helper->getLoaderImage())) {
            return $this->getUrl('pub/media') . static::LOADER_IMAGE_PATH . $this->helper->getLoaderImage();
        }

        return false;
    }
}
