<?php
namespace GoMage\Navigation\Model\ResourceModel\Fulltext;

use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Catalog\Model\Indexer\Category\Product\TableMaintainer;

class Collection extends \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection
{
    /**
     * @var array
     */
    protected $addedFilters = [];

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory                $entityFactory
     * @param \Psr\Log\LoggerInterface                                        $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface    $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface                       $eventManager
     * @param \Magento\Eav\Model\Config                                       $eavConfig
     * @param \Magento\Framework\App\ResourceConnection                       $resource
     * @param \Magento\Eav\Model\EntityFactory                                $eavEntityFactory
     * @param \Magento\Catalog\Model\ResourceModel\Helper                     $resourceHelper
     * @param \Magento\Framework\Validator\UniversalFactory                   $universalFactory
     * @param \Magento\Store\Model\StoreManagerInterface                      $storeManager
     * @param \Magento\Framework\Module\Manager                               $moduleManager
     * @param \Magento\Catalog\Model\Indexer\Product\Flat\State               $catalogProductFlatState
     * @param \Magento\Framework\App\Config\ScopeConfigInterface              $scopeConfig
     * @param \Magento\Catalog\Model\Product\OptionFactory                    $productOptionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Url                        $catalogUrl
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface            $localeDate
     * @param \Magento\Customer\Model\Session                                 $customerSession
     * @param \Magento\Framework\Stdlib\DateTime                              $dateTime
     * @param \Magento\Customer\Api\GroupManagementInterface                  $groupManagement
     * @param \Magento\Search\Model\QueryFactory                              $catalogSearchData
     * @param \Magento\Framework\Search\Request\Builder                       $requestBuilder
     * @param \Magento\Search\Model\SearchEngine                              $searchEngine
     * @param \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory
     * @param SearchCriteriaBuilder                                           $searchCriteriaBuilder
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null             $connection
     * @param string                                                          $searchRequestName
     * @param SearchResultFactory|null                                        $searchResultFactory
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\Search\Model\QueryFactory $catalogSearchData,
        \Magento\Framework\Search\Request\Builder $requestBuilder,
        \Magento\Search\Model\SearchEngine $searchEngine,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        $searchRequestName = 'catalog_view_container',
        SearchResultFactory $searchResultFactory = null
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $moduleManager,
            $catalogProductFlatState,
            $scopeConfig,
            $productOptionFactory,
            $catalogUrl,
            $localeDate,
            $customerSession,
            $dateTime,
            $groupManagement,
            $catalogSearchData,
            $requestBuilder,
            $searchEngine,
            $temporaryStorageFactory,
            $connection,
            $searchRequestName,
            $searchResultFactory
        );
    }

    /**
     * @param string $field
     * @param null   $condition
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
     * @param null  $condition
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
        $this->setSearchCriteriaBuilder($this->searchCriteriaBuilder);
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

    //    /**
    //     * Apply limitation filters to collection
    //     * Method allows using one time category product index table (or product website table)
    //     * for different combinations of store_id/category_id/visibility filter states
    //     * Method supports multiple changes in one collection object for this parameters
    //     *
    //     * @return $this
    //     */
    //    protected function _applyProductLimitations()
    //    {
    //        $mainTable = \Magento\Framework\App\ObjectManager::getInstance()->get(TableMaintainer::class);
    //        $this->_prepareProductLimitationFilters();
    //        $this->_productLimitationJoinWebsite();
    //        $this->_productLimitationJoinPrice();
    //        $filters = $this->_productLimitationFilters;
    //
    //        if (!isset($filters['category_id']) && !isset($filters['visibility'])) {
    //            return $this;
    //        }
    //
    //        $conditions = [
    //            'cat_index.product_id=e.entity_id',
    //            $this->getConnection()->quoteInto('cat_index.store_id=?', $filters['store_id']),
    //        ];
    //        if (isset($filters['visibility']) && !isset($filters['store_table'])) {
    //            $conditions[] = $this->getConnection()->quoteInto('cat_index.visibility IN(?)', $filters['visibility']);
    //        }
    //        if(is_array($filters['category_id'])) {
    //            $conditions[] = $this->getConnection()->quoteInto('cat_index.category_id IN(?)', $filters['category_id']);
    //            $this->getSelect()->group('cat_index.product_id');
    //        } else {
    //            $conditions[] = $this->getConnection()->quoteInto('cat_index.category_id=?', $filters['category_id']);
    //        }
    //
    //        if (isset($filters['category_is_anchor'])) {
    //            $conditions[] = $this->getConnection()->quoteInto('cat_index.is_parent=?', $filters['category_is_anchor']);
    //        }
    //
    //        $joinCond = join(' AND ', $conditions);
    //        $fromPart = $this->getSelect()->getPart(\Magento\Framework\DB\Select::FROM);
    //        if (isset($fromPart['cat_index'])) {
    //            $fromPart['cat_index']['joinCondition'] = $joinCond;
    //            $this->getSelect()->setPart(\Magento\Framework\DB\Select::FROM, $fromPart);
    //        } else {
    //            $this->getSelect()->join(
    //                ['cat_index' => $mainTable->getMainTable($this->getStoreId())],
    //                $joinCond,
    //                ['cat_index_position' => 'position']
    //            );
    //        }
    //        $this->_productLimitationJoinStore();
    //        $this->_eventManager->dispatch(
    //            'catalog_product_collection_apply_limitations_after',
    //            ['collection' => $this]
    //        );
    //        return $this;
    //    }

    public function applyCategories($categories) 
    {
        $this->_productLimitationFilters['category_id'] = $categories;
        $this->_applyProductLimitations();
    }
}
