<?php
namespace GoMage\Navigation\Model\ResourceModel;

/**
 * Class NavigationAttribute
 *
 * @package GoMage\Navigation\Model\ResourceModel
 */
class NavigationAttribute extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('gomage_navigation_attribute', 'id');
    }
}
