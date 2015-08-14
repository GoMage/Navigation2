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
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $columns = [
            'navigation'      => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'unsigned' => true,
                'comment'  => 'Filter Type',
            ],

            'is_show_button'  => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'default'  => '0',
                'nullable' => false,
                'unsigned' => true,
                'comment'  => 'Show Filter Button',
            ],

            'is_ajax'         => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'default'  => '0',
                'nullable' => false,
                'unsigned' => true,
                'comment'  => 'Use Ajax',
            ],

            'is_collapsed'    => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'default'  => '0',
                'nullable' => false,
                'unsigned' => true,
                'comment'  => 'Show Collapsed',
            ],
            'is_checkbox'     =>
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'default'  => '0',
                    'nullable' => false,
                    'unsigned' => true,
                    'comment'  => 'Show Checkboxes',
                ],
            'is_image'        => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'default'  => '0',
                'nullable' => false,
                'unsigned' => true,
                'comment'  => 'Show Image',
            ],

            'image_alignment' => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'default'  => '0',
                'nullable' => false,
                'unsigned' => true,
                'comment'  => 'Image Alignment',
            ],

            'image_width'     => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'unsigned' => true,
                'comment'  => 'Image Width',
            ],

            'image_height'    => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'unsigned' => true,
                'comment'  => 'Image Height',
            ],

            'limit'           => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'unsigned' => true,
                'comment'  => 'Visible Options per Attribute',
            ],

            'is_tooltip'      => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'default'  => '0',
                'nullable' => false,
                'unsigned' => true,
                'comment'  => 'Show Tooltip',
            ],

            'tooltip_width'   => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'unsigned' => true,
                'comment'  => 'Tooltip Window Width',
            ],

            'tooltip_height'  => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'unsigned' => true,
                'comment'  => 'Tooltip Window Height',
            ],

            'tooltip_text'    => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'unsigned' => false,
                'comment'  => 'Tooltip Text',
            ],

            'is_reset'        => [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'default'  => '0',
                'nullable' => false,
                'unsigned' => true,
                'comment'  => 'Show Reset Link',
            ]
        ];

        $connection = $installer->getConnection();
        $table      = $installer->getTable('catalog_eav_attribute');

        foreach ($columns as $columnName => $definition) {
            $connection->addColumn($table, $columnName, $definition);
        }

        $installer->endSetup();
    }
}
