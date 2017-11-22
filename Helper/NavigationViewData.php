<?php

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;
use Magento\Framework\App\Helper\Context;

class NavigationViewData extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * CategoryData constructor.
     * @param Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {

        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @param $param
     * @return mixed
     * return comfiguretion module
     */
    public function getScopeData($param, $section = SystemConfigInterface::SYSTEM_CONFIG_SECTION)
    {
        return $this->scopeConfig->getValue(
            $section . SystemConfigInterface::SYSTEM_CONFIG_SLASH. $param,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
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
}
