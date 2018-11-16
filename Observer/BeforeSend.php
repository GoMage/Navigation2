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
 * @version   Release: 1.1.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject;
use Magento\Framework\App\Response\HttpInterface;

/**
 * Class BeforeSend
 *
 * @package GoMage\Navigation\Observer
 */
class BeforeSend implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $response;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $actionFlag;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * BeforeSend constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \Magento\Framework\DataObjectFactory $dataObjectFactory
    ) {
        $this->dataObjectFactory = $dataObjectFactory;
        $this->request = $request;
        $this->response = $response;
        $this->actionFlag = $actionFlag;
        $this->layout = $layout;
        $this->eventManager = $eventManager;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $response = $observer->getResponse();
        if (($this->request->getRouteName() ==
                'catalog' || $this->request->getRouteName() ==
                'cms' || $this->request->getRouteName() == 'catalogsearch')
            && $this->request->isAjax()
            && (($this->request->getParam('gan_ajax_filter')
                || $this->request->getParam('gan_ajax_cat')
                || $this->request->getParam('gan_ajax_more')))
        ) {
            $result = $this->dataObjectFactory->create();
            if ($this->request->getParam('gan_ajax_more') && $this->request->getRouteName() != 'catalogsearch') {
                $result->setData('content', $this->layout->renderElement('category.products'));
            } elseif ($this->request->getParam('gan_ajax_more')) {
                $result->setData('content', $this->layout->renderElement('search.result'));
            }

            if ($this->request->getParam('gan_ajax_filter') && !$this->request->getParam('gan_ajax_more')) {
                $result->setData('content', $this->layout->renderElement('main.content'));
            }

            if ($this->request->getParam('gan_ajax_cat')) {
                $result->setData('content', $this->layout->renderElement('main.content'));
                $result->setData('breadcrumbs', $this->layout->renderElement('breadcrumbs'));
            }

            $this->response->representJson($result->toJson());
        } elseif (($this->request->isAjax()
            && (($this->request->getParam('gan_ajax_filter')
                || $this->request->getParam('gan_ajax_cat')
                || $this->request->getParam('gan_ajax_more'))))
        ) {
            $result = $this->dataObjectFactory->create();
            $result->setData('content', $response->getContent());
            $result->setData('is_full_page_cache', 1);
            $response->representJson($result->toJson());
        }

        return $this;
    }
}
