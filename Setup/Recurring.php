<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 29.05.2018
 * Time: 13:50
 */

namespace GoMage\Navigation\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class Recurring implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $installer->startSetup();
        $cmsPage = $installer->getTable('cms_page');
        $installer->getConnection()->addColumn($cmsPage, 'location',  "TINYINT(1) NOT NULL DEFAULT 0");
        $installer->getConnection()->addColumn($cmsPage, 'navigation_category_id',  "int(10) NOT NULL DEFAULT 0");
        $installer->endSetup();
    }

}