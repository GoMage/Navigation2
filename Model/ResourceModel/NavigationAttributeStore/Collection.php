<?php
namespace GoMage\Navigation\Model\ResourceModel\NavigationAttributeStore;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('GoMage\Navigation\Model\NavigationAttributeStore','GoMage\Navigation\Model\ResourceModel\NavigationAttributeStore');
    }
}
