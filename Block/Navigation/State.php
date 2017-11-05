<?php

namespace GoMage\Navigation\Block\Navigation;

class State extends \Magento\LayeredNavigation\Block\Navigation\State
{
    protected $dataHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \GoMage\Navigation\Helper\Data $dataHelper,
        array $data = []
    ) {

        $this->dataHelper = $dataHelper;
        parent::__construct($context, $layerResolver, $data);
    }

    protected function _beforeToHtml()
    {
        if ($this->dataHelper->isEnable()) {
            $this->setTemplate('GoMage_Navigation::layer/state.phtml');
        } else {
            $this->setTemplate('Magento_LayeredNavigation::layer/state.phtml');
        }

        return parent::_beforeToHtml();
    }
}
