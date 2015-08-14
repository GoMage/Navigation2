<?php

namespace GoMage\Navigation\Model\Config\Source\Top;

class Action implements \Magento\Framework\Option\ArrayInterface
{

    const PAGE = 0;
    const PRODUCTS = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PAGE, 'label' => __('Back to top of page')],
            ['value' => self::PRODUCTS, 'label' => __('Back to top of products')],
        ];
    }
}
