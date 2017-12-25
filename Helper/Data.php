<?php

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;
use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * Data constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
    
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
        $this->request = $context->getRequest();
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

    /**
     * @return int
     */
    public function isUseAutoScrolling()
    {
        return (int) $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_AUTOSCROLLING);
    }

    /**
     * @return int
     */
    public function isUseBackToTopButton()
    {
        $value = (int)$this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP);

        if ($value) {
            return 'GoMage_Navigation::layer/back_to_top.phtml';
        }

        return '';
    }

    /**
     * @return int
     */
    public function getBackToTopSpeed()
    {
        return (int) $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP_SPEED);
    }

    /**
     * @return int
     */
    public function getBackToTopAction()
    {
        return (int) $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP_ACTION);
    }

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    public function isShowValueQty()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_QTY);
    }

    /**
     * @return mixed
     */
    public function isAddFilterResultsToUrl()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_HASH);
    }

    /**
     * @return mixed
     */
    public function isUseFriendlyUrls()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_FRIENDLY_URLS);
    }

    /**
     * @return mixed
     */
    public function isShowAppliedValuesInResults()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_SHOW_APPLIED_VALUES);
    }

    public function getPagerTheme()
    {
        $isAjax = $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGINATION_ENABLED);
        if ($isAjax || $this->isUseAutoScrolling()) {
            return 'GoMage_Navigation::html/pager.phtml';
        } else {
            return 'Magento_Theme::html/pager.phtml';
        }
    }
}