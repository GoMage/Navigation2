<?php
namespace GoMage\Navigation\Model\Layer;

class Category extends \Magento\Catalog\Model\Layer\Category
{

    public function getCollectionProvider()
    {
        return $this->collectionProvider;
    }
}
