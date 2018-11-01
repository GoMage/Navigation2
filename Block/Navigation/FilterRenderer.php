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
 * @version   Release: 1.0.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Block\Navigation;

use GoMage\Navigation\Model\Catalog\Layer\Filter\FilterInterface;
use Magento\Framework\View\Element\Template;
use GoMage\Navigation\Model\Config\Source\NavigationInterface;
use  GoMage\Navigation\Helper\Config\SystemConfigInterface;

class FilterRenderer extends Template implements FilterRendererInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var $filterType
     */
    protected $filterType;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute
     */
    protected $renderLayered;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $urlHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \GoMage\Navigation\Block\Navigation\Tooltip
     */
    public $tooltip;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;
    /**
     * @var \GoMage\Navigation\Helper\NavigationViewData
     */
    protected $navigationViewHelper;

    /**
     * FilterRenderer constructor.
     * @param Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Swatches\Block\LayeredNavigation\RenderLayered $renderLayered
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \GoMage\Navigation\Helper\Url $urlHelper
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Swatches\Block\LayeredNavigation\RenderLayered $renderLayered,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \GoMage\Navigation\Helper\Url $urlHelper,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper,
        array $data = []
    )
    {
        $this->coreRegistry = $coreRegistry;
        $this->request = $context->getRequest();
        $this->urlHelper = $urlHelper;
        $this->dataHelper = $dataHelper;
        $this->renderLayered = $renderLayered;
        $this->storeManager = $context->getStoreManager();
        $this->navigationViewHelper = $navigationViewHelper;
        $this->layerResolver = $layerResolver;

        parent::__construct($context, $data);
    }

    /**
     * @return \GoMage\Navigation\Helper\Data
     */
    public function getHelper()
    {
        return $this->dataHelper;
    }

    /**
     * @return \GoMage\Navigation\Helper\NavigationViewData
     */
    public function getNavigationViewHelper()
    {
        return $this->navigationViewHelper;
    }

    /**
     * @param $filter
     * @return bool
     */
    public function renderInCurrentCategory($filter)
    {
        $categories = explode(',', $filter->getData('gomage_is_exclude_categories'));
        if (in_array($this->_getCategory()->getId(), $categories)) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    protected function _getCategory()
    {
        $category = $this->coreRegistry->registry('current_category');
        if (!$category) {
            $category = $this->layerResolver->get()->getCurrentCategory();
        }
        return $category;
    }

    /**
     * @param FilterInterface $filter
     * @return string
     */
    public function render(FilterInterface $filter)
    {
        if (!$this->dataHelper->isEnable()) {
            return $this->setOriginaltemplate($filter);
        }

        $this->setTemplate($filter->getGomageFilterTemplate());
        $filters = [];
        $key = -1;
        foreach ($filter->getItems() as $item) {
            $key++;
            $this->checkIsActive($item, $filter->getRequestVar());
            $item->setGomageUrl($this->urlHelper->getItemUrl($item));
            $item->setGomageValue($this->urlHelper->getItemValue($item));
            $filters[$key] = $item;
        }
        $this->assign('filterItems', $filters);

        $html = $this->_toHtml();
        $this->assign('filterItems', []);
        return $html;
    }

    /**
     * @param $filter
     * @return string
     */
    protected function setOriginalTemplate($filter)
    {
        $this->setTemplate('Magento_LayeredNavigation::layer/filter.phtml');
        $this->assign('filterItems', $filter->getItems());
        $html = $this->_toHtml();
        $this->assign('filterItems', []);
        return $html;
    }


    /**
     * @return int
     */
    public function getStep()
    {
        return $this->dataHelper->getScopeData(SystemConfigInterface::SLIDER_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SLIDER_STEP);
    }

    /**
     * @return int
     */
    public function getRound()
    {
        return $this->dataHelper->getScopeData(SystemConfigInterface::SLIDER_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SLIDER_ROUND);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->filterType;
    }

    /**
     * @return string
     */
    public function getSliderSkin()
    {
        return NavigationInterface::SLIDER_SKIN;
    }

    /**
     * @return \Magento\Eav\Model\Entity\Attribute
     */
    public function getSwatch()
    {
        return $this->renderLayered;
    }

    /**
     * @param $item
     * @param $requestVar
     */
    public function checkIsActive($item, $requestVar)
    {
        $params = $this->request->getParam($requestVar);
        $params = explode('_', $params);

        $criteria = urlencode(mb_strtolower( $item->getLabel()));
        if ($this->dataHelper->isUseFriendlyUrls()
            && (in_array($criteria, $params)
                || in_array($criteria, $params))
        ) {
            $item->setIsActive(true);
        }

        if (!$this->dataHelper->isUseFriendlyUrls() && in_array($criteria, $params)
        ) {
            $item->setIsActive(true);
        }
    }

    /**
     * @param $filter
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTooltip($filter)
    {
        $tooltip = $this->getLayout()->createBlock('GoMage\Navigation\Block\Navigation\Tooltip');
        $tooltip->assign('filter', $filter);

        return $tooltip;
    }

    /**
     * @param $data
     * @param $items
     * @return mixed
     */
    public function prepareSwatchesData($data, $items)
    {
        foreach ($items as $item) {
            $data['options'][$item->getValue()]['link'] = $item->getGomageUrl();
            $data['options'][$item->getValue()]['gomage_value'] = $item->getGomageValue();
            $data['options'][$item->getValue()]['is_active'] = $item->getIsActive();
            $data['options'][$item->getValue()]['is_show'] = $item->isShowAppliedValues();
        }

        return $data;
    }
}
