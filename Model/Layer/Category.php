<?php
namespace GoMage\Navigation\Model\Layer;

/**
 * Class Category
 * @package GoMage\Navigation\Model\Layer
 */
class Category extends \Magento\Catalog\Model\Layer\Category
{
    /**
     * @return \Magento\Catalog\Model\Layer\ItemCollectionProviderInterface
     */
    public function getCollectionProvider()
    {
        return $this->collectionProvider;
    }
}
