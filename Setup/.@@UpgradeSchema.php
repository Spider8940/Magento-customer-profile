<?php
namespace Techmail\Devops\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            if (!$installer->tableExists('customer_profile')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('customer_profile')
                )
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Entity ID'
                )
                ->addColumn(
                    'customer_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Customer ID'
                )
                ->addColumn(
                    'file_path',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'File Path'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->setComment('Customer Profile Table');
                $installer->getConnection()->createTable($table);
            }
        }

        $installer->endSetup();
    }
}
