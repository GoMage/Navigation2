<?php

namespace GoMage\Navigation\Helper\Block\Navigation;

use GoMage\Navigation\Model\Catalog\Layer\Filter\FilterInterface;

/**
 * Interface FilterRendererInterface
 */
interface FilterRendererInterface
{
    /**
     * Render filter
     *
     * @param  FilterInterface $filter
     * @return string
     */
    public function render(FilterInterface $filter);
}
