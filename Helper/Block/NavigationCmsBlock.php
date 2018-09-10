<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 30.05.2018
 * Time: 14:55
 */

namespace GoMage\Navigation\Block;

class NavigationCmsBlock extends \GoMage\Navigation\Block\Navigation
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
     * @var array
     */
    protected $activeFilters;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var string
     */
    protected $pageLayout;

    /**
     * @var bool
     */
    protected $canShowNavigation = false;

    /**
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;

    /**
     * @var \GoMage\Navigation\Helper\CategoryData
     */
    protected $categoryHelper;

    /**
     * @var string
     */
    protected $categoriesHtml;

    /**
     * @var \GoMage\Navigation\Helper\NavigationViewData
     */
    protected $navigationViewHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context       $context
     * @param \Magento\Catalog\Model\Layer\Resolver                  $layerResolver
     * @param \Magento\Catalog\Model\Layer\FilterList                $filterList
     * @param \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag
     * @param \GoMage\Navigation\Helper\Data                         $dataHelper
     * @param \GoMage\Navigation\Helper\CategoryData                 $categoryHelper
     * @param \GoMage\Navigation\Helper\NavigationViewData           $navigationViewHelper
     * @param \Magento\Framework\Registry                            $registry
     * @param array                                                  $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Helper\CategoryData $categoryHelper,
        \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->catalogLayer = $layerResolver->get();
        $this->filterList = $filterList;
        $this->visibilityFlag = $visibilityFlag;
        $this->request = $context->getRequest();
        $this->dataHelper = $dataHelper;
        $this->categoryHelper = $categoryHelper;
        $this->navigationViewHelper = $navigationViewHelper;
        $this->cmsPage = $registry->registry('gomage_cms_page');
        parent::__construct(
            $context,
            $layerResolver,
            $filterList,
            $visibilityFlag,
            $dataHelper,
            $categoryHelper,
            $navigationViewHelper,
            $data
        );

    }

    /**
     * Set block location according settings
     */
    protected function setLocation()
    {
        if (!$this->getDataHelper()->isEnable()) {
            return ;
        }
        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT 
            && $this->cmsPage->getPageLayout() == '1column' 
        ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN 
            && $this->cmsPage->getPageLayout() == '2columns-left' 
        ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT 
            && $this->cmsPage->getPageLayout() == '2columns-left'  
        ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN 
            && $this->cmsPage->getPageLayout() == '3columns' 
        ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN 
            && $this->cmsPage->getPageLayout() == '2columns-right' 
        ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT 
            && $this->cmsPage->getPageLayout() == '2columns-right' 
        ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN 
            && $this->cmsPage->getPageLayout() == '3columns' 
        ) {
            $this->moveBlock('sidebar.additional');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT 
            && $this->cmsPage->getPageLayout()== '3columns' 
        ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }
    }

    /**
     * @param $parent
     */
    protected function moveBlock($parent)
    {
        $this->getLayout()->unsetChild('sidebar.main', 'catalog.leftnav');
        $this->getLayout()->setChild($parent, 'catalog.leftnav', 'catalog.leftnav.moved');
        $this->getLayout()->reorderChild($parent, 'catalog.leftnav', 0);
    }
}
