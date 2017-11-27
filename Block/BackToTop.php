<?php

namespace GoMage\Navigation\Block;

use Magento\Framework\View\Element\Template;

class BackToTop extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $helper;

    /**
     * BackToTop constructor.
     * @param Template\Context $context
     * @param \GoMage\Navigation\Helper\Data $helper
     * @param array $data
     */
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

    /**
     * @return \GoMage\Navigation\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
