<?php

/**
 * GoMage.com
 *
 * GoMage Navigation M2
 *
 * @category  Extension
 * @copyright Copyright (c) 2018-2018 GoMage.com (https://www.gomage.com)
 * @author    GoMage.com
 * @license   https://www.gomage.com/licensing  Single domain license
 * @terms     of use https://www.gomage.com/terms-of-use
 * @version   Release: 1.0.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Block\Product\ProductList;

use Magento\Framework\View\Element\Template;

class More extends Template
{
    const BLOCK_MORE = 'button-more';

    /**
     * @var Template\Context
     */
    protected $context;

    /**
     * @var
     */
    protected $navigationHelper;

    /**
     * @param Template\Context $context
     * @param \GoMage\Navigation\Helper\Data $navigationHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \GoMage\Navigation\Helper\Data $navigationHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->context = $context;
        $this->navigationHelper = $navigationHelper;

        if (!$this->navigationHelper->isUseAutoScrolling() && $navigationHelper->isEnable()) {
            $this->setTemplate('GoMage_Navigation::product/list/toolbar/more.phtml');
        }
    }

    /**
     * @return string
     */
    public function getMoreUrl()
    {
        $url = '';
        $pager = $this->getPagerBlock();
        if ($pager) {
            if (!$pager->isLastPage()) {
                $url = $pager->getNextPageUrl();
            }
        }

        return $url;
    }

    /**
     * @return bool
     */
    public function getPagerBlock()
    {
        $toolbar = $this->context->getLayout()->getBlock('product_list_toolbar');
        if (!$toolbar || !$toolbar->getCollection()) {
            return false;
        }
        $pagerBlock = $toolbar->getChildBlock('product_list_toolbar_pager');
        if ($pagerBlock) {
            $pagerBlock->setAvailableLimit($toolbar->getAvailableLimit());
            $pagerBlock->setUseContainer(false)
                ->setShowPerPage(false)
                ->setShowAmounts(false)
                ->setLimitVarName($toolbar->getLimitVarName())
                ->setLimit($toolbar->getLimit())
                ->setCollection($toolbar->getCollection());
            return $pagerBlock;
        }
        return false;
    }

    /**
     * @param $url
     * @return int
     */
    public function getMoreValue($url)
    {
        $parse_url = parse_url($url);
        return $parse_url['query'];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::BLOCK_MORE;
    }
}
