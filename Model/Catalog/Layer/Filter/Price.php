<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class Price extends \Magento\Catalog\Model\Layer\Filter\Price implements FilterInterface
{


    /**
     * @var \Magento\Catalog\Model\Layer\Filter\DataProvider\Price
     */
    protected $dataProvider;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $_layer;

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
    protected $_request;


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
        \GoMage\Navigation\Model\ResourceModel\Layer\Filter\Price $resource,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory,
        \Magento\Catalog\Model\Layer\Filter\Price\Range $range,
        \Magento\Catalog\Model\Layer\Filter\Price\Render $render,
        \Magento\Framework\App\RequestInterface $request,
        array $data = []
    )
    {
        parent::__construct($filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $resource,
            $customerSession,
            $priceAlgorithm,
            $priceCurrency,
            $algorithmFactory,
            $dataProviderFactory,
            $data);
        $this->_layer = $layer;
        $this->storeManager = $storeManager;
        $this->algorithmFactory = $algorithmFactory;
        $this->dataProvider = $dataProviderFactory->create(['layer' => $this->getLayer()]);
        $this->range = $range;
        $this->render = $render;
        $this->resource = $resource;
        $this->_request = $request;
    }


    /**
     * {@inheritdoc}
     */
    public function isAjax()
    {
        return $this->getData('attribute_model')->getIsAjax();
    }

    /**
     * @return mixed
     */
    public function getNavigation()
    {
        return $this->getData('attribute_model')->getNavigation();
    }

    /**
     * @return float
     */
    public function getMaxBasePrice()
    {
        return $this->resource->getMaxPrice();
    }

    /**
     * @return float
     */
    public function getMinBasePrice()
    {
        return $this->resource->getMinPrice();
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
     * Get data for build price filter items
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getItemsData()
    {
        return $this->getItemsData((array)$this->dataProvider->getInterval(), $this->dataProvider->getAdditionalRequestData());
    }

    /**
     * @return number
     */
    private function getRange()
    {
        $maxPrice = $this->getMaxPriceInt();
        $index = 1;
        do {
            $range = pow(10, strlen(floor($maxPrice)) - $index);
            $items = $this->resource->getCount($range);
            $index++;
        } while ($range > \Magento\Catalog\Model\Layer\Filter\Dynamic\Auto::MIN_RANGE_POWER && count($items) < 2);

        return $range;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsData(array $intervals = [], $additionalRequestData = '')
    {
        $data = [];
        if (empty($intervals)) {
            $range = $this->range->getPriceRange();

            if (!$range) {
                $range = $this->getRange();
                $dbRanges = $this->resource->getCount($range);

                $data = $this->render->renderRangeData($range, $dbRanges);

            }
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $data = $this->getItemsData();
        $items = [];
        foreach ($data as $itemData) {
            $items[] = $this->_createItem($itemData['label'], $itemData['value'], $itemData['count']);
        }
        return $items;
    }

    /**
     * @return mixed|string
     */
    public function getPriceValue()
    {
        switch ($this->getNavigation()) {
            case NavigationInterface::SLIDER:
                if ($value = $this->_request->getParam($this->getRequestVar())) {
                    return str_replace('-', ';', $value);
                }
                return $this->getMinBasePrice() . ';' . $this->getMaxBasePrice();
            default:
                return $this->_request->getParam($this->getRequestVar());

        }

    }

    public function canShowMinimized()
    {
        return false;
    }

}
