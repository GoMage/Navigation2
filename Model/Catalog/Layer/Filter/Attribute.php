<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

class Attribute extends \Magento\Catalog\Model\Layer\Filter\Attribute implements FilterInterface
{
    protected $attributeProperties;
    protected $request;
    protected $catalogSession;
    protected $helper;
    protected $productCollectionFactory;
    protected $filter;
    protected $options;

    /**
     * Attribute constructor.
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory $filterAttributeFactory
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\Filter\StripTags $tagFilter
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory $filterAttributeFactory,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\Filter\StripTags $tagFilter,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Model\Session $catalogSession,
        \GoMage\Navigation\Helper\Data $helper,
        \Magento\Catalog\Model\Layer\Category\CollectionFilter $filter,
        //\Magento\Catalog\Model\ResourceModel\CollectionFactory $productCollectionFactory,
        array $data = []
    )
    {
        $this->_resource = $filterAttributeFactory->create();
        $this->string = $string;
        $this->_requestVar = 'attribute';
        $this->tagFilter = $tagFilter;
        $this->request = $request;
        $this->catalogSession = $catalogSession;
        $this->helper = $helper;
        $this->filter = $filter;
        //$this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $filterAttributeFactory, $string, $tagFilter, $data);
    }

    /**
     * @return mixed
     */
    public function getSwatchInputType()
    {
        if ($additional_data = $this->getData('attribute_model')->getData('additional_data')) {
            if (json_decode($additional_data)) {
                $data = json_decode($additional_data);
                if ($data->swatch_input_type) {
                    return $data->swatch_input_type;
                }
            }
        }
        return false;
    }


    /**
     * @return mixed
     */
    public function canShowCheckbox()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canShowMinimized()
    {
        return true;
    }

    /**
     * Apply attribute option filter to product collection
     *
     * @param   \Magento\Framework\App\RequestInterface $request
     * @return  $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $filter = $request->getParam($this->_requestVar);
        if (is_array($filter)) {
            return $this;
        }

        if ($filter) {
            $filters = $this->getFormattedFilters($filter);
            $attribute = $this->getAttributeModel();
            $collection = $this->getLayer()
                ->getProductCollection();

            if(!empty($this->getSwatchInputType())) {

                $newCollection = $this->getLayer()->getCollectionProvider()->getCollection($this->getLayer()->getCurrentCategory());
                $newCollection->updateSearchCriteriaBuilder();
                $this->getLayer()->prepareProductCollection($newCollection);
                $newCollection->addFieldToFilter($attribute->getAttributeCode(), array('in' => $filters));
                $newCollection->load();
                $ids = $newCollection->getAllIds();
                if(!empty($ids)) {
                    $collection->addAttributeToFilter('entity_id', array('in' => $ids));
                    $collection->addAdditionalFilter($attribute->getAttributeCode(), array('in' => $filters));
                }
            } else {
                $collection->addFieldToFilter($attribute->getAttributeCode(), array('in' => $filters));
            }

            foreach ($filters as $filterItem) {
                $text = $this->getOptionText($filterItem);
                $this->getLayer()->getState()->addFilter($this->_createItem($text, $filterItem));
            }
        }

        return $this;
    }

    public function getUsedOptions()
    {
        return explode('_', $this->request->getParam($this->_requestVar));
    }


    /**
     * Get data array for building attribute filter items
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return array
     */
    protected function _getItemsData()
    {
        if (!$this->request->getParam($this->_requestVar)) {
            return parent::_getItemsData();
        }

        if ($this->getGomageFilterType() == \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN) {
            return parent::_getItemsData();
        }

        $attribute = $this->getAttributeModel();
        $productCollection = $this->getLayer()
            ->getProductCollection();

        $collection = $this->getLayer()->getCollectionProvider()->getCollection($this->getLayer()->getCurrentCategory());
        $collection->updateSearchCriteriaBuilder();
        $this->getLayer()->prepareProductCollection($collection);

        foreach ($productCollection->getAddedFilters() as $field => $condition) {

            if ($this->getAttributeModel()->getAttributeCode() == $field) {
                continue;
            }
            $collection->addFieldToFilter($field, $condition);
        }

        $productCollection->getFacetedData($attribute->getAttributeCode());
        $optionsFacetedData = $collection->getFacetedData($attribute->getAttributeCode());

        $optionsCount = $this->_getResource()->getCount($this);
        $usedOptions = $this->getUsedOptions();
        foreach ($attribute->getFrontend()->getSelectOptions() as $option) {

            if (empty($option['value'])) {
                continue;
            }

            if (in_array($option['value'], $usedOptions) && !empty($optionsCount[$option['value']])) {
                $optionsFacetedData[$option['value']]['count'] = $optionsCount[$option['value']];
            }

            if (empty($optionsFacetedData[$option['value']]['count'])) {
                continue;
            }

            $this->itemDataBuilder->addItemData(
                $this->tagFilter->filter($option['label']),
                $option['value'],
                isset($optionsFacetedData[$option['value']]['count']) ? $optionsFacetedData[$option['value']]['count'] : 0
            );
        }

        return $this->itemDataBuilder->build();
    }

    protected function getFormattedFilters($filter)
    {
        $filters = explode('_', $filter);
        if (!$this->helper->isUseFriendlyUrls()) {
            return $filters;
        }

        if(empty($this->options)) {
            foreach ($this->getAttributeModel()->getFrontend()->getSelectOptions() as $option) {
                $this->options[mb_strtolower(str_replace(' ', '+', $option['label']))] = $option['value'];
            }
        }

        $params = [];
        foreach ($filters as $item) {

            $params[] = $this->options[htmlentities($item)];
        }

        return $params;
    }
}
