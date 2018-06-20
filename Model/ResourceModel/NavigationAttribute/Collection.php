<?php
namespace GoMage\Navigation\Model\ResourceModel\NavigationAttribute;

/**
 * Class Collection
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
