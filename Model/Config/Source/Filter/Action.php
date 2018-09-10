<?php

namespace GoMage\Navigation\Model\Config\Source\Filter;

/**
 * Class Action
 *
 * @package GoMage\Navigation\Model\Config\Source\Filter
 */
class Action implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var int
     */
    const PAGE_RELOAD = 0;
    /**
     * @var int
     */
    const AJAX = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PAGE_RELOAD, 'label' => __('Page Reload (Breadcrumbs)')],
            ['value' => self::AJAX, 'label' => __('Ajax')],
        ];
    }
}
