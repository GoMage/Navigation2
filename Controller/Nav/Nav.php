<?php
/**
 * Created by PhpStorm.
 * User: dimasik
 * Date: 24.8.18
 * Time: 17.43
 */

namespace GoMage\Navigation\Controller\Nav;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


class Nav extends \Magento\Framework\App\Action\Action
{
    protected $pageFactory;
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->pageFactory->create();
        return $result;
    }
}