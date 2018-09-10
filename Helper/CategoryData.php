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
     * @var \Magento\Catalog\Model\CategoryFactoryCategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem ;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    protected $categoryResource;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $_imageFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory ,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource
    ) {
        $this->categoryResource = $categoryResource;
        $this->categoryFactory = $categoryFactory;
        $this->_filesystem = $filesystem;
        $this->_imageFactory = $imageFactory;
        $this->scopeConfig = $context->getScopeConfig();
        $this->_storeManager = $storeManager;
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
            $section . SystemConfigInterface::SYSTEM_CONFIG_SLASH . $param,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isShowCategories()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_ENABLE
        );
    }

    /**
     * @return mixed
     */
    public function getCategoriesBlockLocation()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_BLOCK_LOCATION
        );
    }

    /**
     * @return mixed
     */
    public function getCategoriesNavigationType()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_NAVIGATION_TYPE
        );
    }

    /**
     * @return mixed
     */
    public function getShowAllSubcategories()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_SHOW_ALL_SUBCATEGORIES
        );
    }

    /**
     * @return mixed
     */
    public function isHideEmptyCategories()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_HIDE_EMPTY_CATEGORIES
        );
    }

    /**
     * @return mixed
     */
    public function isCategoriesShowCollapsed()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IS_SHOW_COLLAPSED
        );
    }

    /**
     * @return mixed
     */
    public function getMaxBlockHeightCategories()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_MAX_BLOCK_HEIGHT
        );
    }

    public function isShowResetLink()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_RESET_LINK
        );
    }
    /**
     * @return mixed
     */
    public function getCategoriesImageAlignment()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IMAGE_ALIGNMENT
        );
    }

    /**
     * @return mixed
     */
    public function getCategoriesImageWidth()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IMAGE_WIDTH
        );
    }

    /**
     * @return mixed
     */
    public function getCategoriesImageHeight()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IMAGE_HEIGHT
        );
    }

    /**
     * @return mixed
     */
    public function isShowImageName()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_IMAGE_NAME
        );
    }

    /**
     * @return mixed
     */
    public function isShowCheckboxes()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CHECKBOX
        );
    }

    /**
     * @return mixed
     */
    public function isAjax()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_FILTER_ACTION
        );
    }

    /**
     * @return mixed
     */
    public function isShowCategoryInShopBy()
    {
        return (int) $this->isShowCategories() &&
            $this->getScopeData(
                SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
                . SystemConfigInterface::SYSTEM_CONFIG_SLASH
                . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_SHOP_BY
            );
    }

    public function resize($image)
    {
        $width = $this->getCategoriesImageWidth();
        $height = $this->getCategoriesImageHeight();

        if(!$width ) {
            $width = 100;
        }

        if(!$height ) {
            $height = 100;
        }
        $absolutePath = $this->_filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath('catalog/category/') . $image;
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
            ->getAbsolutePath('resized/' . $width . '/') . $image;
        //create image factory...
        $imageResize = $this->_imageFactory->create();
        $imageResize->open($absolutePath);
        $imageResize->constrainOnly(true);
        $imageResize->keepTransparency(true);
        $imageResize->keepFrame(false);
        $imageResize->keepAspectRatio(false);
        $imageResize->resize($width, $height);
        //destination folder
        $destination = $imageResized;
        //save image
        $imageResize->save($destination);

        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'resized/' . $width . '/' . $image;;
        return $resizedURL;

    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCategoryImage($id)
    {
        //Find solution to foreach categories with all data in template
        $category = $this->categoryFactory->create();
        $this->categoryResource->load($category, $id);
        return $category->getData('image');
    }

    public function getCategoryResource()
    {
        return $this->categoryResource;
    }
}
