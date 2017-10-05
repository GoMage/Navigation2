<?php


namespace GoMage\Navigation\Model;

use GoMage\Navigation\Api\Data\NavigationAttributeInterface;

class NavigationAttribute extends \Magento\Framework\Model\AbstractModel implements NavigationAttributeInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('GoMage\Navigation\Model\ResourceModel\NavigationAttribute');
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }
}
