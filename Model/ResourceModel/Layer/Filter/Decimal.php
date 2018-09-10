<?php

namespace GoMage\Navigation\Model\ResourceModel\Layer\Filter;

/**
 * Class Decimal
 *
 * @package GoMage\Navigation\Model\ResourceModel\Layer\Filter
 */
class Decimal extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\Decimal
{
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * @param $filter
     * @param $from
     * @param $to
     * @return $this
     */
    public function applyDecimalRangeFilterToCollection($filter, $from, $to)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $attribute = $filter->getAttributeModel();
        $connection = $this->getConnection();
        $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
        ];

        $collection->getSelect()->join(
            [$tableAlias => $this->getMainTable()],
            implode(' AND ', $conditions),
            []
        );

        $collection->getSelect()->where(
            "{$tableAlias}.value >= ?",
            $from
        )->where(
            "{$tableAlias}.value < ?",
            $to
        );

        return $this;
    }
}
