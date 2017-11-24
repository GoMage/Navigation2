<?php

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;
use Magento\Framework\App\Helper\Context;

class CategoryData extends \Magento\Framework\App\Helper\AbstractHelper
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

    /**
     * @return mixed
     */
    public function isShowCategories()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_ENABLE);
    }

    /**
     * @return mixed
     */
    public function getCategoriesBlockLocation()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_BLOCK_LOCATION);
    }

    /**
     * @return mixed
     */
    public function getCategoriesNavigationType()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_NAVIGATION_TYPE);
    }

    /**
     * @return mixed
     */
    public function getShowAllSubcategories()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_SHOW_ALL_SUBCATEGORIES);
    }

    /**
     * @return mixed
     */
    public function isHideEmptyCategories()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_HIDE_EMPTY_CATEGORIES);
    }

    /**
     * @return mixed
     */
    public function isCategoriesShowCollapsed()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IS_SHOW_COLLAPSED);
    }

    /**
     * @return mixed
     */
    public function getMaxBlockHeightCategories()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_MAX_BLOCK_HEIGHT);
    }

    /**
     * @return mixed
     */
    public function getCategoriesImageAlignment()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IMAGE_ALIGNMENT);
    }

    /**
     * @return mixed
     */
    public function getCategoriesImageWidth()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IMAGE_WIDTH);
    }

    /**
     * @return mixed
     */
    public function getCategoriesImageHeight()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IMAGE_HEIGHT);
    }

    /**
     * @return mixed
     */
    public function isShowImageName()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IMAGE_NAME);
    }

    /**
     * @return mixed
     */
    public function isShowCheckboxes()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CHECKBOX);
    }

    /**
     * @return mixed
     */
    public function isAjax()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_FILTER_ACTION);
    }

    /**
     * @return mixed
     */
    public function isShowCategoryInShopBy()
    {

        return $this->getScopeData(SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_SHOP_BY);
    }
}
