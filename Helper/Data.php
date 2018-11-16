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
 * @version   Release: 1.1.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;

/**
 * Class Data
 *
 * @package GoMage\Navigation\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     *
     */
    const ALIGNMENT_VERTICAL = 0;
    const ALIGNMENT_HORIZONTAL = 1;
    const ALIGNMENT_2COLUMN = 2;

    protected $_encryptor;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    protected $assetRepository;
    protected $dataHelperCore;
    protected $showMore = [];
    protected $_objectManager;
    protected $_moduleList;
    protected $_directory;
    protected $_jsonHelper;
    protected $_systemStore;
    protected $_dateTime;
    protected $compile;
    const CONTENT = 1;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    protected $cmsPage;

    public function __construct() {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManager');
        $this->_moduleList = $this->_objectManager->get('Magento\Framework\Module\ModuleList');

        $this->_jsonHelper = $this->_objectManager->get('Magento\Framework\Json\Helper\Data');
        $filesystem                        = $this->_objectManager->get('Magento\Framework\Filesystem');
        $this->_directory                  = $filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_storeManager               = $this->_objectManager->get('Magento\Store\Model\StoreManager');
        $this->_systemStore                = $this->_objectManager->get('Magento\Store\Model\System\Store');
        $this->_dateTime                   = $this->_objectManager->get('Magento\Framework\Stdlib\DateTime\DateTime');
        $this->cmsPage                     = $this->_objectManager->get('Magento\Cms\Model\Page');
        $this->_encryptor                  = $this->_objectManager->get('Magento\Framework\Encryption\Encryptor');
        $this->assetRepository                  = $this->_objectManager->get('Magento\Framework\View\Asset\Repository');
        $context                  = $this->_objectManager->get('Magento\Framework\App\Helper\Context');
        $this->compile = $this->_objectManager->get('GoMage\Navigation\Helper\CompilationClass');
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
        $this->request = $context->getRequest();
    }
    /**
     * @param $param
     * @param string $section
     * @return mixed
     */
    public function getScopeData($param, $section = SystemConfigInterface::SYSTEM_CONFIG_SECTION)
    {
        return $this->scopeConfig->getValue(
            $section . SystemConfigInterface::SYSTEM_CONFIG_SLASH . $param,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isEnable()
    {
        return true;
        $info = $this->ga();
        return isset($info['d']) && isset($info['c']) && (int)$info['c'] && $this->getScopeData(
                SystemConfigInterface::SYSTEM_CONFIG_CROUP
                . SystemConfigInterface::SYSTEM_CONFIG_SLASH
                . SystemConfigInterface::SYSTEM_CONFIG_FIELD_ENABLE
            );
    }

    /**
     * @param $item
     * @param $requestVar
     */
    public function checkIsActive($item, $requestVar)
    {
        $params = $this->request->getParam($requestVar);
        $params = explode('_', $params);

        $label = urlencode(mb_strtolower($item->getLabel()));
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
        return (int)$this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_SHOW_SHOP_BY_IN
        );
    }

    /**
     * @return mixed
     */
    public function isShowEmptyCategory()
    {
        return !($this->getScopeData(
            SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CATEGORIES_CONFIG_HIDE_EMPTY_CATEGORIES
        ));
    }

    public function removeBlocCategoriesOrCategory()
    {
        if ($this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_SHOW_SHOP_BY_IN
        )
        ) {
            return '';
        }
    }

    /**
     * @return int
     */
    public function isUseAutoScrolling()
    {
        return (int)$this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_AUTOSCROLLING
        );
    }

    /**
     * @return int
     */
    public function isUseBackToTopButton()
    {
        $value = (int)$this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP
        );

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
        return (int)$this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP_SPEED
        );
    }

    /**
     * @return int
     */
    public function getBackToTopAction()
    {
        return (int)$this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_BACK_TO_TOP_ACTION
        );
    }

    /**
     * @return mixed
     */
    public function getContentFilterType()
    {
        if ($this->cmsPage->getId() && !$this->cmsPage->getLocation() == self::CONTENT) {
            return false;
        }

        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_CONTENT_FILTER_TYPE
        );
    }

    /**
     * @return mixed
     */
    public function isPagerButton()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGER_BUTTON
        );
    }

    /**
     * @return mixed
     */
    public function isShowPager()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGER
        );
    }

    /**
     * @return mixed
     */
    public function isShowValueQty()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_QTY
        );
    }

    /**
     * @return mixed
     */
    public function isAddFilterResultsToUrl()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_HASH
        );
    }

    /**
     * @return mixed
     */
    public function isUseFriendlyUrls()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_USE_FRIENDLY_URLS
        );
    }

    /**
     * @return mixed
     */
    public function isShowAppliedValuesInResults()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_SETTINGS_GROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_SHOW_APPLIED_VALUES
        );
    }

    public function getOptionsAlignment($alignmentOption)
    {
        if (self::ALIGNMENT_2COLUMN === $alignmentOption) {
            return 'gan-align-2-columns';
        } elseif (self::ALIGNMENT_HORIZONTAL === $alignmentOption) {
            return 'gan-align-horizontally';
        } else {
            return 'gan-align-default';
        }
    }

    public function isPaginationAjax()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGINATION_ENABLED
        );
    }

    public function getPagerTheme()
    {
        $isAjax = $this->getScopeData(
            SystemConfigInterface::SYSTEM_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_CONFIG_FIELD_PAGINATION_ENABLED
        );

        if (($isAjax || $this->isUseAutoScrolling()) && $this->isEnable()) {
            return 'GoMage_Navigation::html/pager.phtml';
        } else {
            return 'GoMage_Navigation::html/pager_no_ajax.phtml';
        }
    }

    public function getValueCategory($params)
    {
        $arrCat = explode('_', $this->request->get('cat'));
        $cat = $this->request->get('cat');
        if ($cat && !in_array($params['cat'], $arrCat)) {
            $params['cat'] = $params['cat'] . '_' . $cat;
        }
        return $params['cat'];
    }

    public function getUrlCategory($params)
    {
        $arrCat = explode('_', $this->request->get('cat'));
        $cat = $this->request->get('cat');
        if ($cat && !in_array($params['cat'], $arrCat)) {
            $params['cat'] = $params['cat'] . '_' . $cat;
        }
        return $this->_urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $params]);
    }

    public function isThisActiveCategory($name)
    {
        $arrCat = explode(',', $this->request->get('cat'));
        if (in_array(strtolower($name), $arrCat)) {
            return true;
        }
        return false;
    }

    public function isRequestMore()
    {
        return $this->request->get('more_show');
    }

    public function isShowMore($name)
    {
        if ($this->request->get('more_show')) {
            $this->showMore = explode('_', $this->request->get('more_show'));

            return (int)in_array(strtolower($name), $this->showMore);
        }

        return 0;
    }

    public function isCollapsed($name = '', $isRequest = false)
    {
        if ($isRequest) {
            return $this->request->get('collapsed_expanded');
        }
        $arrexpanded = explode('_', $this->request->get('collapsed_expanded'));
        if (in_array(urlencode(strtolower($name)), $arrexpanded)) {
            return true;
        }
        return false;
    }

    public function isInaCategoryRequest($name, $category = null)
    {
        if (!$category) {
            $arrCat = explode('_', $this->request->get('cat'));
            if (in_array($name, $arrCat)) {
                return true;
            }
            return false;
        } else {
            $arrCat = explode('_', $this->request->get('cat'));
            if (is_object($category)) {
                if (in_array($name, $arrCat)) {
                    return true;
                }
                return false;
            } else {
                if (in_array(strtolower($name), $arrCat)) {
                    return true;
                }
                return false;
            }
        }
    }

    public function getAssets()
    {
        return $this->assetRepository;
    }

    public function getQuerySearchText()
    {
        $query = $this->request->get('q');
        if (!$query) {
            $query = '';
        }
        return $query;
    }

    /**
     * @return array
     */
    public function getAvailableWebsites()
    {
        return $this->compile->getAvailableWebsites();
    }

    public function a($c = 0, $s = '')
    {
        return $this->compile->a();
    }

    /**
     * @return mixed
     */
    public function ga()
    {
        return $this->compile->ga();
    }
    /**
     * @return array
     */
    public function getStoreOptionArray()
    {
        return $this->compile->getStoreOptionArray();
    }

    public function notify()
    {
        return $this->compile->notify();

    }
}
