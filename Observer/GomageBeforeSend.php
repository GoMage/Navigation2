<?php

namespace GoMage\Navigation\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject;

class GomageBeforeSend implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;
    protected $dataHelper;


    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \GoMage\Navigation\Helper\Data $dataHelper
    )
    {
        $this->_request = $request;
        $this->_response = $response;
        $this->_actionFlag = $actionFlag;
        $this->_layout = $layout;
        $this->_eventManager = $eventManager;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     *
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_request->getRouteName() == 'catalog' && $this->_request->isAjax() &&
            (($this->_request->getParam('gan_ajax_filter') ||
                $this->_request->getParam('gan_ajax_cat') ||
                $this->_request->getParam('gan_ajax_more')
            ))){

            $result = new DataObject();

            if ($this->_request->getParam('gan_ajax_more')) {
                $result->setData('content', $this->_layout->renderElement('category.products'));
            }

            if ($this->_request->getParam('gan_ajax_filter') && !$this->_request->getParam('gan_ajax_more')) {
                $result->setData('content', $this->_layout->renderElement('main.content'));
            }

            if ($this->_request->getParam('gan_ajax_cat')) {
                $result->setData('content', $this->_layout->renderElement('main.content'));
                $result->setData('breadcrumbs', $this->_layout->renderElement('breadcrumbs'));
            }

            $this->_response->representJson($result->toJson());
    }

        return $this;
    }
}
