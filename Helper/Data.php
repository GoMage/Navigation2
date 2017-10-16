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

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param Url $urlHelper
     * @param \Magento\Framework\UrlInterface $urlHelper
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \GoMage\Navigation\Helper\Url $urlHelper,
        \Magento\Framework\App\Request\Http $request
        //\Magento\Framework\UrlInterface $url,
    )
    {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
        $this->_urlHelper = $urlHelper;
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
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_ENABLE
        );
    }

    /**
     * @return mixed
     */
    public function isPagerButton(){
        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGER_BUTTON
        );
    }

    public function isMobileDevice()
    {
        //$detect = new GoMage\MobileDetect\Detect();
       // return $detect->isMobile();
    }

    public function isFriendlyUrl()
    {
        return false;
        //return $this->isGomageNavigation() && Mage::getStoreConfigFlag('gomage_navigation/settings/friendly_urls');
    }

    public function isGomageNavigation()
    {
        /*if ($this->isMobileDevice() && $this->_scopeConfig->getValue('gomage_navigation/general/disable_mobile')) {
            return false;
        }
        return in_array(Mage::app()->getStore()->getWebsiteId(), $this->getAvailavelWebsites()) && Mage::getStoreConfigFlag('gomage_navigation/general/mode');*/

        return true;
    }

    public function isGoMageSeoBoosterEnabled()
    {
        /*$_modules = Mage::getConfig()->getNode('modules')->children();
        $_modulesArray = (array)$_modules;
        if (!isset($_modulesArray['GoMage_SeoBooster'])) {
            return false;
        }
        return $_modulesArray['GoMage_SeoBooster']->is('active');*/

        return true;
    }

    public function getRemoveUrl($route, $data)
    {
        $queryParams = is_array($this->_request->getParams()) ? $this->_request->getParams() : array();
        $temp = explode(',', $queryParams[$data['request_var']]);
        $queryParams[$data['request_var']] = implode(',', array_diff($temp, [$data['request_value']]));

        $params = array(
            '_nosid'		=> true,
            '_current'		=> true,
            '_secure'		=> true,
            '_use_rewrite'	=> true,
            '_query'		=> $queryParams,
            '_escape'		=> false,

        );

        return $this->_urlHelper->wrapp($this->_urlBuilder->getUrl($route, $params));
    }

    public function getFilterUrl($route = '*/*/*', $params = array())
    {
        if (!$this->isFriendlyUrl()) {
            $params['_query']['ajax'] = null;
            return $this->_urlHelper->wrapp($this->_urlBuilder->getUrl($route, $params));
        }


        $attr = Mage::registry('gan_filter_attributes');
        $query_params = is_array($this->_request->getParams()) ? $this->_request->getParams() : array();
        $query = array();
        if (isset($params['_query']) && is_array($params['_query'])) {
            $query_params = array_merge($query_params, $params['_query']);
        }
        foreach ($query_params as $param => $value) {
            if (is_null($value)) {
                $query[$param] = null;
                continue;
            }
            if ($param == 'cat') {
                $values = explode(',', $value);
                $prepare_values = array();
                foreach ($values as $_value) {
                    $category = Mage::getModel('catalog/category')->load($_value);
                    if ($category && $category->getId()) {
                        if (Mage::getStoreConfigFlag('gomage_navigation/filter_settings/expend_frendlyurl')) {
                            $parent_ids = $category->getParentIds();
                            $parent_category = Mage::getModel('catalog/category')->load(end($parent_ids));
                            $prepare_values[] = $parent_category->getData('url_key') . '|' . $category->getData('url_key');
                        } else {
                            $prepare_values[] = $category->getData('url_key');
                        }
                    }
                }
                if (!empty($prepare_values)) {
                    $query[$param] = implode(',', $prepare_values);
                } else {
                    $query[$param] = null;
                }
            } elseif (isset($attr[$param]) && !in_array($attr[$param]['type'], array('price', 'decimal'))) {
                $values = explode(',', $value);
                $prepare_values = array();
                foreach ($values as $_value) {
                    foreach ($attr[$param]['options'] as $_k => $_v) {
                        if ($_v == $_value) {
                            $prepare_values[] = $_k;
                            break;
                        }
                    }
                }
                if (!empty($prepare_values)) {
                    $query[$param] = implode(',', $prepare_values);
                } else {
                    $query[$param] = null;
                }
            } elseif (isset($attr[$param]) && in_array($attr[$param]['type'], array('price', 'decimal'))) {
                if (is_array($value)) {
                    if (isset($value['from'])) {
                        $query[$param . '_from'] = $value['from'];
                    }
                    if (isset($value['to'])) {
                        $query[$param . '_to'] = $value['to'];
                    }
                } elseif (($attribute = $this->getProductAttribute($param)) && in_array($attribute->getRangeOptions(), array(GoMage_Navigation_Model_Adminhtml_System_Config_Source_Filter_Optionsrange::MANUALLY, GoMage_Navigation_Model_Adminhtml_System_Config_Source_Filter_Optionsrange::AUTO)) && $attribute->getFilterType() == GoMage_Navigation_Model_Catalog_Layer::FILTER_TYPE_DEFAULT) {
                    $values = explode(',', $value);
                    $query[$param . '_from'] = $values[0];
                    $query[$param . '_to'] = $values[1];
                    $query[$param] = null;
                } else {
                    $query[$param] = $value;
                }
            } else {
                $query[$param] = $value;
            }
        }
        $params['_query'] = $query;
        $params['_query']['ajax'] = null;
        return Mage::helper('gomage_navigation/url')->wrapp($model->getUrl($route, $params));
    }
}
