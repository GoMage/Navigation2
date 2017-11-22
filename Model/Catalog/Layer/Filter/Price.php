<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class Price extends \Magento\Catalog\Model\Layer\Filter\Price implements FilterInterface
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

    protected $filterTemplates;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;


    /**
     * Price constructor.
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \GoMage\Navigation\Model\ResourceModel\Layer\Filter\Price $resource
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory
     * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory
     * @param \Magento\Catalog\Model\Layer\Filter\Price\Range $range
     * @param \Magento\Catalog\Model\Layer\Filter\Price\Render $render
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
        $this->dataHelper = $dataHelper;
    }

    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        parent::apply($request);
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

    public function canShowMinimized()
    {
        return true;
    }

    public function isActive()
    {
        return $this->request->getParam($this->getRequestVar());
    }

    public function getSingleValue()
    {
        if ($value = $this->request->getParam($this->getRequestVar())) {
            return str_replace('-', ';', $value);
        }

        return $this->getMinBasePrice() . ';' . $this->getMaxBasePrice();
    }

    public function getFilterType()
    {
        return static::FILTER_TYPE;
    }

    protected function _initItems()
    {

        $itemsArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::SLIDER);
        $itemsArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::SLIDER_INPUT);
        $itemsArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::INPUT);

        if (!in_array($this->getGomageFilterTemplate(), $itemsArray)) {
            return parent::_initItems();
        }

        $items[] = $this->_createItem('slider', '10-20', 1);
        $this->_items = $items;
        return $this;
    }

    public function getMinBasePrice()
    {
        return $this->getCleanCollection()->getMinPrice();
    }

    /**
     * @return float
     */
    public function getMaxBasePrice()
    {
        return $this->getCleanCollection()->getMaxPrice();
    }

    protected function getCleanCollection()
    {
        $collection = $this->layer->getCollectionProvider()->getCollection($this->layer->getCurrentCategory());
        $collection->updateSearchCriteriaBuilder();
        $this->layer->prepareProductCollection($collection);
        return $collection;
    }

    public function isShowAppliedValues()
    {
        if ($this->dataHelper->isShowAppliedValuesInResults() == \GoMage\Navigation\Model\Config\Source\Result::REMOVE && $this->isActive()
        && $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::SLIDER)) {
            return false;
        }

        return true;
    }
}
