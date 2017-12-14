<?php

namespace GoMage\Navigation\Model\Config\Source\Filter;

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
            \GoMage\Navigation\Model\Config\Source\Navigation::DEFAULTS => 'GoMage_Navigation::layer/filter/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::COLOR_PICKER => 'GoMage_Navigation::layer/filter/swatches.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN => 'GoMage_Navigation::layer/filter/drop_down.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IN_BLOCK => 'GoMage_Navigation::layer/filter/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::BUTTON => 'GoMage_Navigation::layer/filter/button.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::INPUT => 'GoMage_Navigation::layer/filter/input.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::SLIDER => 'GoMage_Navigation::layer/filter/slider.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::SLIDER_INPUT  => 'GoMage_Navigation::layer/filter/slider_input.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::SWATCHES => 'GoMage_Navigation::layer/filter/swatches.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IMAGE => 'GoMage_Navigation::layer/filter/image.phtml',
        ];

        if (empty($templates[$filterType])) {
            throw new \Exception(__('Template is not set for ' . (int) $filterType . ' filter type'));
        }

        return $templates[$filterType];
    }
}
