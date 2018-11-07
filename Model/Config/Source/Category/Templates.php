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
 * @version   Release: 1.0.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Model\Config\Source\Category;

/**
 * Class Templates
 *
 * @package GoMage\Navigation\Model\Config\Source\Category
 */
class Templates
{
    /**
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function get($type)
    {
        $templates = [
            \GoMage\Navigation\Model\Config\Source\Navigation::DEFAULTS =>
                'GoMage_Navigation::categories/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN =>
                'GoMage_Navigation::categories/dropdown.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::FLY_OUT => 'GoMage_Navigation::categories/flyout.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IMAGE => 'GoMage_Navigation::categories/image.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IN_BLOCK =>
                'GoMage_Navigation::categories/default.phtml',
        ];

        if (empty($templates[$type])) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Template is not set for ' . (int) $type . ' filter type')
            );
        }

        return $templates[$type];
    }

    /**
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function getSearch($type)
    {
        $templates = [
            \GoMage\Navigation\Model\Config\Source\Navigation::DEFAULTS =>
                'GoMage_Navigation::categories/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN =>
                'GoMage_Navigation::categories/dropdown.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IMAGE =>
                'GoMage_Navigation::categories/image.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::FLY_OUT =>
                'GoMage_Navigation::categories/flyout.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IN_BLOCK =>
                'GoMage_Navigation::categories/default.phtml',
        ];

        if (empty($templates[$type])) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Template is not set for ' . (int) $type . ' filter type')
            );
        }

        return $templates[$type];
    }

    /**
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function getShowShopByInTemplateSearch($type)
    {
        $templates = [
            \GoMage\Navigation\Model\Config\Source\Navigation::DEFAULTS =>
                'GoMage_Navigation::categories/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN =>
                'GoMage_Navigation::categories/dropdown.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IMAGE =>
                'GoMage_Navigation::categories/image.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::FLY_OUT =>
                'GoMage_Navigation::categories/flyout.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IN_BLOCK =>
                'GoMage_Navigation::categories/default.phtml',
        ];

        if (empty($templates[$type])) {
            throw new  \Magento\Framework\Exception\LocalizedException(
                __('Template is not set for ' . (int) $type . ' filter type')
            );
        }

        return $templates[$type];
    }

    /**
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function getShowShopByInTemplate($type)
    {
        $templates = [
            \GoMage\Navigation\Model\Config\Source\Navigation::DEFAULTS =>
                'GoMage_Navigation::categories/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN =>
                'GoMage_Navigation::categories/dropdown.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IMAGE => 'GoMage_Navigation::categories/image.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::FLY_OUT => 'GoMage_Navigation::categories/flyout.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IN_BLOCK =>
                'GoMage_Navigation::categories/default.phtml',
        ];

        if (empty($templates[$type])) {
            throw new  \Magento\Framework\Exception\LocalizedException(
                __('Template is not set for ' . (int) $type . ' filter type')
            );
        }

        return $templates[$type];
    }
}
