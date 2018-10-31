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

namespace GoMage\Navigation\Model\Config\Source\Filter;

/**
 * Class Templates
 *
 * @package GoMage\Navigation\Model\Config\Source\Filter
 */
class Templates
{
    /**
     * @param $filterType
     * @return mixed
     * @throws \Exception
     */
    public function get($filterType)
    {
        $templates = [
            \GoMage\Navigation\Model\Config\Source\Navigation::DEFAULTS =>
                'GoMage_Navigation::layer/filter/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::COLOR_PICKER =>
                'GoMage_Navigation::layer/filter/swatches.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN =>
                'GoMage_Navigation::layer/filter/drop_down.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IN_BLOCK =>
                'GoMage_Navigation::layer/filter/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::FLY_OUT =>
                'GoMage_Navigation::layer/filter/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::BUTTON =>
                'GoMage_Navigation::layer/filter/swatchesbutton.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::INPUT => 'GoMage_Navigation::layer/filter/input.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::SLIDER => 'GoMage_Navigation::layer/filter/slider.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::SLIDER_INPUT  =>
                'GoMage_Navigation::layer/filter/slider_input.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::SWATCHES =>
                'GoMage_Navigation::layer/filter/swatches.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IMAGE => 'GoMage_Navigation::layer/filter/image.phtml',
        ];

        if (empty($templates[$filterType])) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Template is not set for ' . (int) $filterType . ' filter type')
            );
        }

        return $templates[$filterType];
    }
}
