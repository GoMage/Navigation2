<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

class Decimal extends \Magento\Catalog\Model\Layer\Filter\Decimal implements FilterInterface
{

    /**
     * {@inheritdoc}
     */
    public function isAjax()
    {
        return true;
    }
}
