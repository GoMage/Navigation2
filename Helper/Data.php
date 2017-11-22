<?php

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;
use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{


    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    protected $_request;

    protected $filters;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Url $urlHelper
     * @param \Magento\Framework\UrlInterface $urlHelper
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Request\Http $request
        //\Magento\Framework\UrlInterface $url,
    ) {
    
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
        $this->_request = $request;
    }


    /**
     * @param $param
     * @return mixed
     * return comfiguretion module
     */
    public function getScopeData($param, $section = SystemConfigInterface::SYSTEM_CONFIG_SECTION)
    {
        return $this->_scopeConfig->getValue(
            $section . SystemConfigInterface::SYSTEM_CONFIG_SLASH. $param,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isEnable()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_ENABLE);
    }

    /**
     * @return mixed
     */
    public function getShowShopByIn()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_SHOW_SHOP_BY_IN);
    }

    public function isUseAutoScrolling()
    {
        return (int) $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_AUTOSCROLLING);
    }

    public function isUseBackToTopButton()
    {
        return (int) $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP);
    }

    public function getBackToTopSpeed()
    {
        return (int) $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP_SPEED);
    }

    public function getBackToTopAction()
    {
        return (int) $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP_ACTION);
    }

    public function getContentFilterType()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_CONTENT_FILTER_TYPE);
    }

    /**
     * @return mixed
     */
    public function isPagerButton()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGER_BUTTON);
    }

    /**
     * @return mixed
     */
    public function isShowPager()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGER);
    }

    public function isShowValueQty()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_QTY);
    }

    public function isAddFilterResultsToUrl()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_HASH);
    }

    public function isUseFriendlyUrls()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_FRIENDLY_URLS);
    }

    public function isShowAppliedValuesInResults()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_SHOW_APPLIED_VALUES);
    }

    public function getNavigationViewBlockBackgroundColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_BACKGROUND);
    }

    public function getNavigationViewBlockCategoriesBackgroundColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CATEGORY_BACKGROUND);
    }

    public function getNavigationViewButtonsBackgroundColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_BUTTON_BACKGROUND);
    }

    public function isNavigationViewButtonsGradient()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_BUTTON_GRADIENT);
    }

    public function getNavigationViewButtonsBackgroundColor2()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_BUTTON_BACKGROUND2);
    }

    public function getNavigationViewButtonsTextColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_BUTTON_TEXT_COLOR);
    }

    public function getNavigationViewTooltipBackgroundColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_BACKGROUND);
    }

    public function getNavigationViewTooltipWindowBackgroundColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_WINDOW_BACKGROUND);
    }

    public function isNavigationViewTooltipShowOnClick()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_SHOW_EVENT);
    }

    public function isNavigationViewTooltipShowOnMouseOver()
    {
        $value = $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_SHOW_EVENT);

        return (!$value) ? true : false;
    }

    public function isNavigationViewTooltipHideOnMouseOut()
    {
        $value = $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_HIDE_EVENT);

        return (!$value) ? true : false;
    }

    public function isNavigationViewTooltipHideOnCloseButton()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_TOOLTIP_HIDE_EVENT);
    }

    public function getNavigationViewSliderLineColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_LINE_COLOR);
    }

    public function getNavigationViewSliderLineHeight()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_LINE_HEIGHT);
    }

    public function getNavigationViewSliderElementColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_ELEMENT_COLOR);
    }

    public function getNavigationViewSliderElementWidth()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_ELEMENT_WIDTH);
    }

    public function getNavigationViewSliderElementHeight()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_ELEMENT_HEIGHT);
    }

    public function getNavigationViewSliderElementRadius()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_NAVIGATION_VIEW_CONFIG_SLIDER_ELEMENT_RADIUS);
    }

    public function getRemoveUrl($route, $data)
    {
        $queryParams = is_array($this->_request->getParams()) ? $this->_request->getParams() : [];
        $temp = explode(',', $queryParams[$data['request_var']]);
        $queryParams[$data['request_var']] = implode(',', array_diff($temp, [$data['request_value']]));

        $params = [
            '_nosid'        => true,
            '_current'      => true,
            '_secure'       => true,
            '_use_rewrite'  => true,
            '_query'        => $queryParams,
            '_escape'       => false,

        ];

        return $this->_urlBuilder->getUrl($route, $params);
    }

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

    public function getBlockLocation($type)
    {
        $place[\GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN] = 'left';
        $place[\GoMage\Navigation\Model\Config\Source\Place::CONTENT] = 'content';
        $place[\GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN] = 'right';

        if (empty($place[$type])) {
            throw new Exception(__('Block position is not set for ' . (int) $type . ' type'));
        }

        return $place[$type];
    }
}
