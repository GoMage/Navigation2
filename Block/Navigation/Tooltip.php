<?php

namespace GoMage\Navigation\Block\Navigation;

use Magento\Framework\View\Element\Template;

class Tooltip extends \Magento\Framework\View\Element\Template
{
    protected $storeManager;

    public function __construct(
        Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = [])
    {
        $this->storeManager = $storeManager;
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
}
