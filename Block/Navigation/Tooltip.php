<?php

namespace GoMage\Navigation\Block\Navigation;

use Magento\Framework\View\Element\Template;

class Tooltip extends \Magento\Framework\View\Element\Template
{
    protected $storeManager;
    protected $helper;
    protected $navigationViewHelper;

    public function __construct(
        Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \GoMage\Navigation\Helper\Data $helper,
        \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper,
        array $data = []
    ) {
    
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        $this->navigationViewHelper = $navigationViewHelper;
        $this->setTemplate('GoMage_Navigation::layer/tooltip.phtml');
        parent::__construct($context, $data);
    }

    public function getTooltipText($data)
    {
        $data = unserialize($data);
        if (!empty($data[$this->storeManager->getStore()->getId()])) {
            return $data[$this->storeManager->getStore()->getId()];
        }

        return '';
    }

    public function getHelper()
    {
        return $this->helper;
    }

    public function getNavigationViewHelper()
    {
        return $this->navigationViewHelper;
    }
}
