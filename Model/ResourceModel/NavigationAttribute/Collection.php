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
 * @version   Release: 1.1.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Model\ResourceModel\NavigationAttribute;

/**
 * Class Collection
 *
 * @package GoMage\Navigation\Model\ResourceModel\NavigationAttribute
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \GoMage\Navigation\Model\NavigationAttribute::class,
            \GoMage\Navigation\Model\ResourceModel\NavigationAttribute::class
        );
    }
}
