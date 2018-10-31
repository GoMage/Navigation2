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
 * @version   Release: 2.0.0
 * @since     Class available since Release 2.0.0
 */
namespace GoMage\Navigation\Block\Navigation;

class State extends \Magento\LayeredNavigation\Block\Navigation\State
{
    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver            $layerResolver
     * @param \GoMage\Navigation\Helper\Data                   $dataHelper
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \GoMage\Navigation\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $layerResolver, $data);
    }

    /**
     * @return \GoMage\Navigation\Helper\Data
     */
    public function getDataHelper()
    {
        return $this->dataHelper;
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        if ($this->getDataHelper()->isEnable()) {
            $this->setTemplate('GoMage_Navigation::layer/state.phtml');
        } else {
            $this->setTemplate('Magento_LayeredNavigation::layer/state.phtml');
        }

        return parent::_beforeToHtml();
    }
}
