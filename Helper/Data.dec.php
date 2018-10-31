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
 * @version   Release: 2.0.0
 * @since     Class available since Release 2.0.0
 */

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;

/**
 * Class Data
 *
 * @package GoMage\Navigation\Helper
 */
class Data
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
    /**
     * Helper module name
     *
     * @var string
     */
    protected $_moduleName;

    /**
     * Request object
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $_moduleManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Magento\Framework\HTTP\Header
     */
    protected $_httpHeader;

    /**
     * Event manager
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $_remoteAddress;

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $urlEncoder;

    /**
     * @var \Magento\Framework\Url\DecoderInterface
     */
    protected $urlDecoder;


    /**
     * @var \Magento\Framework\Cache\ConfigInterface
     */
    protected $_cacheConfig;
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

        $this->_moduleManager = $context->getModuleManager();
        $this->_logger = $context->getLogger();
        $this->_request = $context->getRequest();
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->_httpHeader = $context->getHttpHeader();
        $this->_eventManager = $context->getEventManager();
        $this->_remoteAddress = $context->getRemoteAddress();
        $this->_cacheConfig = $context->getCacheConfig();
        $this->urlEncoder = $context->getUrlEncoder();
        $this->urlDecoder = $context->getUrlDecoder();
        $this->scopeConfig = $context->getScopeConfig();
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
            return 'gomage.categories';
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
            if (in_array(strtolower($name), $arrCat)) {
                return true;
            }
            return false;
        } else {
            $arrCat = explode('_', $this->request->get('cat'));
            if (is_object($category)) {
                $parent = $category->getParentCategory();
                $requestParent = $this->request->get('parent_cat_' . $category->getId());
                if ($parent) {
                    $parentId = $parent->getId();
                } else {
                    $parentId = $category->getId();
                }
                if (in_array(strtolower($name), $arrCat) && ($parentId ==
                        $requestParent || $requestParent == $category->getId())) {
                    return true;
                }
                return false;
            } else {
                $parentId = $category['parent_cat'];
                $requestParent = $this->request->get('parent_cat_' . $category['entity_id']);
                if (in_array(strtolower($name), $arrCat) && $parentId == $requestParent) {
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
        if (!$this->scopeConfig->getValue('gomage_activation/navigation/installed') ||
            (intval($this->scopeConfig->getValue('gomage_activation/navigation/count')) > 10)
        ) {
            return [];
        }

        $time_to_update = 60 * 60 * 24 * 15;

        $r = $this->scopeConfig->getValue('gomage_activation/navigation/ar');
        $t = $this->scopeConfig->getValue('gomage_activation/navigation/time');
        $s = $this->scopeConfig->getValue('gomage_activation/navigation/websites');

        $last_check = str_replace($r, '', $this->_encryptor->decrypt($t));

        $sites = explode(',', str_replace($r, '', $this->_encryptor->decrypt($s)));
        $sites = array_diff($sites, ['']);

        if (($last_check + $time_to_update) < $this->_dateTime->gmtTimestamp()) {
            $this->a((int)$this->scopeConfig->getValue('gomage_activation/navigation/count'),
                implode(',', $sites)
            );
        }

        return $sites;
    }

    public function a($c = 0, $s = '')
    {
        $k = $this->scopeConfig->getValue('gomage_settings/navigation/key');

        /** @var \Magento\Config\Model\Config $config */
        $config = $this->_objectManager->create('Magento\Config\Model\Config');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_downloadable/key/check'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=' . urlencode($k) . '&sku=navigation-pro-m2&domains=' . urlencode(implode(',', $this->_getDomains())) . '&ver=' . urlencode($this->_getVersion()));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $content = curl_exec($ch);
        try {
            $r = $this->_jsonHelper->jsonDecode($content);
        } catch (\Exception $e) {
            $r = [];
        }

        if (empty($r)) {

            $value1 = $this->scopeConfig->getValue('gomage_activation/navigation/ar');

            $groups = [
                'navigation' => [
                    'fields' => [
                        'ar'       => ['value' => $value1],
                        'websites' => [
                            'value' => (string)$this->scopeConfig->getValue('gomage_activation/navigation/websites')
                        ],
                        'time'     => [
                            'value' => (string)$this->_encryptor->encrypt($value1 . ($this->_dateTime->gmtTimestamp() - (60 * 60 * 24 * 15 - 1800)) . $value1)
                        ],
                        'count'    => ['value' => $c + 1]
                    ]
                ]
            ];

            $config->setSection('gomage_activation')
                ->setGroups($groups)
                ->save();
            return;
        }

        $value1 = '';
        $value2 = '';

        if (isset($r['d']) && isset($r['c'])) {
            $value1 = $this->_encryptor->encrypt(base64_encode($this->_jsonHelper->jsonEncode($r)));

            if (!$s) {
                $s = $this->scopeConfig->getValue('gomage_settings/navigation/websites');
            }

            $s = array_slice(explode(',', $s), 0, $r['c']);

            $value2 = $this->_encryptor->encrypt($value1 . implode(',', $s) . $value1);

        }
        $groups = [
            'navigation' => [
                'fields' => [
                    'ar'        => ['value' => $value1],
                    'websites'  => ['value' => (string)$value2],
                    'time'      => [
                        'value' => (string)$this->_encryptor->encrypt($value1 . $this->_dateTime->gmtTimestamp() . $value1)
                    ],
                    'installed' => ['value' => 1],
                    'count'     => ['value' => 0]
                ]
            ]
        ];

        $config->setSection('gomage_activation')
            ->setGroups($groups)
            ->save();
    }

    /**
     * @return mixed
     */
    public function ga()
    {
        $value = base64_decode($this->_encryptor->decrypt($this->scopeConfig->getValue('gomage_activation/navigation/ar')));
        if ($value) {
            return $this->_jsonHelper->jsonDecode($value);
        }
        return [];
    }

    /**
     * @return array
     */
    private function _getDomains()
    {
        $domains = [];

        /** @var \Magento\Store\Model\Website $website */
        foreach ($this->_storeManager->getWebsites() as $website) {

            $url = $website->getConfig('web/unsecure/base_url');

            if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
                $domains[] = $domain;
            }

            $url = $website->getConfig('web/secure/base_url');

            if ($domain = trim(preg_replace('/^.*?\\/\\/(.*)?\\//', '$1', $url))) {
                $domains[] = $domain;
            }
        }
        return array_unique($domains);
    }

    /**
     * @return string
     */
    private function _getVersion()
    {
        return $this->_moduleList->getOne('GoMage_Navigation')['setup_version'];
    }

    /**
     * @return array
     */
    public function getStoreOptionArray()
    {
        $options   = [];
        $options[] = ['label' => '', 'value' => ''];

        $websites = $this->getAvailableWebsites();

        $nonEscapableNbspChar = html_entity_decode('&#160;', ENT_NOQUOTES, 'UTF-8');

        foreach ($this->_systemStore->getWebsiteCollection() as $website) {

            if (!in_array($website->getId(), $websites)) {
                continue;
            }

            $websiteShow = false;
            foreach ($this->_systemStore->getGroupCollection() as $group) {
                if ($website->getId() != $group->getWebsiteId()) {
                    continue;
                }
                $groupShow = false;
                foreach ($this->_systemStore->getStoreCollection() as $store) {
                    if ($group->getId() != $store->getGroupId()) {
                        continue;
                    }
                    if (!$websiteShow) {
                        $options[]   = ['label' => $website->getName(), 'value' => []];
                        $websiteShow = true;
                    }
                    if (!$groupShow) {
                        $groupShow = true;
                        $values    = [];
                    }
                    $values[] = [
                        'label' => str_repeat($nonEscapableNbspChar, 4) . $store->getName(),
                        'value' => $store->getId(),
                    ];
                }
                if ($groupShow) {
                    $options[] = [
                        'label' => str_repeat($nonEscapableNbspChar, 4) . $group->getName(),
                        'value' => $values,
                    ];
                }
            }
        }
        return $options;
    }

    public function notify()
    {
        $frequency = (int)$this->scopeConfig->getValue('gomage_notification/notification/frequency');
        if (!$frequency) {
            $frequency = 24;
        }
        $last_update = (int)$this->scopeConfig->getValue('gomage_notification/notification/last_update');

        if (($frequency * 60 * 60 + $last_update) > $this->_dateTime->gmtTimestamp()) {
            return false;
        }

        $timestamp = $last_update;
        if (!$timestamp) {
            $timestamp = $this->_dateTime->gmtTimestamp();
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, sprintf('https://www.gomage.com/index.php/gomage_notification/index/data'));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'sku=navigation-pro-m2&timestamp=' . $timestamp . '&ver=' . urlencode($this->_getVersion()));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $content = curl_exec($ch);

            try {
                $result = $this->_jsonHelper->jsonDecode($content);
            } catch (\Exception $e) {
                $result = false;
            }

            if ($result && isset($result['frequency']) && ($result['frequency'] != $frequency)) {
                $frequency = $result['frequency'];
            }

            if ($result && isset($result['data'])) {
                if (!empty($result['data'])) {
                    /** @var \Magento\AdminNotification\Model\Inbox $inbox */
                    $inbox = $this->_objectManager->create('Magento\AdminNotification\Model\Inbox');
                    $inbox->parse($result['data']);
                }
            }
        } catch (\Exception $e) {
        }

        $groups = [
            'notification' => [
                'fields' => [
                    'frequency'   => ['value' => $frequency],
                    'last_update' => ['value' => $this->_dateTime->gmtTimestamp()]
                ]
            ]
        ];

        /** @var \Magento\Config\Model\Config $config */
        $config = $this->_objectManager->create('Magento\Config\Model\Config');

        $config->setSection('gomage_notification')
            ->setGroups($groups)
            ->save();

    }


    /**
     * Retrieve request object
     *
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function _getRequest()
    {
        return $this->_request;
    }

    /**
     * Retrieve helper module name
     *
     * @return string
     */
    protected function _getModuleName()
    {
        if (!$this->_moduleName) {
            $class = get_class($this);
            $this->_moduleName = substr($class, 0, strpos($class, '\\Helper'));
        }
        return str_replace('\\', '_', $this->_moduleName);
    }

    /**
     * Check whether or not the module output is enabled in Configuration
     *
     * @param string $moduleName Full module name
     * @return boolean
     * use \Magento\Framework\Module\Manager::isOutputEnabled()
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        if ($moduleName === null) {
            $moduleName = $this->_getModuleName();
        }
        return $this->_moduleManager->isOutputEnabled($moduleName);
    }

    /**
     * Retrieve url
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    protected function _getUrl($route, $params = [])
    {
        return $this->_urlBuilder->getUrl($route, $params);
    }
}
