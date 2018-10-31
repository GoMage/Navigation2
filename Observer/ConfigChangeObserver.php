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

class ConfigChangeObserver implements ObserverInterface
{

    /**
     * @var \GoMage\Feed\Helper\Data
     */
    protected $_helper;

    public function __construct(
        \GoMage\Navigation\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_helper->a();
    }

}
