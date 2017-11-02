<?php

namespace GoMage\Navigation\Model\Config\Source\Category;

class Templates
{
    /**
     * @param $type
     * @return mixed
     */
    public function get($type)
    {
        $templates = [
            \GoMage\Navigation\Model\Config\Source\Navigation::DEFAULTS => 'GoMage_Navigation::categories/default.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::DROP_DOWN => 'GoMage_Navigation::categories/dropdown.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::FLY_OUT => 'GoMage_Navigation::categories/fly_out.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IMAGE => 'GoMage_Navigation::categories/image.phtml',
            \GoMage\Navigation\Model\Config\Source\Navigation::IN_BLOCK => 'GoMage_Navigation::categories/inblock.phtml',
        ];

        if(empty($templates[$type])) {
            throw new Exception(__('Template is not set for ' . (int) $type . ' filter type'));
        }

        return $templates[$type];
    }
}
