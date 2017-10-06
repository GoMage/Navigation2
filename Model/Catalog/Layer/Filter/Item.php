<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    /**
     * @var boolean
     */
    protected $_isPreferred = false;
    
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
     * @param boolean $value
     * @return $this
     */
    public function setIsPreferred($value)
    {
        $this->_isPreferred = $value;
        return $this;
    }
    
    /**
     * @return boolean
     */
    public function getIsPreferred()
    {
        return $this->_isPreferred;
    }

}
