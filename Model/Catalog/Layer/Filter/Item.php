<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    /**
     * @var boolean
     */
    protected $_isPreferred = false;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $helper;


    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \GoMage\Navigation\Helper\Data $helper,
        array $data = []
    ) {

        $this->helper = $helper;
        parent::__construct($url, $htmlPagerBlock, $data);
    }

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

    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl($ajax = false)
    {
        $filter_model		= $this->getFilter();
        $filter_request_var	= $filter_model->getRequestVarValue();

        $filter_reset_val	= array(
            $filter_request_var	=> $this->getFilter()->getResetValue($this->getValue()),
        );

        if ($filter_model->hasAttributeModel()) {
            $filter_type = $filter_model->getAttributeModel()->getFilterType();

            if (
                in_array(
                    $filter_type,
                    array(
                        \GoMage\Navigation\Model\Catalog\Layer::FILTER_TYPE_INPUT,
                        \GoMage\Navigation\Model\Catalog\Layer::FILTER_TYPE_SLIDER,
                        \GoMage\Navigation\Model\Catalog\Layer::FILTER_TYPE_SLIDER_INPUT,
                        \GoMage\Navigation\Model\Catalog\Layer::FILTER_TYPE_INPUT_SLIDER
                    )
                ) &&
                !$this->helper->isMobileDevice()
            ) {
                $filter_reset_val = array(
                    $filter_request_var . '_from'	=> null,
                    $filter_request_var . '_to'		=> null
                );
            }
        }

        $params = array(
            '_nosid'		=> true,
            '_current'		=> true,
            '_secure'		=> true,
            '_use_rewrite'	=> true,
            '_query'		=> array(
                'ajax'	=> ($ajax) ? $ajax : null,
            ),
            '_escape'		=> false,

        );

        $params['_query'] = array_merge($params['_query'], $filter_reset_val);

        return $this->helper->getFilterUrl('*/*/*', $params);
    }

}
