<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 07.06.2018
 * Time: 06:41
 */

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;


/**
 * Class CategorySearch
 * @package GoMage\Navigation\Model\Catalog\Layer\Filter
 */
class CategorySearch extends Category
{

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->helper->isEnable()) {
            return parent::apply($request);
        }

        if(empty($request->getParam($this->getRequestVar()))) {
            return parent::apply($request);
        }

        $filters = $this->getFormattedFilters();

        if(empty($filters)) {
            return parent::apply($request);
        }
        $categoryForFacetsData = clone $this->getLayer()->getProductCollection();
        $this->coreRegistry->register('facets_collection', $categoryForFacetsData);
        $this->getLayer()->getProductCollection()->addCategoriesFilter(['in' => $filters]);
        foreach ($filters as $filter) {
            $this->dataProvider->setCategoryId($filter);
            $category = $this->dataProvider->getCategory();
            $this->getLayer()->getState()->addFilter($this->_createItem($category->getName(), $filter));
        }
        $mainCategory = $this->coreRegistry->registry('current_category');
        if(!$mainCategory) {
            $mainCategory = $this->layerResolver->get()->getCurrentCategory();
        }
        $this->dataProvider->setCategoryId($mainCategory->getId());
        return $this;
    }

    /**
     * @return array
     */
    protected function _getItemsData()
    {
        return [];
    }

    protected function getFormattedFilters()
    {
        $filters = explode('_', $this->request->getParam($this->getRequestVar()));
        if (!$this->helper->isUseFriendlyUrls()) {
            return $filters;
        }
        $mainCategory = $this->coreRegistry->registry('current_category');
        if(!$mainCategory) {
            $mainCategory = $this->layerResolver->get()->getCurrentCategory();
        }

        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addIsActiveFilter();

        $categoriesName = [];
        foreach ($collection as $category) {
            $parent = explode('/', $category->getPath());
            if($this->request->getParam('parent_cat_'.$category->getId())) {
                $requestParent = $this->request->getParam('parent_cat_'.$category->getId());
            } else {
                $requestParent = false;
            }
            if(!$requestParent && ($requestParent != $category->getParentCategory()->getId() || $requestParent!=$category)) {
                continue;
            }
            $categoriesName[html_entity_decode($this->formatCategoryName($category->getName()))] = $category->getId();
        }

        $params = [];

        foreach ($filters as $item) {
            $formatItem = $this->formatCategoryName($item);
            if(isset($categoriesName[$formatItem])) {
                $params[] = $categoriesName[$formatItem];
            }
        }
        // $params = array_unique($params);
        return $params;
    }
}