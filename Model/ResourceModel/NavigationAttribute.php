<?php


namespace GoMage\Navigation\Model\ResourceModel;

class NavigationAttribute extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('gomage_navigation_attribute', 'test_id');
    }
}
