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
    )
    {
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

    /**
     * @return mixed
     */
    public function isShowPager(){

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGER
        );
    }

    public function isShowValueQty(){

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_QTY
        );
    }

    public function isAddFilterResultsToUrl(){

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_HASH
        );
    }

    public function isUseFriendlyUrls(){

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_FRIENDLY_URLS
        );
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

        return $this->_urlBuilder->getUrl($route, $params);
    }

    public function prepareSwatchesData($data, $items)
    {

        foreach ($items as $item) {
            $data['options'][$item->getValue()]['link'] = $item->getGomageUrl();
            $data['options'][$item->getValue()]['gomage_value'] = $item->getGomageValue();
            $data['options'][$item->getValue()]['is_active'] = $item->getIsActive();
        }

        return $data;
    }
}
