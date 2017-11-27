<?php
namespace GoMage\Navigation\Model\ResourceModel\Fulltext;

use Magento\Framework\App\ObjectManager;

class Collection extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
{

    /**
     * @var array
     */
    protected $addedFilters = [];

    /**
     * @param string $field
     * @param null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (is_string($field)) {
            $this->addedFilters[$field] = $condition;
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * @param array $categoriesFilter
     * @return $this
     */
    public function addCategoriesFilter(array $categoriesFilter)
    {

        $this->addedFilters['category_id'] = $categoriesFilter;

        return parent::addCategoriesFilter($categoriesFilter);
    }

    /**
     * @param $field
     * @param null $condition
     */
    public function addAdditionalFilter($field, $condition = null)
    {
        if (is_string($field)) {
            $this->addedFilters[$field] = $condition;
        }
    }

    /**
     * @return array
     */
    public function getAddedFilters()
    {
        return $this->addedFilters;
    }

    /**
     * @return $this
     */
    public function updateSearchCriteriaBuilder()
    {
        $searchCriteriaBuilder = ObjectManager::getInstance()
            ->create(\Magento\Framework\Api\Search\SearchCriteriaBuilder::class);
        $this->setSearchCriteriaBuilder($searchCriteriaBuilder);
        return $this;
    }
}
