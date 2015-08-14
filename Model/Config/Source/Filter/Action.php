<?php

namespace GoMage\Navigation\Model\Config\Source\Filter;

class Action implements \Magento\Framework\Option\ArrayInterface
{

    const PAGE_RELOAD = 0;
    const AJAX = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PAGE_RELOAD, 'label' => __('Page Reload')],
            ['value' => self::AJAX, 'label' => __('Ajax')],
        ];
    }
}
