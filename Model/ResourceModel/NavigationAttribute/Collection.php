<?php


namespace GoMage\Navigation\Model\ResourceModel\NavigationAttribute;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'GoMage\Navigation\Model\NavigationAttribute',
            'GoMage\Navigation\Model\ResourceModel\NavigationAttribute'
        );
    }
}
