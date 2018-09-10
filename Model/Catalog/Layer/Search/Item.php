<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Search;

/**
 * Class Item
 *
 * @package GoMage\Navigation\Model\Catalog\Layer\Filter
 */
class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $urlHelper;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Theme\Block\Html\Pager $htmlPagerBlock
     * @param \GoMage\Navigation\Helper\Url   $urlHelper
     * @param \GoMage\Navigation\Helper\Data  $dataHelper
     * @param array                           $data
     */
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
     * @return string
     */
    public function getRemoveUrl()
    {
        if (!$this->dataHelper->isEnable()) {
            return parent::getRemoveUrl();
        }

        return $this->urlHelper->getRemoveUrl($this);
    }

    /**
     * @return string
     */
    public function getRemoveValue()
    {
        return $this->urlHelper->getRemoveValue($this);
    }

    /**
     * @return bool
     */
    public function isShowAppliedValuesRemove()
    {
        if ($this->dataHelper->isShowAppliedValuesInResults() == \GoMage\Navigation\Model\Config\Source\Result::REMOVE) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isCategoryFilter()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isShowAppliedValues()
    {
        if ($this->dataHelper->isShowAppliedValuesInResults() == \GoMage\Navigation\Model\Config\Source\Result::REMOVE && $this->getIsActive()) {
            return false;
        }

        return true;
    }
}
