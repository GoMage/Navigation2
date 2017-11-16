<?php

namespace GoMage\Navigation\Model\Config\Source;

class SpinnerType implements \Magento\Framework\Option\ArrayInterface
{

    const STANDARD = 0;
    const IMAGE = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::STANDARD, 'label' => __('Standard')],
            ['value' => self::IMAGE, 'label' => __('Image')],
        ];
    }
}
