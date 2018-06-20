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
     * Filter Product by Categories
     *
     * @param array $categoriesFilter
     * @return $this
     */
    public function addCategoriesFilter(array $categoriesFilter)
    {
        $this->addFieldToFilter('category_ids', $categoriesFilter);
        return $this;
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

    /**
     * @return mixed
     */
    public function getMaxBasePrice()
    {
        $select = $this->getSelect();
        $priceExpression = $this->getPriceExpression($select) . ' ' . $this->getAdditionalPriceExpression($select);
        $sqlEndPart = ') * ' . $this->getCurrencyRate() . ', 2)';
        $select = $this->_getSelectCountSql($select, false);
        $select->columns(
            [
                'max' => 'ROUND(MAX(' . $priceExpression . $sqlEndPart,
                'min' => 'ROUND(MIN(' . $priceExpression . $sqlEndPart,
                'std' => $this->getConnection()->getStandardDeviationSql('ROUND((' . $priceExpression . $sqlEndPart),
            ]
        );

        return $this;
    }
}
