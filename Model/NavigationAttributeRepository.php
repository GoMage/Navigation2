<?php


namespace GoMage\Navigation\Model;

use GoMage\Navigation\Api\NavigationAttributeRepositoryInterface;
use GoMage\Navigation\Api\Data\NavigationAttributeSearchResultsInterfaceFactory;
use GoMage\Navigation\Api\Data\NavigationAttributeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use GoMage\Navigation\Model\ResourceModel\NavigationAttribute as ResourceNavigationAttribute;
use GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory as NavigationAttributeCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class NavigationAttributeRepository implements navigationAttributeRepositoryInterface
{

    protected $resource;

    protected $navigationAttributeFactory;

    protected $navigationAttributeCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataNavigationAttributeFactory;

    private $storeManager;


    /**
     * @param ResourceNavigationAttribute $resource
     * @param NavigationAttributeFactory $navigationAttributeFactory
     * @param NavigationAttributeInterfaceFactory $dataNavigationAttributeFactory
     * @param NavigationAttributeCollectionFactory $navigationAttributeCollectionFactory
     * @param NavigationAttributeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceNavigationAttribute $resource,
        NavigationAttributeFactory $navigationAttributeFactory,
        NavigationAttributeInterfaceFactory $dataNavigationAttributeFactory,
        NavigationAttributeCollectionFactory $navigationAttributeCollectionFactory,
        NavigationAttributeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->navigationAttributeFactory = $navigationAttributeFactory;
        $this->navigationAttributeCollectionFactory = $navigationAttributeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataNavigationAttributeFactory = $dataNavigationAttributeFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \GoMage\Navigation\Api\Data\NavigationAttributeInterface $navigationAttribute
    ) {
        /* if (empty($navigationAttribute->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $navigationAttribute->setStoreId($storeId);
        } */
        try {
            $navigationAttribute->getResource()->save($navigationAttribute);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the navigationAttribute: %1',
                $exception->getMessage()
            ));
        }
        return $navigationAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($attributeId)
    {
        $navigationAttribute = $this->navigationAttributeFactory->create();
        $navigationAttribute->getResource()->load($navigationAttribute, $attributeId);
        if (!$navigationAttribute->getId()) {
            throw new NoSuchEntityException(__('NavigationAttribute with id "%1" does not exist.', $attributeId));
        }
        return $navigationAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->navigationAttributeCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \GoMage\Navigation\Api\Data\NavigationAttributeInterface $navigationAttribute
    ) {
        try {
            $navigationAttribute->getResource()->delete($navigationAttribute);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the NavigationAttribute: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($attributeId)
    {
        return $this->delete($this->getById($attributeId));
    }
}
