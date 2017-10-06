<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;
use GoMage\Navigation\Model\Config\Source\NavigationInterface;
class Category extends \Magento\Catalog\Model\Layer\Filter\Category implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function isAjax()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getAttributeCode(){
        return NavigationInterface::ATTRIBUTE_CATEGORY;
    }

    /**
     * @return $this
     */
    public function getAttributeModel(){
        return $this;
    }

    /**
     * return array
     */
    public function _getItemsData(){
        return [];
    }
}
