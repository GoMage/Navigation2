<?php

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{


    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /*
     * @var Varien_Object
     */
    protected $_dataObject;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_scopeConfig = $scopeConfig;
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

    public function newDataObject()
    {
        $this->_dataObject = new Varien_Object();
    }

    public function getDataObject()
    {
        return $this->_dataObject;
    }

    public function storeData($key, $value)
    {
        if (is_object($value)) {
            $this->_dataObject->addData([$key => (string) $value]);
            return $value;
        }

        if(is_array($value)) {

            $data = $this->_dataObject->getData($key);
            if (!is_array($data)) {
                $data = [];
            }
            $data[] = $value;

            $this->_dataObject->addData([$key => $data]);
            return $value;
        }

        $this->_dataObject->addData([$key => $value]);

        return $value;
    }
}
