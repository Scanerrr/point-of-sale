<?php

use yii\db\Migration;

/**
 * Handles the creation of table `inventory_log`.
 */
class m181030_154750_create_inventory_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('inventory_log', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'comment' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->addForeignKey('fk-inventory_log-inventory', 'inventory_log', ['location_id', 'product_id'], 'inventory', ['location_id', 'product_id'], 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk-inventory_log-location', 'inventory_log', 'location_id', 'location', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-inventory_log-product', 'inventory_log', 'product_id', 'product', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk-inventory_log-user', 'inventory_log', 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('inventory_log');
    }
}
