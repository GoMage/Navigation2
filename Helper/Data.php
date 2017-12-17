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
        if ($this->isMobile()) {
            return !$this->getScopeData(SystemConfigInterface::SYSTEM_CONFIG_CROUP
                . SystemConfigInterface::SYSTEM_CONFIG_SLASH
                . SystemConfigInterface::SYSTEM_CONFIG_FIELD_MOBILE);
        }

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

    function isMobile()
    {
        $regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|"
            . "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|"
            . "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|"
            . "symbian|smartphone|mmp|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|"
            . "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220"
            . ")/i";

        if (preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return TRUE;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            return TRUE;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
            'wapr','webc','winw','winw','xda ','xda-');

        if (in_array($mobile_ua,$mobile_agents)) {
            return true;
        }

        if (isset($_SERVER['ALL_HTTP']) && strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
            return true;
        }

        return false;
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
}
