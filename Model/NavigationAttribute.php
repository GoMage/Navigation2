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

namespace GoMage\Navigation\Model;

/**
 * Class NavigationAttribute
 *
 * @package GoMage\Navigation\Model
 */
class NavigationAttribute extends \Magento\Framework\Model\AbstractModel implements
    \GoMage\Navigation\Api\Data\NavigationAttributeInterface,
    \Magento\Framework\DataObject\IdentityInterface
{
    protected function _construct()
    {
        $this->_init(\GoMage\Navigation\Model\ResourceModel\NavigationAttribute::class);
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [$this->getId()];
    }
}
