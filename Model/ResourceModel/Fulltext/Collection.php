<?php
namespace GoMage\Navigation\Model\ResourceModel\Fulltext;

use Magento\Framework\App\ObjectManager;

class Collection extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
{

    protected $_addedFilters = [];

    public function addFieldToFilter($field, $condition = null)
    {
        if (is_string($field)) {
            $this->_addedFilters[$field] = $condition;
        }
        return parent::addFieldToFilter($field, $condition);
    }

    public function addAdditionalFilter($field, $condition = null)
    {
        if (is_string($field)) {
            $this->_addedFilters[$field] = $condition;
        }
    }

    public function getAddedFilters()
    {
        return $this->_addedFilters;
    }

    public function updateSearchCriteriaBuilder()
    {
        $searchCriteriaBuilder = ObjectManager::getInstance()
            ->create(\Magento\Framework\Api\Search\SearchCriteriaBuilder::class);
        $this->setSearchCriteriaBuilder($searchCriteriaBuilder);
        return $this;
    }
}
