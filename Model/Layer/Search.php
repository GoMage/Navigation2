<?php
namespace GoMage\Navigation\Model\Layer;

/**
 * Class Search
 *
 * @package GoMage\Navigation\Model\Layer
 */
class Search extends \Magento\Catalog\Model\Layer\Search
{
    /**
     * @return \Magento\Catalog\Model\Layer\ItemCollectionProviderInterface
     */
    public function getCollectionProvider()
    {
        return $this->collectionProvider;
    }
}
