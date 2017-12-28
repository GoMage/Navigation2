<?php

namespace GoMage\Navigation\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'gomage_navigation_attribute'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('gomage_navigation_attribute')
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Id'
        )->addColumn(
            'attribute_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Attribute Id'
        )->addColumn(
            'filter_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Filter Type'
        )->addColumn(
            'is_show_filter_button',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default'  => '0', 'nullable' => false],
            'Show Filter Button'
        )->addColumn(
            'max_block_height',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Max Block Height'
        )->addColumn(
            'is_ajax',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default'  => '0', 'nullable' => false],
            'Use Ajax'
        )->addColumn(
            'is_collapsed',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default'  => '0', 'nullable' => false],
            'Show Collapsed'
        )->addColumn(
            'is_checkbox',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default'  => '0', 'nullable' => false],
            'Show Checkboxes'
        )->addColumn(
            'is_show_image_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default'  => '0', 'nullable' => false],
            'Show Image Name'
        )->addColumn(
            'options_alignment',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Options Alignment'
        )->addColumn(
            'image_width',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Image Width'
        )->addColumn(
            'image_height',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Image Height'
        )->addColumn(
            'visible_options',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Visible Options per Attribute'
        )->addColumn(
            'is_show_tooltip',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default'  => '0', 'nullable' => false],
            'Show Tooltip'
        )->addColumn(
            'tooltip_width',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Tooltip Window Width'
        )->addColumn(
            'tooltip_height',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Tooltip Window Height'
        )->addColumn(
            'is_reset',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default'  => '0'],
            'Show Reset Link'
        )->addColumn(
            'tooltip_text',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'Tooltip Text'
        )->addColumn(
            'is_exclude_categories',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Exclude Categories'
        )->addForeignKey(
            $installer->getFkName(
                'gomage_navigation_attribute',
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id'
            ),
            'attribute_id',
            $installer->getTable('eav_attribute'),
            'attribute_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
