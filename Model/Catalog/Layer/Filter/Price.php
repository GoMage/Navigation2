<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class Price extends \Magento\CatalogSearch\Model\Layer\Filter\Price implements FilterInterface
{

    const FILTER_TYPE = 'price';
    /**
     * @var \Magento\Catalog\Model\Layer\Filter\DataProvider\Price
     */
    protected $dataProvider;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layer;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory
     */
    protected $algorithmFactory;

    /**
     * @var
     */
    protected $dataProviderFactory;

    /**
     * @var \Magento\Catalog\Model\Layer\Filter\Price\Range
     */
    protected $range;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Filter\Templates
     */
    protected $filterTemplates;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $urlHelper;

    /**
     * Price constructor.
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory
     * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory
     * @param \Magento\Catalog\Model\Layer\Filter\Price\Range $range
     * @param \Magento\Catalog\Model\Layer\Filter\Price\Render $render
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \GoMage\Navigation\Model\Config\Source\Filter\Templates $filterTemplates
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory,
        \Magento\Catalog\Model\Layer\Filter\Price\Range $range,
        \Magento\Catalog\Model\Layer\Filter\Price\Render $render,
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Model\Config\Source\Filter\Templates $filterTemplates,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Helper\Url $urlHelper,
        array $data = []
    ) {
    
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $resource,
            $customerSession,
            $priceAlgorithm,
            $priceCurrency,
            $algorithmFactory,
            $dataProviderFactory,
            $data
        );
        $this->layer = $layer;
        $this->storeManager = $storeManager;
        $this->algorithmFactory = $algorithmFactory;
        $this->dataProvider = $dataProviderFactory->create(['layer' => $this->getLayer()]);
        $this->range = $range;
        $this->render = $render;
        $this->request = $request;
        $this->filterTemplates = $filterTemplates;
        $this->priceCurrency = $priceCurrency;
        $this->dataHelper = $dataHelper;
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        $this->filtersPrepare($request);
        if (!$this->isShowAppliedValues()) {
            $this->_items = [];
        }

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getCurrentCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrency();
    }

    /**
     * @return mixed
     */
    public function getCurrencySymbol()
    {
        return $this->getCurrentCurrency()->getCurrencySymbol();
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->request->getParam($this->getRequestVar());
    }

    public function isCategoryFilter() {
        return false;
    }

    /**
     * @return mixed|string
     */
    public function getSingleValue()
    {
        if ($value = $this->request->getParam($this->getRequestVar())) {
            return str_replace('-', ';', $value);
        }
        return ($this->getMinBasePrice()) . ';' . $this->getMaxBasePrice();
    }

    /**
     * @return string
     */
    public function getFilterType()
    {
        return static::FILTER_TYPE;
    }

    /**
     * @return array
     */
    protected function getTemplatesArray()
    {
        $templatesArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::SLIDER);
        $templatesArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::SLIDER_INPUT);
        $templatesArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::INPUT);

        return $templatesArray;
    }

    /**
     * @return $this|\Magento\Catalog\Model\Layer\Filter\AbstractFilter
     */
    protected function _initItems()
    {
        if (!in_array($this->getGomageFilterTemplate(), $this->getTemplatesArray())) {
            return parent::_initItems();
        }

        $items[] = $this->_createItem('slider', $this->getMinBasePrice() . '-' . $this->getMaxBasePrice(), 1);
        $this->_items = $items;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinBasePrice()
    {
        $data = $this->getCleanCollection()->getMaxBasePrice()->getData();
        if(isset($data[0]) && isset($data[0]['min'])) {
            return $data[0]['min'];
        } else {
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function getMaxBasePrice()
    {
        $data = $this->getCleanCollection()->getMaxBasePrice()->getData();
        if(isset($data[0]) && isset($data[0]['max'])) {
            return $data[0]['max'];
        } else {
            return null;
        }

    }

    /**
     * @return mixed
     */
    protected function getCleanCollection()
    {
        $productCollection = $this->getLayer()->getProductCollection();
        $collection = $this->layer->getCollectionProvider()->getCollection($this->layer->getCurrentCategory());
        $collection->updateSearchCriteriaBuilder();
        $this->getLayer()->prepareProductCollection($collection);

        foreach ($productCollection->getAddedFilters() as $field => $condition) {
            if ($this->getAttributeModel()->getAttributeCode() == $field) {
                continue;
            }
            $collection->addFieldToFilter($field, $condition);
        }
        $this->layer->prepareProductCollection($collection);
        return $collection;
    }

    /**
     * @return bool
     */
    public function isShowAppliedValues()
    {
        if ($this->dataHelper->isShowAppliedValuesInResults() == \GoMage\Navigation\Model\Config\Source\Result::REMOVE && $this->isActive()
        && $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::SLIDER)) {
            return false;
        }

        return true;
    }

    /**
     * Prepare text of range label
     *
     * @param float|string $fromPrice
     * @param float|string $toPrice
     * @return float|\Magento\Framework\Phrase
     */
    protected function _renderRangeLabel($fromPrice, $toPrice)
    {
        $formattedFromPrice = $this->priceCurrency->format($fromPrice);
        if ($toPrice === '') {
            return __('%1 and above', $formattedFromPrice);
        } elseif ($fromPrice == $toPrice && $this->dataProvider->getOnePriceIntervalValue()) {
            return $formattedFromPrice;
        } else {
            return __('%1 - %2', $formattedFromPrice, $this->priceCurrency->format($toPrice));
        }
    }

    /**
     * @return mixed
     */
    public function getRemoveUrl()
    {
        return $this->urlHelper->getFilterRemoveUrl($this);
    }

    public function filtersPrepare (\Magento\Framework\App\RequestInterface $request) {

        /**
         * Filter must be string: $fromPrice-$toPrice
         */
        $filter = $request->getParam($this->getRequestVar());
        if (!$filter || is_array($filter)) {
            return $this;
        }

        $filterParams = explode(',', $filter);
        $filter = $this->dataProvider->validateFilter($filterParams[0]);
        if (!$filter) {
            return $this;
        }

        $this->dataProvider->setInterval($filter);
        $priorFilters = $this->dataProvider->getPriorFilters($filterParams);
        if ($priorFilters) {
            $this->dataProvider->setPriorIntervals($priorFilters);
        }

        list($from, $to) = $filter;

        $this->getLayer()->getProductCollection()->addFieldToFilter(
            'price',
            ['from' => $from, 'to' =>  empty($to) || $from == $to ? $to : $to]
        );

        $this->getLayer()->getState()->addFilter(
            $this->_createItem($this->_renderRangeLabel(empty($from) ? 0 : $from, $to), $filter)
        );

        return $this;
    }
}
