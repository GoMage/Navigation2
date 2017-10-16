<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace GoMage\Navigation\Model\ResourceModel\Layer\Filter;

/**
 * Catalog Layer Attribute Filter Resource Model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Attribute extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\Attribute
{
    /**
     * Initialize connection and define main table name
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('catalog_product_index_eav', 'entity_id');
    }

    /**
     * Apply attribute filter to product collection
     *
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     * @param int $value
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return $this
     */
    public function applyFilterToCollection(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter, $value)
    {

        $collection = $filter->getLayer()->getProductCollection();
        $storeId = $collection->getStoreId();
        $this->prepareSelect($filter, $value, $collection->getSelect(),$storeId);

        $base_select = $filter->getLayer()->getBaseSelect();
        $attribute_code = $filter->getAttributeModel()->getAttributeCode();
        foreach ($base_select as $code => $select) {
            if ($attribute_code == $code) {
                $this->prepareSelect($filter, $value, $select,$storeId);
            }
        }

        return $this;
    }

    /**
     * @param $filter
     * @param $value
     * @param $select
     */
    public function prepareSelect($filter, $value, $select, $storeId)
    {

        $attribute = $filter->getAttributeModel();
        $connection = $this->getConnection();
        $tableAlias = $attribute->getAttributeCode() . '_idx';

        $value = (array)$value;

        foreach ($value as $_value) {
            $where[] = intval($_value);
        }

        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $storeId),
            $connection->quoteInto($tableAlias . '.value IN (' . implode(',', $where) . ')', null)
        ];

        $select->join(
            [$tableAlias => $this->getMainTable()],
            implode(' AND ', $conditions),
            []
        );
    }

    /**
     * Retrieve array with products counts per attribute option
     *
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return array
     */
    public function getCount(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        // clone select from collection with filters

        $base_select = $filter->getLayer()->getBaseSelect();
        $attribute = $filter->getAttributeModel();
        $attribute_code = $attribute->getAttributeCode();

        if (isset($base_select[$attribute_code])) {
            $select = $base_select[$attribute_code];
        }else {
            $select = clone $filter->getLayer()->getProductCollection()->getSelect();
        }

        $tableAlias = sprintf('%s_idx', $attribute_code . md5(microtime()));

        // reset columns, order and limitation conditions
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);
        $select->reset(\Magento\Framework\DB\Select::ORDER);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
        $select->reset(\Magento\Framework\DB\Select::GROUP);


        $connection = $this->getConnection();

        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        ];

        $select->join(
            [$tableAlias => $this->getMainTable()],
            join(' AND ', $conditions),
            ['value', 'count' => new \Zend_Db_Expr("COUNT( {$tableAlias}.entity_id)")]
        )->group(
            "{$tableAlias}.value"
        );

        $_collection =  $filter->getLayer()->getProductCollection();
        $_collection->getSelect()->distinct(true);

        return $connection->fetchPairs($select);
    }
}
