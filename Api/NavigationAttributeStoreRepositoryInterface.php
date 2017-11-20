<?php
namespace GoMage\Navigation\Api;

use GoMage\Navigation\Api\Data\NavigationAttributeStoreInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface NavigationAttributeStoreRepositoryInterface
{
    public function save(NavigationAttributeStoreInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(NavigationAttributeStoreInterface $page);

    public function deleteById($id);
}
