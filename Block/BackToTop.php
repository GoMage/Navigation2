<?php

namespace GoMage\Navigation\Block;

use Magento\Framework\View\Element\Template;

class BackToTop extends \Magento\Framework\View\Element\Template
{

    protected $helper;
    protected $alignmentConfig;

    public function __construct(
        Template\Context $context,
        \GoMage\Navigation\Helper\Data $helper,
        array $data = []
    ) {
    
        $this->helper = $helper;
        if ($this->helper->isUseBackToTopButton()) {
            $this->setTemplate('GoMage_Navigation::layer/back_to_top.phtml');
        }

        parent::__construct($context, $data);
    }

    public function getHelper()
    {
        return $this->helper;
    }
}
