<?php

namespace GoMage\Navigation\Block\Product\ProductList;

use Magento\Framework\View\Element\Template;

class More extends Template
{
    const BLOCK_MORE= 'button-more';

    /**
     * @var Template\Context
     */
    protected $context;

    /**
     * @var
     */
    protected $navigationHelper;

    /**
     * More constructor.
     * @param Template\Context $context
     * @param \GoMage\Navigation\Helper\Data $navigationHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \GoMage\Navigation\Helper\Data $navigationHelper,
        array $data = array()
    )
    {
        parent::__construct($context, $data);
        $this->context = $context;
        $this->navigationHelper = $navigationHelper;
    }

    /**
     * set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('GoMage_Navigation::product/list/toolbar/more.phtml');
    }

    /**
     * @return string
     */
    public function getMoreUrl()
    {
        $url = '';
        if($this->navigationHelper->isPagerButton()) {
            $pager = $this->getPagerBlock();
            if ($pager) {
                if (!$pager->isLastPage()) {
                    $url = $pager->getNextPageUrl();
                }
            }
        }
        return $url;
    }

    /**
     * @return bool|Mage_Page_Block_Html_Pager
     */
    public function getPagerBlock()
    {
        $toolbar = $this->context->getLayout()->getBlock('product_list_toolbar');
        if (!$toolbar || !$toolbar->getCollection()) return false;
        $pagerBlock =$toolbar->getChildBlock('product_list_toolbar_pager');
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
    public function getMoreValue($url){
        $parse_url = parse_url($url);
        return $parse_url['query'];
    }

    /**
     * @return string
     */
    public function getType(){
        return self::BLOCK_MORE;
    }

}
