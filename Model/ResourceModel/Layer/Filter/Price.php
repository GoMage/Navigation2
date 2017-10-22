<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace GoMage\Navigation\Model\ResourceModel\Layer\Filter;
use GoMage\Navigation\Model\Config\Source\NavigationInterface;
/**
 * Catalog Layer Price Filter resource model
 */
class Price extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price
{

    /**
     * @var \Magento\Catalog\Model\Layer
     */
    private $layer;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;


    /**
     * Price constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param null $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Customer\Model\Session $session,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        $connectionName = null
    )
    {
        $this->layer = $layerResolver->get();
        $this->session = $session;
        $this->storeManager = $storeManager;
        parent::__construct($context, $eventManager, $layerResolver, $session, $storeManager, $connectionName);
    }


    /**
     * Retrieve clean select with joined price index table
     *
     * @return \Magento\Framework\DB\Select
     */
    protected function _getSelect()
    {

        $base_select = $this->layer->getBaseSelect();
        if (isset($base_select[NavigationInterface::ATTRIBUTE_PRICE])) {
            $select = $base_select[NavigationInterface::ATTRIBUTE_PRICE];
        } else {
            $collection = $this->layer->getProductCollection();
            $collection->addPriceData(
                $this->session->getCustomerGroupId(),
                $this->storeManager->getStore()->getWebsiteId()
            );

            if ($collection->getCatalogPreparedSelect() !== null) {
                $select = clone $collection->getCatalogPreparedSelect();
            } else {
                $select = clone $collection->getSelect();
            }
        }

        // reset columns, order and limitation conditions
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);
        $select->reset(\Magento\Framework\DB\Select::ORDER);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
        // remove join with main table
        $fromPart = $select->getPart(\Magento\Framework\DB\Select::FROM);
        if (!isset(
                $fromPart[\Magento\Catalog\Model\ResourceModel\Product\Collection::INDEX_TABLE_ALIAS]
            ) || !isset(
                $fromPart[\Magento\Catalog\Model\ResourceModel\Product\Collection::MAIN_TABLE_ALIAS]
            )
        ) {
            return $select;
        }

        // processing FROM part
        $priceIndexJoinPart = $fromPart[\Magento\Catalog\Model\ResourceModel\Product\Collection::INDEX_TABLE_ALIAS];
        $priceIndexJoinConditions = explode('AND', $priceIndexJoinPart['joinCondition']);
        $priceIndexJoinPart['joinType'] = \Magento\Framework\DB\Select::FROM;
        $priceIndexJoinPart['joinCondition'] = null;
        $fromPart[\Magento\Catalog\Model\ResourceModel\Product\Collection::MAIN_TABLE_ALIAS] = $priceIndexJoinPart;
        unset($fromPart[\Magento\Catalog\Model\ResourceModel\Product\Collection::INDEX_TABLE_ALIAS]);
        $select->setPart(\Magento\Framework\DB\Select::FROM, $fromPart);
        foreach ($fromPart as $key => $fromJoinItem) {
            $fromPart[$key]['joinCondition'] = $this->_replaceTableAlias($fromJoinItem['joinCondition']);
        }
        $select->setPart(\Magento\Framework\DB\Select::FROM, $fromPart);

        // processing WHERE part
        $wherePart = $select->getPart(\Magento\Framework\DB\Select::WHERE);
        foreach ($wherePart as $key => $wherePartItem) {
            $wherePart[$key] = $this->_replaceTableAlias($wherePartItem);
        }
        $select->setPart(\Magento\Framework\DB\Select::WHERE, $wherePart);
        $excludeJoinPart = \Magento\Catalog\Model\ResourceModel\Product\Collection::MAIN_TABLE_ALIAS . '.entity_id';
        foreach ($priceIndexJoinConditions as $condition) {

            if (strpos($condition, $excludeJoinPart) !== false) {
                continue;
            }
            $select->where($this->_replaceTableAlias($condition));
        }
        $select->where($this->_getPriceExpression($select) . ' IS NOT NULL');

        return $select;
    }

    /**
     * @return float
     */
    public function getMinPrice()
    {
        $base_select = $this->layer->getBaseSelect();
        if (isset($base_select['collection'])) {
            $collection = $base_select['collection'];
        } else {
            $collection = $this->layer->getProductCollection();
        }
        return $collection->getMinPrice();
    }

    /**
     * @return float
     */
    public function getMaxPrice()
    {
        $base_select = $this->layer->getBaseSelect();
        if (isset($base_select['collection'])) {
            $collection = $base_select['collection'];
        } else {
            $collection = $this->layer->getProductCollection();
        }
        return $collection->getMaxPrice();
    }

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     * @param mixed $interval
     * @return $this
     */
    /*public function applyPriceRange(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter, $interval)
    {
        if (!$interval) {
            return $this;
        }

        list($from, $to) = $interval;
        if ($from === '' && $to === '') {
            return $this;
        }
        $value['from'] = $from;
        $value['to'] = $to;
        $select = $filter->getLayer()->getProductCollection()->getSelect();
        //$this->prepareSelect($select, $value);
        $base_select = $filter->getLayer()->getBaseSelect();
        $attribute_code = NavigationInterface::ATTRIBUTE_PRICE;
        foreach ($base_select as $code => $select) {
            if ($attribute_code == $code) {
                $this->prepareSelect($select, $value);
            }
        }
        return $this;
    }*/

   
}