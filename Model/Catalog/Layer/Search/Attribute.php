<?php

/**
 * GoMage.com
 *
 * GoMage Navigation M2
 *
 * @category  Extension
 * @copyright Copyright (c) 2018-2018 GoMage.com (https://www.gomage.com)
 * @author    GoMage.com
 * @license   https://www.gomage.com/licensing  Single domain license
 * @terms     of use https://www.gomage.com/terms-of-use
 * @version   Release: 2.0.0
 * @since     Class available since Release 2.0.0
 */

namespace GoMage\Navigation\Model\Catalog\Layer\Search;

use GoMage\Navigation\Model\Catalog\Layer\Filter\FilterInterface;

/**
 * Class Attribute
 *
 * @package GoMage\Navigation\Model\Catalog\Layer\Filter
 */
class Attribute extends \Magento\CatalogSearch\Model\Layer\Filter\Attribute implements FilterInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    protected $_requestVarOrder;
    protected $itemsInitiels;
    /**
     * @var \Magento\Catalog\Model\Session
     */
    protected $catalogSession;

    /**
     * @var \Magento\Framework\Filter\StripTags
     */
    protected $tagFilter;
    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Catalog\Model\Layer\Category\CollectionFilter
     */
    protected $filter;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $urlHelper;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Filter\StripTags $tagFilter,
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Helper\Data $helper,
        \GoMage\Navigation\Helper\Url $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory $filterAttributeFactory,
        \Magento\Catalog\Model\Layer\Category\CollectionFilter $filter,
        array $data = []
    ) {
        $this->_requestVar = 'attribute';
        $this->_requestVarOrder = 'product_list_order';
        $this->request = $request;
        $this->tagFilter = $tagFilter;
        $this->helper = $helper;
        $this->filter = $filter;
        $this->urlHelper = $urlHelper;
        $this->_resource = $filterAttributeFactory->create();
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $tagFilter, $data);
    }

    /**
     * @return mixed
     */
    public function getSwatchInputType()
    {
        if ($additional_data = $this->getData('attribute_model')->getData('additional_data')) {
            if (json_decode($additional_data)) {
                $data = json_decode($additional_data);
                if (!empty($data->swatch_input_type)) {
                    return $data->swatch_input_type;
                }
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isCategoryFilter()
    {
        return false;
    }

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->helper->isEnable()) {
            return parent::apply($request);
        }

        $filter = $request->getParam($this->_requestVar);
        if (is_array($filter)) {
            return $this;
        }

        if ($filter) {
            $filters = $this->getFormattedFilters($filter);
            $attribute = $this->getAttributeModel();
            $collection = $this->getLayer()
                ->getProductCollection();
            $collection->addFieldToFilter($attribute->getAttributeCode(), ['in' => $filters]);
            foreach ($filters as $filterItem) {
                $text = $this->getOptionText($filterItem);
                $this->getLayer()->getState()->addFilter($this->_createItem($text, $filterItem));
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getUsedOptions()
    {
        return explode('_', $this->request->getParam($this->_requestVar));
    }

    protected function _getItemsData()
    {
        if (!$this->helper->isEnable()) {
            return parent::_getItemsData();
        }

        if (!$this->request->getParam($this->_requestVar)) {
            return parent::_getItemsData();
        }

        if ($this->getGomageFilterType() == \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN) {
            return parent::_getItemsData();
        }

        $attribute = $this->getAttributeModel();
        /**
         * @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection
         */
        $productCollection = $this->getLayer()
            ->getProductCollection();

        $isAttributeFilterable =
        $this->getAttributeIsFilterable($attribute) === static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS;
        $collection = $this->getLayer()->getCollectionProvider()
            ->getCollection($this->getLayer()->getCurrentCategory());
        $collection->updateSearchCriteriaBuilder();
        $this->getLayer()->prepareProductCollection($collection);

        foreach ($productCollection->getAddedFilters() as $field => $condition) {
            if ($this->getAttributeModel()->getAttributeCode() == $field) {
                continue;
            }
            $collection->addFieldToFilter($field, $condition);
        }
        $optionsFacetedData = $collection->getFacetedData($attribute->getAttributeCode());
        if (count($optionsFacetedData) === 0 && !$isAttributeFilterable) {
            return $this->itemDataBuilder->build();
        }

        $productSize = $productCollection->getSize();

        $options = $attribute->getFrontend()
            ->getSelectOptions();
        foreach ($options as $option) {
            $this->buildOptionData($option, $isAttributeFilterable, $optionsFacetedData, $productSize);
        }
        $this->itemsInitiels = $this->itemDataBuilder->build();
        return $this->itemsInitiels;
    }

    protected function getFormattedFilters($filter)
    {
        $filters = explode('_', $filter);
        if (!$this->helper->isUseFriendlyUrls()) {
            return $filters;
        }

        if (empty($this->options)) {
            foreach ($this->getAttributeModel()->getFrontend()->getSelectOptions() as $option) {
                $this->options[$this->formatItemName($option['label'])] = trim($option['value']);
            }
        }

        $params = [];
        foreach ($filters as $item) {
            if (isset($this->options[$item])) {
                $params[] = $this->options[$item];
            }
        }
        $params = array_unique($params);
        return $params;
    }

    /**
     * Retrieve resource instance
     *
     * @return \Magento\Catalog\Model\ResourceModel\Layer\Filter\Attribute
     */
    protected function _getResource()
    {
        return $this->_resource;
    }

    /**
     * @param $name
     * @return mixed|string
     */
    protected function formatItemName($name)
    {
        return urlencode(mb_strtolower($name));
    }

    /**
     * @return string
     */
    public function getRemoveUrl()
    {
        return $this->urlHelper->getFilterRemoveUrl($this);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if ($this->request->getParam($this->_requestVar)) {
            return true;
        }
        return false;
    }

    /**
     * Build option data
     *
     * @param  array   $option
     * @param  boolean $isAttributeFilterable
     * @param  array   $optionsFacetedData
     * @param  int     $productSize
     * @return void
     */
    private function buildOptionData($option, $isAttributeFilterable, $optionsFacetedData, $productSize)
    {
        $value = $this->getOptionValue($option);
        if ($value === false) {
            return;
        }
        $count = $this->getOptionCount($value, $optionsFacetedData);
        if ($isAttributeFilterable && (!$this->isOptionReducesResults($count, $productSize) || $count === 0)) {
            return;
        }

        $this->itemDataBuilder->addItemData(
            $this->tagFilter->filter($option['label']),
            $value,
            $count
        );
    }

    /**
     * Retrieve count of the options
     *
     * @param  int|string $value
     * @param  array      $optionsFacetedData
     * @return int
     */
    private function getOptionCount($value, $optionsFacetedData)
    {
        return isset($optionsFacetedData[$value]['count'])
            ? (int)$optionsFacetedData[$value]['count']
            : 0;
    }

    /**
     * Retrieve option value if it exists
     *
     * @param  array $option
     * @return bool|string
     */
    private function getOptionValue($option)
    {
        if (empty($option['value']) && !is_numeric($option['value'])) {
            return false;
        }
        return $option['value'];
    }

    /**
     * Checks whether the option reduces the number of results
     *
     * @param  int $optionCount Count of search results with this option
     * @param  int $totalSize   Current search results count
     * @return bool
     */
    protected function isOptionReducesResults($optionCount, $totalSize)
    {
        return true;
    }
}
