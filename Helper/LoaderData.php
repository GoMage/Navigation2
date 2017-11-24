<?php

namespace GoMage\Navigation\Helper;

use GoMage\Navigation\Helper\Config\SystemConfigInterface;
use Magento\Framework\App\Helper\Context;

class LoaderData extends \Magento\Framework\App\Helper\AbstractHelper
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
    public function isShowAjaxLoader()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_ENABLE);
    }

    /**
     * @return mixed
     */
    public function isSpinnerTypeImage()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_SPINNER_TYPE);
    }

    /**
     * @return mixed
     */
    public function getLoaderImage()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_IMAGE);
    }

    /**
     * @return mixed
     */
    public function getLoaderBackgroundColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_BACKGROUND);
    }

    /**
     * @return mixed
     */
    public function getLoaderBorderColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_BORDER_COLOR);
    }

    /**
     * @return mixed
     */
    public function getLoaderTextColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_TEXT_COLOR);
    }

    /**
     * @return mixed
     */
    public function getLoaderSpinnerColor()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_SPINNER_COLOR);
    }

    /**
     * @return mixed
     */
    public function getLoaderWindowWidth()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_WINDOW_WIDTH);
    }

    /**
     * @return mixed
     */
    public function getLoaderWindowHeight()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_WINDOW_HEIGHT);
    }

    /**
     * @return mixed
     */
    public function getLoaderText()
    {
        return $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_TEXT);
    }

    /**
     * @return mixed
     */
    public function getLoaderImageAlignment()
    {
        $value = $this->getScopeData(SystemConfigInterface::SYSTEM_LOADER_CONFIG_CROUP
            . SystemConfigInterface::SYSTEM_CONFIG_SLASH
            . SystemConfigInterface::SYSTEM_LOADER_CONFIG_ALIGNMENT);

        $alignment[\GoMage\Navigation\Model\Config\Source\Alignment::LEFT] = 'left';
        $alignment[\GoMage\Navigation\Model\Config\Source\Alignment::RIGHT] = 'right';
        $alignment[\GoMage\Navigation\Model\Config\Source\Alignment::TOP] = 'top';
        $alignment[\GoMage\Navigation\Model\Config\Source\Alignment::BOTTOM] = 'bottom';

        if (empty($alignment[$value])) {
            throw new Exception(__('Alignment position is not set for ' . (int) $value . ' type'));
        }

        return $alignment[$value];
    }
}
