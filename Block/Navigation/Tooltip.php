<?php

namespace GoMage\Navigation\Block\Navigation;

use Magento\Framework\View\Element\Template;

class Tooltip extends \Magento\Framework\View\Element\Template
{
    protected $storeManager;
    protected $helper;

    public function __construct(
        Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \GoMage\Navigation\Helper\Data $helper,
        array $data = [])
    {
        $this->storeManager = $storeManager;
        $this->helper = $helper;
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
}
