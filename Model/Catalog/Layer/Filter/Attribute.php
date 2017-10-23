<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

class Attribute extends \Magento\Catalog\Model\Layer\Filter\Attribute implements FilterInterface
{
    protected $attributeProperties;
    protected $request;
    protected $catalogSession;

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
        array $data = []
    )
    {
        $this->_resource = $filterAttributeFactory->create();
        $this->string = $string;
        $this->_requestVar = 'attribute';
        $this->tagFilter = $tagFilter;
        $this->request = $request;
        $this->catalogSession = $catalogSession;
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
            $filters = explode('_', $filter);
            $attribute = $this->getAttributeModel();
            $collection = $this->getLayer()
                ->getProductCollection();

            $collection->addFieldToFilter($attribute->getAttributeCode(), array('in' => $filters));

            $data = $this->catalogSession->getData('used_filters');
            if (empty($data)) {
                $data = [];
            }
            $data[] = [$attribute->getAttributeCode() => array('in' => $filters)];

            $this->catalogSession->setData('used_filters', $data);

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

        $optionsFacetedData = $collection->getFacetedData($attribute->getAttributeCode());
        $originalFacetedData = $productCollection->getFacetedData($attribute->getAttributeCode());

        if ($attribute->getFrontendInput() == 'multiselect') {
            $optionsFacetedData = $this->calculateOptionsCount($originalFacetedData, $optionsFacetedData);
        }

        $usedOptions = $this->getUsedOptions();
        foreach ($attribute->getFrontend()->getSelectOptions() as $option) {

            if (empty($option['value'])) {
                continue;
            }

            if (in_array($option['value'], $usedOptions) && !empty($originalFacetedData[$option['value']]['count'])) {
                $optionsFacetedData[$option['value']]['count'] = $originalFacetedData[$option['value']]['count'];
            }

            if (empty($optionsFacetedData[$option['value']]['count'])) {
                continue;
            }
            $this->itemDataBuilder->addItemData(
                $this->tagFilter->filter($option['label']),
                $option['value'],
                isset($optionsFacetedData[$option['value']]['count']) ? '+' . $optionsFacetedData[$option['value']]['count'] : 0
            );
        }

        return $this->itemDataBuilder->build();
    }

    protected function calculateOptionsCount($originalFacetedData, $optionsFacetedData)
    {
        foreach ($originalFacetedData as $key => $optionData) {
            $optionsFacetedData[$key]['count'] = $optionsFacetedData[$key]['count'] - $optionData['count'];
            if ($optionsFacetedData[$key]['count'] <= 0) {
                unset($optionsFacetedData[$key]['count']);
            }
        }

        return $optionsFacetedData;
    }
}
