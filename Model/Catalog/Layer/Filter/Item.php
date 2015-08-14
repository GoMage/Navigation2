<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    /**
     * Get request variable name which is used for apply filter
     *
     * @return string
     */
    public function getRequestVar()
    {
        return $this->getFilter()->getRequestVar();
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return $this->getFilter()->isAjax();
    }

    /**
     * TODO: detect Item status
     * @return bool
     */
    public function isActive()
    {
        return false;
    }

}
