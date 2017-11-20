<?php
namespace GoMage\Navigation\Model;

class NavigationAttributeStore extends \Magento\Framework\Model\AbstractModel implements \GoMage\Navigation\Api\Data\NavigationAttributeStoreInterface, \Magento\Framework\DataObject\IdentityInterface
{

    protected function _construct()
    {
        $this->_init('GoMage\Navigation\Model\ResourceModel\NavigationAttributeStore');
    }

    public function getIdentities()
    {
        return [$this->getId()];
    }
}
