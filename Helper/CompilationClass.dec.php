<?php
/**
 * Created by PhpStorm.
 * User: dimasik
 * Date: 31.10.18
 * Time: 19.42
 */

namespace GoMage\Navigation\Helper;


class CompilationClass
{
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
        $this->scopeConfig = $context->getScopeConfig();
        $this->request = $context->getRequest();
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
}