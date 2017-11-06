<?php
namespace GoMage\Navigation\Block\Navigation\Categories;

class Right extends \GoMage\Navigation\Block\Navigation\Categories\Main
{
    const LOCATION = \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Theme\Block\Html\Topmenu $topMenu,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Model\Config\Source\Category\Templates $templates,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource
    ) {

        parent::__construct($context, $categoryHelper, $categoryFlatState, $topMenu, $dataHelper, $templates, $categoryResource);
    }
}
