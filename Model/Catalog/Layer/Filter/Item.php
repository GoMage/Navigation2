<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    /**
     * @var boolean
     */
    protected $_isPreferred = false;

    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $urlHelper;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;


    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \GoMage\Navigation\Helper\Url $urlHelper,
        \GoMage\Navigation\Helper\Data $dataHelper,
        array $data = []
    ) {

        $this->urlHelper = $urlHelper;
        $this->dataHelper = $dataHelper;
        parent::__construct($url, $htmlPagerBlock, $data);
    }

    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl()
    {
        return $this->urlHelper->getRemoveUrl($this);

    }

    public function getRemoveValue()
    {
        return $this->urlHelper->getRemoveValue($this);

    }

    public function isShowAppliedValues()
    {
        if ($this->dataHelper->isShowAppliedValuesInResults() == \GoMage\Navigation\Model\Config\Source\Result::REMOVE && $this->getIsActive()) {
            return false;
        }

        return true;
    }
}
