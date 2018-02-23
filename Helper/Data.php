<?php

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;
use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ALIGNMENT_VERTICAL = 0;
    const ALIGNMENT_HORIZONTAL = 1;
    const ALIGNMENT_2COLUMN = 2;
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
     * @param $item
     * @param $requestVar
     */
    public function checkIsActive($item, $requestVar)
    {
        $params = $this->request->getParam($requestVar);
        $params = explode('_', $params);

        $label = mb_strtolower(str_replace(' ', '+', htmlentities($item->getLabel())));
        if ($this->isUseFriendlyUrls() && in_array($label, $params)) {
            $item->setIsActive(true);
        }

        if (!$this->isUseFriendlyUrls() && in_array($item->getValue(), $params)) {
            $item->setIsActive(true);
        }
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
     * @return mixed
     */
    public function isShowEmptyCategory()
    {
        return !($this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_HIDE_EMPTY_CATEGORIES));
    }
    public function removeBlocCategoriesOrCategory () {
         if( $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_SHOW_SHOP_BY_IN)) {
                return 'gomage.categories';
         }
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

        if ($value && $this->isEnable()) {
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

    public function getOptionsAlignment($alignmentOption)
    {
        if (self::ALIGNMENT_2COLUMN === $alignmentOption) {
            return 'gan-align-2-columns';
        } elseif (self::ALIGNMENT_HORIZONTAL === $alignmentOption) {
            return 'gan-align-horizontally';
        } else  {
            return 'gan-align-default';
        }
    }

    public function isPaginationAjax ()
    {
       return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGINATION_ENABLED);
    }

    public function getPagerTheme()
    {
        $isAjax = $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGINATION_ENABLED);
        if (($isAjax || $this->isUseAutoScrolling()) && $this->isEnable()) {
            return 'GoMage_Navigation::html/pager.phtml';
        } else {
            return 'Magento_Theme::html/pager.phtml';
        }
    }
    public function getUrlCategory($params)
    {
        $arrCat = explode('_',$this->request->get('cat'));
        $cat = $this->request->get('cat');
        if($cat && !in_array($params['cat'], $arrCat)) {
            $params['cat'] = $params['cat'].'_'.$cat;
        }
        return $this->_urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $params]);
    }
    public function isThisActiveCategory($name) {
        $arrCat = explode(',',$this->request->get('cat'));
        if(in_array(strtolower($name),$arrCat)) {
           return true;
        }
        return false;
    }

    public function isInaCategoryRequest($name) {

        $arrCat = explode(',',$this->request->get('cat'));
        if(in_array(strtolower($name),$arrCat)) {
           return true;
        }
        return false;
    }
}