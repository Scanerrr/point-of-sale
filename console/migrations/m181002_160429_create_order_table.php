<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m181002_160429_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->notNull(),
            'location_id' => $this->integer()->notNull(),
            'employee_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer()->null(),
            'total_tax' => $this->decimal(15, 2)->defaultValue(0),
            'total' => $this->decimal(15, 2)->defaultValue(0),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp() . ' on update CURRENT_TIMESTAMP',
        ]);

        $this->createIndex('idx-order-created_at', 'order', 'created_at');

        $this->addForeignKey('fk-order-location', 'order', 'location_id', 'location', 'id');
        $this->addForeignKey('fk-order-employee', 'order', 'employee_id', 'user', 'id');
        $this->addForeignKey('fk-order-customer', 'order', 'customer_id', 'customer', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order');
    }
}
