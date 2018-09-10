<?php
namespace GoMage\Navigation\Api;

use GoMage\Navigation\Api\Data\NavigationAttributeInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface NavigationAttributeRepositoryInterface
{
    public function save(NavigationAttributeInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(NavigationAttributeInterface $page);

    public function deleteById($id);
}
