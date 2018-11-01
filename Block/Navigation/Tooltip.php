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
 * @version   Release: 1.0.0
 * @since     Class available since Release 1.0.0
 */
namespace GoMage\Navigation\Block\Navigation;

use Magento\Framework\View\Element\Template;

class Tooltip extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $helper;

    /**
     * @var \GoMage\Navigation\Helper\NavigationViewData
     */
    protected $navigationViewHelper;

    /**
     * @param Template\Context                             $context
     * @param \GoMage\Navigation\Helper\Data               $helper
     * @param \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper
     * @param array                                        $data
     */
    public function __construct(
        Template\Context $context,
        \GoMage\Navigation\Helper\Data $helper,
        \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper,
        array $data = []
    ) {
        $this->storeManager = $context->getStoreManager();
        $this->helper = $helper;
        $this->navigationViewHelper = $navigationViewHelper;
        $this->setTemplate('GoMage_Navigation::layer/tooltip.phtml');
        parent::__construct($context, $data);
    }

    /**
     * @param $data
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTooltipText($data)
    {
        $data = unserialize($data);
        if (!empty($data[$this->storeManager->getStore()->getId()])) {
            return $data[$this->storeManager->getStore()->getId()];
        }

        return '';
    }

    /**
     * @return \GoMage\Navigation\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return \GoMage\Navigation\Helper\NavigationViewData
     */
    public function getNavigationViewHelper()
    {
        return $this->navigationViewHelper;
    }
}
