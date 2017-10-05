<?php


namespace GoMage\Navigation\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface NavigationAttributeRepositoryInterface
{


    /**
     * Save NavigationAttribute
     * @param \GoMage\Navigation\Api\Data\NavigationAttributeInterface $navigationAttribute
     * @return \GoMage\Navigation\Api\Data\NavigationAttributeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \GoMage\Navigation\Api\Data\NavigationAttributeInterface $id
    );

    /**
     * Retrieve NavigationAttribute
     * @param string $navigationattributeId
     * @return \GoMage\Navigation\Api\Data\NavigationAttributeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve NavigationAttribute matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \GoMage\Navigation\Api\Data\NavigationAttributeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete NavigationAttribute
     * @param \GoMage\Navigation\Api\Data\NavigationAttributeInterface $navigationAttribute
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \GoMage\Navigation\Api\Data\NavigationAttributeInterface $navigationAttribute
    );

    /**
     * Delete NavigationAttribute by ID
     * @param string $navigationattributeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}
