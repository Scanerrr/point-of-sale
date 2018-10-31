<?php

use yii\db\Migration;

/**
 * Handles the creation of table `inventory_report`.
 */
class m181031_162233_create_inventory_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('inventory_report', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'reason_id' => $this->smallInteger(1)->comment('0 - damaged, 1 - lost, 2 - tester, 3 - other'),
            'quantity' => $this->integer(),
            'comment' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->addForeignKey('fk-inventory_report-inventory', 'inventory_report', ['location_id', 'product_id'], 'inventory', ['location_id', 'product_id'], 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk-inventory_report-location', 'inventory_report', 'location_id', 'location', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-inventory_report-product', 'inventory_report', 'product_id', 'product', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk-inventory_report-user', 'inventory_report', 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('inventory_report');
    }
}
