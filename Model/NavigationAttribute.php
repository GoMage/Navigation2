<?php
namespace GoMage\Navigation\Model;

class NavigationAttribute extends \Magento\Framework\Model\AbstractModel implements \GoMage\Navigation\Api\Data\NavigationAttributeInterface, \Magento\Framework\DataObject\IdentityInterface
{

    protected function _construct()
    {
        $this->_init('GoMage\Navigation\Model\ResourceModel\NavigationAttribute');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [$this->getId()];
    }
}
