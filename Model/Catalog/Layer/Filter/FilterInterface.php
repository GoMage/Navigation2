<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

/**
 * Navigation filter interface
 */
interface FilterInterface extends \Magento\Catalog\Model\Layer\Filter\FilterInterface
{

    /**
     * Get navigation type
     *
     * @return int
     */
    public function getNavigation();

    /**
     * @return boolean
     */
    public function isAjax();

}
