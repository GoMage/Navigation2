<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.1.1
 * @since        Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Observer;

use Magento\Framework\Event\ObserverInterface;

class PredispatchAdminActionControllerObserver implements ObserverInterface
{

    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_backendAuthSession;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(
        \GoMage\Navigation\Helper\Data $helper,
        \Magento\Backend\Model\Auth\Session $backendAuthSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_helper             = $helper;
        $this->_backendAuthSession = $backendAuthSession;
        $this->_scopeConfig        = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_backendAuthSession->isLoggedIn()) {
            if ($this->_scopeConfig->isSetFlag('gomage_notification/notification/enable')) {
                $this->_helper->notify();
            }
        }
    }
}
