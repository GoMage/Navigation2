<?php


namespace GoMage\Navigation\Api\Data;

interface NavigationAttributeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get NavigationAttribute list.
     * @return \GoMage\Navigation\Api\Data\NavigationAttributeInterface[]
     */
    public function getItems();

    /**
     * Set attribute_id list.
     * @param \GoMage\Navigation\Api\Data\NavigationAttributeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
