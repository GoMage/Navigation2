<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

class Item extends \Magento\Catalog\Model\Layer\Filter\Item
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $urlHelper;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * Item constructor.
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Theme\Block\Html\Pager $htmlPagerBlock
     * @param \GoMage\Navigation\Helper\Url $urlHelper
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Theme\Block\Html\Pager $htmlPagerBlock,
        \GoMage\Navigation\Helper\Url $urlHelper,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \Magento\Framework\App\Helper\Context  $context,
        array $data = []
    ) {
        $this->request = $context->getRequest();
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
    public function isShowAppliedValues()
    {
        if ($this->dataHelper->isShowAppliedValuesInResults() == \GoMage\Navigation\Model\Config\Source\Result::REMOVE && $this->getIsActive()) {
            return false;
        }

        return true;
    }

    public function getGomageValue ()
    {
        $result = $this->getValue();
        if($result) {
            if (!$this->isShowAppliedValues() && !$this->request->get('price')) {
                $result = explode('_', $result);
                if (isset($result[1]) && null !== $result[1]) {
                    $result = $result[1];
                }
            }
        }

        return $result;

    }
}
