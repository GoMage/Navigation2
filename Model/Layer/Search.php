<?php
namespace GoMage\Navigation\Model\Layer;

class Search extends \Magento\Catalog\Model\Layer\Search
{

    public function getCollectionProvider()
    {
        return $this->collectionProvider;
    }
}
