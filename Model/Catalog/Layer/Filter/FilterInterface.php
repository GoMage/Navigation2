<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

/**
 * Navigation filter interface
 */
interface FilterInterface extends \Magento\Catalog\Model\Layer\Filter\FilterInterface
{
    /**
     * @return boolean
     */
    public function isAjax();



}
