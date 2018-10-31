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
use Magento\Framework\App\Helper\Context;

/**
 * Class LoaderData
 *
 * @package GoMage\Navigation\Helper
 */
class LoaderData extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Alignment
     */
    protected $alignment;

    /**
     * @var  \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * LoaderData constructor.
     * @param Context $context
     * @param \GoMage\Navigation\Model\Config\Source\Alignment $alignment
     * @param Data $dataHelper
     */
    public function __construct(
        Context $context,
        \GoMage\Navigation\Model\Config\Source\Alignment $alignment,
        \GoMage\Navigation\Helper\Data $dataHelper
    ) {

        $this->dataHelper = $dataHelper;
        $this->scopeConfig = $context->getScopeConfig();
        $this->alignment = $alignment;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->dataHelper->isEnable();
    }

    /**
     * @param $param
     * @param string $section
     * @return mixed
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
    public function isShowAjaxLoader()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_ENABLE
        );
    }

    /**
     * @return mixed
     */
    public function isSpinnerTypeImage()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_SPINNER_TYPE
        );
    }

    /**
     * @return mixed
     */
    public function getLoaderImage()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_IMAGE
        );
    }

    /**
     * @return mixed
     */
    public function getLoaderBackgroundColor()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_BACKGROUND
        );
    }

    /**
     * @return mixed
     */
    public function getLoaderBorderColor()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_BORDER_COLOR
        );
    }

    /**
     * @return mixed
     */
    public function getLoaderTextColor()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_TEXT_COLOR
        );
    }

    /**
     * @return mixed
     */
    public function getLoaderSpinnerColor()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_SPINNER_COLOR
        );
    }

    /**
     * @return mixed
     */
    public function getLoaderWindowWidth()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_WINDOW_WIDTH
        );
    }

    /**
     * @return mixed
     */
    public function getLoaderWindowHeight()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_WINDOW_HEIGHT
        );
    }

    /**
     * @return mixed
     */
    public function getLoaderText()
    {
        return $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_TEXT
        );
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getLoaderImageAlignment()
    {
        $value = $this->getScopeData(
            SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_ALIGNMENT
        );

        return $this->alignment->getAlignment($value);
    }
}
