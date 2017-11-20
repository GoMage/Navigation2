<?php
namespace GoMage\Navigation\Model\ResourceModel;

class NavigationAttributeStore extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('gomage_navigation_attribute_store', 'id');
    }
}
