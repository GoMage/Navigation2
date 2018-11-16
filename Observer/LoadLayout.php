<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 19.03.2018
 * Time: 8:29
 */

namespace GoMage\Navigation\Observer;

use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject;

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

class LoadLayout implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $cmsPage;

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
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;
    /**
     * @var  \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * LoadLayout constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Cms\Model\Page $cmsPage
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param CategoryRepository $categoryRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Cms\Model\Page $cmsPage,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        CategoryRepository $categoryRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->registry = $registry;
        $this->request = $request;
        $this->cmsPage = $cmsPage;
        $this->dataHelper = $dataHelper;
        $this->categoryFactory = $categoryFactory;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $layout = $observer->getLayout();
        if ($this->cmsPage->getId() && $this->dataHelper->isEnable()) {
            if ($this->cmsPage->getNavigationCategoryId()) {
                $categoryId = $this->cmsPage->getNavigationCategoryId();
                $storeId = $this->storeManager->getStore()->getId();
                $category = $this->categoryRepository->get($categoryId, $storeId);
                $this->registry->register('current_category', $category);
                $this->registry->register('gomage_cms_page', $this->cmsPage);
                $layout->getUpdate()->addHandle('product_list_cms_view');
                $layout->getUpdate()->addHandle('layer_cms_go_mage');
            }
        }
    }

}
