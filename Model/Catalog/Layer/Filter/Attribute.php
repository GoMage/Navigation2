<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

/**
 * Class Attribute
 *
 * @package GoMage\Navigation\Model\Catalog\Layer\Filter
 */
class Attribute extends \Magento\Catalog\Model\Layer\Filter\Attribute implements FilterInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    protected $_requestVarOrder;
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

    protected $ids;

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory                    $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface                         $storeManager
     * @param \Magento\Catalog\Model\Layer                                       $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder               $itemDataBuilder
     * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\AttributeFactory $filterAttributeFactory
     * @param \Magento\Framework\Stdlib\StringUtils                              $string
     * @param \Magento\Framework\Filter\StripTags                                $tagFilter
     * @param \Magento\Framework\App\RequestInterface                            $request
     * @param \Magento\Catalog\Model\Session                                     $catalogSession
     * @param \GoMage\Navigation\Helper\Data                                     $helper
     * @param \GoMage\Navigation\Helper\Url                                      $urlHelper
     * @param \Magento\Catalog\Model\Layer\Category\CollectionFilter             $filter
     * @param array                                                              $data
     */
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
        \Magento\Framework\Stdlib\StringUtils $string,
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
    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     */
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

            if (!empty($this->getSwatchInputType())) {
                $newCollection = $this->getLayer()->getCollectionProvider()->getCollection($this->getLayer()->getCurrentCategory());
                $newCollection->updateSearchCriteriaBuilder();
                $this->getLayer()->prepareProductCollection($newCollection);
                $newCollection->addFieldToFilter($attribute->getAttributeCode(), ['in' => $filters]);
                $newCollection->load();
                $ids = $newCollection->getAllIds();
                if (!empty($ids)) {
                    $collection->addAttributeToFilter('entity_id', ['in' => $ids]);
                    $this->ids = $ids;
                    $collection->addAdditionalFilter($attribute->getAttributeCode(), ['in' => $filters]);
                }
            } else {
                $collection->addFieldToFilter($attribute->getAttributeCode(), ['in' => $filters]);
            }

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


    /**
     * @param $filter
     * @return array
     */
    protected function getFormattedFilters($filter)
    {
        $filters = explode('_', $filter);
        if (!$this->helper->isUseFriendlyUrls()) {
            return $filters;
        }

        if (empty($this->options)) {
            foreach ($this->getAttributeModel()->getFrontend()->getSelectOptions() as $option) {
                $this->options[html_entity_decode($this->formatItemName($option['label']))] = trim($option['value']);
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
        return mb_strtolower(str_replace(' ', '+', $name));
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

}
