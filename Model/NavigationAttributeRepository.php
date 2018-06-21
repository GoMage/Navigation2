<?php
namespace GoMage\Navigation\Model;

use GoMage\Navigation\Api\NavigationAttributeRepositoryInterface;
use GoMage\Navigation\Api\Data\NavigationAttributeInterface;
use GoMage\Navigation\Model\NavigationAttributeFactory;
use GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory;
use GoMage\Navigation\Model\ResourceModel\NavigationAttribute as NavigationAttributeResource;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

/**
 * Class NavigationAttributeRepository
 * @package GoMage\Navigation\Model
 */
class NavigationAttributeRepository implements \GoMage\Navigation\Api\NavigationAttributeRepositoryInterface
{
    /**
     * @var \GoMage\Navigation\Model\NavigationAttributeFactory
     */
    protected $objectFactory;

    /**
     * @var NavigationAttributeResource
     */
    protected $navigationAttributeResource;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param \GoMage\Navigation\Model\NavigationAttributeFactory $objectFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        NavigationAttributeFactory $objectFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        NavigationAttributeResource $navigationAttributeResource
    ) {
    
        $this->objectFactory        = $objectFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param NavigationAttributeInterface $object
     * @return NavigationAttributeInterface
     * @throws CouldNotSaveException
     */
    public function save(NavigationAttributeInterface $object)
    {
        try {
            $object->save();
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $object;
    }

    /**
     * @param $id
     * @return NavigationAttribute
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $object = $this->objectFactory->create();
        $this->navigationAttributeResource->load($object, $id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }
        return $object;
    }

    /**
     * @param NavigationAttributeInterface $object
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(NavigationAttributeInterface $object)
    {
        try {
            $object->delete();
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
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
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);
        return $searchResults;
    }
}
